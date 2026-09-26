<?php

namespace App\Http\Controllers;

use App\Models\Calibration;
use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalibrationController extends Controller
{
    /**
     * Daftar riwayat kalibrasi, dengan highlight alat yang mendekati/lewat jatuh tempo.
     */
    public function index()
    {
        $calibrations = Calibration::with('instrument')
            ->orderByDesc('tanggal_jatuh_tempo')
            ->paginate(15);

        $akanJatuhTempo = Calibration::with('instrument')
            ->where('tanggal_jatuh_tempo', '<=', now()->addDays(30))
            ->where('status', '!=', 'selesai')
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        return view('calibrations.index', compact('calibrations', 'akanJatuhTempo'));
    }

    public function create(Instrument $instrument)
    {
        $this->authorizeStaff();

        return view('calibrations.create', compact('instrument'));
    }

    public function store(Request $request, Instrument $instrument)
    {
        $this->authorizeStaff();

        $validated = $request->validate([
            'tanggal_kalibrasi' => ['required', 'date'],
            'tanggal_jatuh_tempo' => ['required', 'date', 'after:tanggal_kalibrasi'],
            'vendor_kalibrasi' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'file_sertifikat_kalibrasi' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = $request->hasFile('file_sertifikat_kalibrasi')
            ? $request->file('file_sertifikat_kalibrasi')->store('calibrations', 'public')
            : null;

        $instrument->calibrations()->create($validated + [
            'file_sertifikat_kalibrasi' => $filePath,
            'status' => 'selesai',
        ]);

        return redirect()->route('instruments.show', $instrument)
            ->with('success', 'Data kalibrasi berhasil dicatat.');
    }

    private function authorizeStaff(): void
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin']), 403);
    }
}
