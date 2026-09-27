<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Jadwal Praktikum" subtitle="Lihat dan kelola jadwal sesi praktikum lab">
            @if (auth()->user()->hasAnyRole(['dosen', 'admin']))
                <a href="{{ route('practicum-schedules.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm">
                    <x-icon name="plus" class="w-4 h-4" /> Buat Jadwal
                </a>
            @endif
        </x-page-header>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto space-y-4">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama praktikum..."
                   class="rounded-lg border-slate-300 text-sm flex-1 min-w-[200px]">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="rounded-lg border-slate-300 text-sm">
            <button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Filter</button>
            @if (request('q') || request('tanggal'))
                <a href="{{ route('practicum-schedules.index') }}" class="px-4 py-2.5 rounded-lg text-sm text-slate-500 border border-slate-200">Reset</a>
            @endif
        </form>

        <div class="grid gap-4 md:grid-cols-2">
            @forelse ($schedules as $schedule)
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <h3 class="font-medium">{{ $schedule->nama_praktikum }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $schedule->tanggal->format('d-m-Y') }} · {{ $schedule->jam_mulai }}–{{ $schedule->jam_selesai }}
                    </p>
                    <p class="text-sm text-gray-500">Dosen: {{ $schedule->dosen->name }}</p>
                    <p class="text-sm text-gray-500">Sisa kuota: {{ $schedule->sisa_kuota }} / {{ $schedule->kapasitas }}</p>
                    <div class="mt-3 flex gap-3">
                        <a href="{{ route('practicum-schedules.show', $schedule) }}" class="text-emerald-600 text-sm">Detail</a>
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
