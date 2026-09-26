<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow rounded p-6">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-medium">Sampel Uji Terbaru</h3>
                <a href="{{ route('samples.index') }}" class="text-indigo-600 text-sm">Lihat semua →</a>
            </div>
            @forelse ($samples as $sample)
                <div class="flex justify-between py-2 border-t text-sm">
                    <span>{{ $sample->kode_sampel }} — {{ $sample->nama_sampel }}</span>
                    <span class="text-gray-500">{{ str_replace('_', ' ', $sample->status) }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Belum ada sampel terdaftar. <a href="{{ route('samples.create') }}" class="text-indigo-600">Daftarkan sekarang</a></p>
            @endforelse
        </div>

        <div class="bg-white shadow rounded p-6">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-medium">Booking Praktikum Terbaru</h3>
                <a href="{{ route('practicum-schedules.index') }}" class="text-indigo-600 text-sm">Lihat jadwal →</a>
            </div>
            @forelse ($bookings as $booking)
                <div class="flex justify-between py-2 border-t text-sm">
                    <span>{{ $booking->schedule->nama_praktikum }}</span>
                    <span class="text-gray-500">{{ $booking->status }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Belum ada booking praktikum.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
