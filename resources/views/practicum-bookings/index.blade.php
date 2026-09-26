<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Booking Praktikum Saya</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Praktikum</th>
                        <th class="px-4 py-3">Tanggal</th>
                        @if (auth()->user()->hasAnyRole(['dosen', 'admin', 'laboran']))
                            <th class="px-4 py-3">Peserta</th>
                        @endif
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-4 py-3">{{ $booking->schedule->nama_praktikum }}</td>
                            <td class="px-4 py-3">{{ $booking->schedule->tanggal->format('d-m-Y') }}</td>
                            @if (auth()->user()->hasAnyRole(['dosen', 'admin', 'laboran']))
                                <td class="px-4 py-3">{{ $booking->user->name }}</td>
                            @endif
                            <td class="px-4 py-3">{{ $booking->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
