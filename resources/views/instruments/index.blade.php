<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Alat & Instrumen Lab</h2>
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']))
                <a href="{{ route('instruments.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">+ Tambah Alat</a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama Alat</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Kalibrasi Terakhir</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($instruments as $instrument)
                        @php $lastCal = $instrument->calibrations->first(); @endphp
                        <tr>
                            <td class="px-4 py-3 font-mono">{{ $instrument->kode_alat }}</td>
                            <td class="px-4 py-3">{{ $instrument->nama_alat }}</td>
                            <td class="px-4 py-3">{{ $instrument->lokasi }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs bg-gray-100">{{ $instrument->status }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($lastCal)
                                    {{ $lastCal->tanggal_kalibrasi->format('d-m-Y') }}
                                    <span class="text-xs {{ $lastCal->tanggal_jatuh_tempo->isPast() ? 'text-red-600' : 'text-gray-400' }}">
                                        (jatuh tempo {{ $lastCal->tanggal_jatuh_tempo->format('d-m-Y') }})
                                    </span>
                                @else
                                    <span class="text-gray-400">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('instruments.show', $instrument) }}" class="text-indigo-600">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada data alat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $instruments->links() }}</div>
    </div>
</x-app-layout>
