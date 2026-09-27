@php
    $steps = ['diajukan', 'diverifikasi', 'menunggu_pembayaran', 'dibayar', 'diproses', 'hasil_terbit', 'selesai'];
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Dashboard" subtitle="Status pengujian sampel dan jadwal praktikum Anda" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-6xl mx-auto">
        <!-- Welcome banner -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl p-6 sm:p-8 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold">Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
                <p class="text-emerald-50 text-sm mt-1">Pantau status pengujian sampel dan jadwal praktikum Anda di sini.</p>
            </div>
            <a href="{{ route('samples.create') }}" class="inline-flex items-center gap-2 bg-white text-emerald-700 text-sm font-medium px-4 py-2.5 rounded-lg shrink-0">
                <x-icon name="plus" class="w-4 h-4" /> Daftarkan Sampel
            </a>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-2xl font-semibold text-slate-800">{{ $summary['total_sampel'] }}</p>
                <p class="text-sm text-slate-500">Total Sampel</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-2xl font-semibold text-indigo-600">{{ $summary['sampel_aktif'] }}</p>
                <p class="text-sm text-slate-500">Sedang Berjalan</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-2xl font-semibold text-emerald-600">{{ $summary['sampel_selesai'] }}</p>
                <p class="text-sm text-slate-500">Selesai</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-2xl font-semibold text-purple-600">{{ $summary['booking_aktif'] }}</p>
                <p class="text-sm text-slate-500">Booking Praktikum Aktif</p>
            </div>
        </div>

        <!-- Sample progress -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Progres Sampel Uji</h3>
                <a href="{{ route('samples.index') }}" class="text-sm text-emerald-600 font-medium">Lihat semua →</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($samples as $sample)
                    @php $currentIndex = array_search($sample->status, $steps); @endphp
                    <div class="px-6 py-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <a href="{{ route('samples.show', $sample) }}" class="font-medium text-slate-800 hover:text-emerald-600">{{ $sample->nama_sampel }}</a>
                                <p class="text-xs text-slate-400 font-mono">{{ $sample->kode_sampel }}</p>
                            </div>
                            <x-status-badge :status="$sample->status" />
                        </div>

                        @if ($sample->status === 'ditolak')
                            <p class="text-sm text-red-500">Pendaftaran ditolak — {{ $sample->catatan ?? 'lihat detail untuk info lebih lanjut.' }}</p>
                        @else
                            <div class="flex items-center">
                                @foreach ($steps as $i => $step)
                                    <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                                        <div class="w-3 h-3 rounded-full shrink-0 {{ $i <= $currentIndex ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                        @if (!$loop->last)
                                            <div class="flex-1 h-0.5 {{ $i < $currentIndex ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Tahap saat ini: {{ ucwords(str_replace('_', ' ', $sample->status)) }}</p>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-400 text-sm">
                        Belum ada sampel terdaftar. <a href="{{ route('samples.create') }}" class="text-emerald-600 font-medium">Daftarkan sekarang →</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Practicum bookings -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Booking Praktikum</h3>
                <a href="{{ route('practicum-schedules.index') }}" class="text-sm text-emerald-600 font-medium">Lihat jadwal →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($bookings as $booking)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-700">{{ $booking->schedule->nama_praktikum }}</p>
                            <p class="text-xs text-slate-400">{{ $booking->schedule->tanggal->format('d M Y') }} · {{ $booking->schedule->jam_mulai }}</p>
                        </div>
                        <x-status-badge :status="$booking->status" />
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-slate-400 text-sm">Belum ada booking praktikum.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
