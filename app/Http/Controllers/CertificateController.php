<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function download(Sample $sample)
    {
        $user = Auth::user();
        abort_unless($sample->user_id === $user->id || $user->hasAnyRole(['laboran', 'admin', 'kepala_lab']), 403);
        abort_unless($sample->testResult && $sample->testResult->status_approval === 'disetujui', 404, 'Sertifikat belum tersedia.');

        $sample->load(['user', 'parameters', 'testResult.approver']);

        $pdf = Pdf::loadView('certificates.pdf', compact('sample'));

        return $pdf->download("sertifikat-{$sample->kode_sampel}.pdf");
    }
}
