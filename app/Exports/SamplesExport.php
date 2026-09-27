<?php

namespace App\Exports;

use App\Models\Sample;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SamplesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private ?string $status = null,
        private ?string $search = null,
        private ?int $month = null,
        private ?int $year = null,
    ) {
    }

    public function collection(): Collection
    {
        $query = Sample::with(['user', 'parameters', 'payment']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where(fn ($q) => $q->where('kode_sampel', 'like', "%{$this->search}%")
                ->orWhere('nama_sampel', 'like', "%{$this->search}%"));
        }

        if ($this->month) {
            $query->whereMonth('created_at', $this->month);
        }

        if ($this->year) {
            $query->whereYear('created_at', $this->year);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Kode Sampel', 'Nama Sampel', 'Pemohon', 'Tipe Pemohon', 'Instansi',
            'Status', 'Jumlah Parameter', 'Total Biaya', 'Status Pembayaran',
            'Tanggal Daftar', 'Tanggal Update Terakhir',
        ];
    }

    public function map($sample): array
    {
        return [
            $sample->kode_sampel,
            $sample->nama_sampel,
            $sample->user->name,
            ucfirst($sample->user->tipe),
            $sample->user->instansi ?? '-',
            ucwords(str_replace('_', ' ', $sample->status)),
            $sample->parameters->count(),
            $sample->payment->total ?? 0,
            $sample->payment ? ucwords(str_replace('_', ' ', $sample->payment->status)) : '-',
            $sample->created_at->format('d-m-Y'),
            $sample->updated_at->format('d-m-Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
