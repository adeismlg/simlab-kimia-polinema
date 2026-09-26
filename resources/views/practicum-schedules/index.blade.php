<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Jadwal Praktikum</h2>
            @if (auth()->user()->hasAnyRole(['dosen', 'admin']))
                <a href="{{ route('practicum-schedules.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">+ Buat Jadwal</a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-2">
            @forelse ($schedules as $schedule)
                <div class="bg-white shadow rounded p-5">
                    <h3 class="font-medium">{{ $schedule->nama_praktikum }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $schedule->tanggal->format('d-m-Y') }} · {{ $schedule->jam_mulai }}–{{ $schedule->jam_selesai }}
                    </p>
                    <p class="text-sm text-gray-500">Dosen: {{ $schedule->dosen->name }}</p>
                    <p class="text-sm text-gray-500">Sisa kuota: {{ $schedule->sisa_kuota }} / {{ $schedule->kapasitas }}</p>
                    <div class="mt-3 flex gap-3">
                        <a href="{{ route('practicum-schedules.show', $schedule) }}" class="text-indigo-600 text-sm">Detail</a>
                        @if (auth()->user()->hasAnyRole(['mahasiswa', 'eksternal']))
                            <form method="POST" action="{{ route('practicum-bookings.store', $schedule) }}">
                                @csrf
                                <button class="text-green-600 text-sm" {{ $schedule->sisa_kuota <= 0 ? 'disabled' : '' }}>
                                    {{ $schedule->sisa_kuota <= 0 ? 'Penuh' : 'Booking' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-400 col-span-2">Belum ada jadwal praktikum.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $schedules->links() }}</div>
    </div>
</x-app-layout>
