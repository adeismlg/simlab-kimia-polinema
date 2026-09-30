<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    /**
     * Cetak satu label untuk satu sampel — ukuran kecil (mirip label lab/rumah sakit),
     * ditempel langsung ke wadah fisik sampel agar tidak tertukar.
     */
    public function show(Sample $sample)
    {
        $this->authorizeAccess($sample);

        $sample->load('user', 'parameters');

        $qrDataUri = $this->generateQrCode(route('samples.show', $sample));

        $sample->update(['label_dicetak_at' => now()]);

        $pdf = Pdf::loadView('labels.sample', compact('sample', 'qrDataUri'))
            ->setPaper([0, 0, 226.77, 141.73]); // ~80mm x 50mm, ukuran umum label lab

        return $pdf->stream("label-{$sample->kode_sampel}.pdf");
    }

    /**
     * Cetak beberapa label sekaligus (misal untuk semua sampel yang baru diverifikasi hari ini).
     */
    public function batch(Request $request)
    {
        abort_unless($this->userHasAnyRole(Auth::user(), ['laboran', 'admin']), 403);

        $validated = $request->validate([
            'sample_ids' => ['required', 'array', 'min:1'],
            'sample_ids.*' => ['exists:samples,id'],
        ]);

        $samples = Sample::with('user', 'parameters')->whereIn('id', $validated['sample_ids'])->get();

        $labels = $samples->map(fn ($sample) => [
            'sample' => $sample,
            'qrDataUri' => $this->generateQrCode(route('samples.show', $sample)),
        ]);

        Sample::whereIn('id', $validated['sample_ids'])->update(['label_dicetak_at' => now()]);

        $pdf = Pdf::loadView('labels.batch', compact('labels'))->setPaper('a4');

        return $pdf->stream('label-sampel-' . now()->format('Y-m-d') . '.pdf');
    }

    private function generateQrCode(string $url): string
    {
        $result = new Builder(
            writer: new PngWriter(),
            data: $url,
            size: 160,
            margin: 4,
        );

        $result = $result->build();

        return $result->getDataUri();
    }

    private function userHasAnyRole(?object $user, array $roles): bool
    {
        if (! $user) {
            return false;
        }

        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole($roles);
        }

        if (method_exists($user, 'roles')) {
            return $user->roles()->whereIn('name', $roles)->exists();
        }

        return false;
    }

    private function authorizeAccess(Sample $sample): void
    {
        $user = Auth::user();
        abort_unless(
            $sample->user_id === $user?->id || $this->userHasAnyRole($user, ['laboran', 'admin', 'kepala_lab']),
            403
        );
    }
}
