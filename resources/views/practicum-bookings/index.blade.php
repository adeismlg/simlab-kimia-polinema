<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Booking Praktikum Saya" subtitle="Riwayat booking praktikum Anda" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
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
                            <td class="px-4 py-3"><x-status-badge :status="$booking->status" /></td>
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
