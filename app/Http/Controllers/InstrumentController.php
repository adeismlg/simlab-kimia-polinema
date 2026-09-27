<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstrumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Instrument::with(['calibrations' => fn ($q) => $q->latest('tanggal_kalibrasi')->limit(1)]);

        if ($search = $request->get('q')) {
            $query->where(fn ($q) => $q->where('nama_alat', 'like', "%{$search}%")
                ->orWhere('kode_alat', 'like', "%{$search}%"));
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $instruments = $query->orderBy('nama_alat')->paginate(15)->withQueryString();

        return view('instruments.index', compact('instruments'));
    }

    public function create()
    {
        $this->authorizeStaff();

        return view('instruments.create');
    }

    public function store(Request $request)
    {
        $this->authorizeStaff();

        $validated = $request->validate([
            'kode_alat' => ['required', 'string', 'max:50', 'unique:instruments,kode_alat'],
            'nama_alat' => ['required', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
        ]);

        Instrument::create($validated + ['status' => 'tersedia']);

        return redirect()->route('instruments.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function show(Instrument $instrument)
    {
        $instrument->load(['calibrations' => fn ($q) => $q->latest('tanggal_kalibrasi')]);

        return view('instruments.show', compact('instrument'));
    }

    public function edit(Instrument $instrument)
    {
        $this->authorizeStaff();

        return view('instruments.edit', compact('instrument'));
    }

    public function update(Request $request, Instrument $instrument)
    {
        $this->authorizeStaff();

        $validated = $request->validate([
            'nama_alat' => ['required', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:tersedia,digunakan,maintenance,rusak'],
        ]);

        $instrument->update($validated);

        return back()->with('success', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Instrument $instrument)
    {
        $this->authorizeStaff();

        $instrument->delete();

        return redirect()->route('instruments.index')->with('success', 'Alat berhasil dihapus.');
    }

    private function authorizeStaff(): void
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin']), 403);
    }
}
