<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard Staf Lab</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white shadow rounded p-5">
                <p class="text-sm text-gray-500">Sampel Diajukan</p>
                <p class="text-2xl font-semibold">{{ $data['total_sampel_diajukan'] }}</p>
            </div>
            <div class="bg-white shadow rounded p-5">
                <p class="text-sm text-gray-500">Menunggu Pembayaran</p>
                <p class="text-2xl font-semibold">{{ $data['total_menunggu_pembayaran'] }}</p>
            </div>
            <div class="bg-white shadow rounded p-5">
                <p class="text-sm text-gray-500">Sedang Diproses</p>
                <p class="text-2xl font-semibold">{{ $data['total_diproses'] }}</p>
            </div>
            <div class="bg-white shadow rounded p-5">
                <p class="text-sm text-gray-500">Selesai Bulan Ini</p>
                <p class="text-2xl font-semibold">{{ $data['total_selesai_bulan_ini'] }}</p>
            </div>
            <div class="bg-white shadow rounded p-5">
                <p class="text-sm text-gray-500">Pembayaran Perlu Verifikasi</p>
                <p class="text-2xl font-semibold">{{ $data['pembayaran_menunggu_verifikasi'] }}</p>
            </div>
            <div class="bg-white shadow rounded p-5">
                <p class="text-sm text-gray-500">Kalibrasi Akan Jatuh Tempo</p>
                <p class="text-2xl font-semibold">{{ $data['kalibrasi_akan_jatuh_tempo'] }}</p>
            </div>
            <div class="bg-white shadow rounded p-5">
                <p class="text-sm text-gray-500">Booking Praktikum Diajukan</p>
                <p class="text-2xl font-semibold">{{ $data['booking_praktikum_diajukan'] }}</p>
            </div>
        </div>

        <div class="mt-6 flex gap-4 text-sm">
            <a href="{{ route('samples.index') }}" class="text-indigo-600">Kelola Sampel →</a>
            <a href="{{ route('instruments.index') }}" class="text-indigo-600">Kelola Alat →</a>
            <a href="{{ route('calibrations.index') }}" class="text-indigo-600">Kalibrasi →</a>
            <a href="{{ route('practicum-schedules.index') }}" class="text-indigo-600">Jadwal Praktikum →</a>
        </div>
    </div>
</x-app-layout>
