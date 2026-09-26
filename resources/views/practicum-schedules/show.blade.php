<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $schedule->nama_praktikum }}</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded p-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Tanggal</dt><dd>{{ $schedule->tanggal->format('d-m-Y') }}</dd></div>
                <div><dt class="text-gray-500">Jam</dt><dd>{{ $schedule->jam_mulai }}–{{ $schedule->jam_selesai }}</dd></div>
                <div><dt class="text-gray-500">Dosen</dt><dd>{{ $schedule->dosen->name }}</dd></div>
                <div><dt class="text-gray-500">Alat</dt><dd>{{ $schedule->instrument->nama_alat ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Kuota</dt><dd>{{ $schedule->sisa_kuota }} / {{ $schedule->kapasitas }}</dd></div>
            </dl>
        </div>

        <div class="bg-white shadow rounded p-6">
            <h3 class="font-medium mb-3">Peserta</h3>
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500">
                    <tr><th class="pb-2">Nama</th><th class="pb-2">Status</th>
                        @if (auth()->user()->hasAnyRole(['dosen', 'laboran', 'admin']))
                            <th class="pb-2"></th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($schedule->bookings as $booking)
                        <tr>
                            <td class="py-2">{{ $booking->user->name }}</td>
                            <td class="py-2">{{ $booking->status }}</td>
                            @if (auth()->user()->hasAnyRole(['dosen', 'laboran', 'admin']) && $booking->status === 'diajukan')
                                <td class="py-2 space-x-2">
                                    <form method="POST" action="{{ route('practicum-bookings.status', [$booking, 'disetujui']) }}" class="inline">
                                        @csrf
                                        <button class="text-green-600 text-xs">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('practicum-bookings.status', [$booking, 'ditolak']) }}" class="inline">
                                        @csrf
                                        <button class="text-red-600 text-xs">Tolak</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-400">Belum ada peserta booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
