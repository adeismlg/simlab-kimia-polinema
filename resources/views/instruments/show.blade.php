<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $instrument->nama_alat }} ({{ $instrument->kode_alat }})</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded p-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Merk</dt><dd>{{ $instrument->merk ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Lokasi</dt><dd>{{ $instrument->lokasi ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd>{{ $instrument->status }}</dd></div>
            </dl>
        </div>

        <div class="bg-white shadow rounded p-6">
            <h3 class="font-medium mb-3">Riwayat Kalibrasi</h3>
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500">
                    <tr><th class="pb-2">Tanggal</th><th class="pb-2">Jatuh Tempo</th><th class="pb-2">Vendor</th><th class="pb-2">Status</th></tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($instrument->calibrations as $cal)
                        <tr>
                            <td class="py-2">{{ $cal->tanggal_kalibrasi->format('d-m-Y') }}</td>
                            <td class="py-2">{{ $cal->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                            <td class="py-2">{{ $cal->vendor_kalibrasi ?? '-' }}</td>
                            <td class="py-2">{{ $cal->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada riwayat kalibrasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @auth
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']))
                <div class="bg-white shadow rounded p-6">
                    <h3 class="font-medium mb-3">Catat Kalibrasi Baru</h3>
                    <form method="POST" action="{{ route('calibrations.store', $instrument) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700">Tanggal Kalibrasi</label>
                                <input type="date" name="tanggal_kalibrasi" class="mt-1 block w-full rounded border-gray-300" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700">Jatuh Tempo Berikutnya</label>
                                <input type="date" name="tanggal_jatuh_tempo" class="mt-1 block w-full rounded border-gray-300" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Vendor Kalibrasi</label>
                            <input type="text" name="vendor_kalibrasi" class="mt-1 block w-full rounded border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Sertifikat Kalibrasi (PDF)</label>
                            <input type="file" name="file_sertifikat_kalibrasi" class="mt-1">
                        </div>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan Kalibrasi</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
