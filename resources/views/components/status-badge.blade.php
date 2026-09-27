@props(['status'])

@php
    $map = [
        'diajukan' => 'bg-blue-100 text-blue-700',
        'diverifikasi' => 'bg-cyan-100 text-cyan-700',
        'menunggu_pembayaran' => 'bg-amber-100 text-amber-700',
        'menunggu_verifikasi' => 'bg-amber-100 text-amber-700',
        'dibayar' => 'bg-teal-100 text-teal-700',
        'diproses' => 'bg-indigo-100 text-indigo-700',
        'hasil_terbit' => 'bg-purple-100 text-purple-700',
        'selesai' => 'bg-emerald-100 text-emerald-700',
        'lunas' => 'bg-emerald-100 text-emerald-700',
        'disetujui' => 'bg-emerald-100 text-emerald-700',
        'ditolak' => 'bg-red-100 text-red-700',
        'terlambat' => 'bg-red-100 text-red-700',
        'tersedia' => 'bg-emerald-100 text-emerald-700',
        'digunakan' => 'bg-amber-100 text-amber-700',
        'maintenance' => 'bg-orange-100 text-orange-700',
        'rusak' => 'bg-red-100 text-red-700',
        'terjadwal' => 'bg-blue-100 text-blue-700',
    ];
    $classes = $map[$status] ?? 'bg-gray-100 text-gray-600';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium $classes"]) }}>
    {{ ucwords(str_replace('_', ' ', $status)) }}
</span>
