<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\Payment;
use App\Models\Sample;
use App\Notifications\SampleStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SampleController extends Controller
{
    /**
     * Daftar sampel. User biasa lihat miliknya sendiri; laboran/admin lihat semua.
     * Mendukung pencarian (?q=) dan filter status (?status=).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isStaff = $user->hasAnyRole(['laboran', 'admin', 'kepala_lab']);

        $query = $isStaff
            ? Sample::with(['user', 'parameters'])
            : Sample::with('parameters')->where('user_id', $user->id);

        if ($search = $request->get('q')) {
            $query->where(fn ($q) => $q->where('kode_sampel', 'like', "%{$search}%")
                ->orWhere('nama_sampel', 'like', "%{$search}%"));
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $samples = $query->latest()->paginate(15)->withQueryString();
        $statusOptions = [
            'diajukan', 'diverifikasi', 'menunggu_pembayaran', 'dibayar',
            'diproses', 'hasil_terbit', 'selesai', 'ditolak',
        ];

        return view('samples.index', compact('samples', 'statusOptions', 'isStaff'));
    }

    public function create()
    {
        $parameters = Parameter::where('aktif', true)->get();

        return view('samples.create', compact('parameters'));
    }

    /**
     * Simpan pendaftaran sampel baru + parameter yang dipilih (dengan snapshot harga).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sampel' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file_dokumen' => ['nullable', 'file', 'max:10240'],
            'parameter_ids' => ['required', 'array', 'min:1'],
            'parameter_ids.*' => ['exists:parameters,id'],
        ]);

        $user = Auth::user();

        $sample = DB::transaction(function () use ($validated, $request, $user) {
            $filePath = $request->hasFile('file_dokumen')
                ? $request->file('file_dokumen')->store('samples/dokumen', 'public')
                : null;

            $sample = Sample::create([
                'kode_sampel' => $this->generateKodeSampel(),
                'user_id' => $user->id,
                'nama_sampel' => $validated['nama_sampel'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'file_dokumen' => $filePath,
                'status' => 'diajukan',
            ]);

            $parameters = Parameter::whereIn('id', $validated['parameter_ids'])->get();

            $syncData = [];
            foreach ($parameters as $parameter) {
                $syncData[$parameter->id] = [
                    'harga_saat_daftar' => $parameter->hargaUntuk($user),
                ];
            }
            $sample->parameters()->sync($syncData);

            return $sample;
        });

        $user->notify(new SampleStatusUpdated($sample));

        return redirect()->route('samples.index')
            ->with('success', 'Sampel berhasil didaftarkan dan menunggu verifikasi laboran.');
    }

    public function show(Sample $sample)
    {
        $this->authorizeAccess($sample);

        $sample->load(['user', 'parameters', 'testResult', 'payment']);

        return view('samples.show', compact('sample'));
    }

    /**
     * Laboran memverifikasi sampel -> lanjut ke tahap pembayaran.
     */
    public function verify(Sample $sample)
    {
        $this->authorizeStaff();

        $sample->update([
            'status' => 'menunggu_pembayaran',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // buat record payment otomatis berdasarkan total biaya parameter
        $total = Payment::hitungTotal($sample->total_biaya, $sample->user->tipe);

        $sample->payment()->create([
            'jumlah' => $total['jumlah'],
            'ppn' => $total['ppn'],
            'total' => $total['total'],
            'status' => 'menunggu_verifikasi',
        ]);

        $sample->user->notify(new SampleStatusUpdated($sample));

        return back()->with('success', 'Sampel diverifikasi. Tagihan pembayaran telah dibuat.');
    }

    /**
     * Laboran input hasil setelah pembayaran lunas & pengujian selesai.
     */
    public function inputHasil(Request $request, Sample $sample)
    {
        $this->authorizeStaff();

        $validated = $request->validate([
            'hasil' => ['required', 'array'],
            'hasil.*' => ['required', 'string'],
            'file_sertifikat' => ['nullable', 'file', 'max:10240'],
        ]);

        foreach ($validated['hasil'] as $parameterId => $hasil) {
            $sample->parameters()->updateExistingPivot($parameterId, ['hasil' => $hasil]);
        }

        $filePath = $request->hasFile('file_sertifikat')
            ? $request->file('file_sertifikat')->store('samples/sertifikat', 'public')
            : null;

        $sample->testResult()->updateOrCreate(
            ['sample_id' => $sample->id],
            ['file_sertifikat' => $filePath, 'status_approval' => 'menunggu']
        );

        $sample->update(['status' => 'hasil_terbit']);

        $sample->user->notify(new SampleStatusUpdated($sample));

        return back()->with('success', 'Hasil uji berhasil diinput, menunggu approval kepala lab.');
    }

    /**
     * Kepala lab approve hasil -> status sampel jadi "selesai".
     */
    public function approveHasil(Sample $sample)
    {
        abort_unless(Auth::user()->hasRole('kepala_lab'), 403);

        $sample->testResult->update([
            'status_approval' => 'disetujui',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $sample->update(['status' => 'selesai']);

        $sample->user->notify(new SampleStatusUpdated($sample));

        return back()->with('success', 'Hasil uji disetujui dan sampel selesai diproses.');
    }

    private function generateKodeSampel(): string
    {
        $tahun = now()->format('Y');
        $urutan = Sample::whereYear('created_at', $tahun)->count() + 1;

        return sprintf('SMP-%s-%s', $tahun, str_pad($urutan, 4, '0', STR_PAD_LEFT));
    }

    private function authorizeAccess(Sample $sample): void
    {
        $user = Auth::user();
        abort_unless(
            $sample->user_id === $user->id || $user->hasAnyRole(['laboran', 'admin', 'kepala_lab']),
            403
        );
    }

    private function authorizeStaff(): void
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin']), 403);
    }
}
