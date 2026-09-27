<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Alat & Instrumen Lab" subtitle="Kelola alat lab dan riwayat kalibrasinya">
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']))
                <a href="{{ route('instruments.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm">
                    <x-icon name="plus" class="w-4 h-4" /> Tambah Alat
                </a>
            @endif
        </x-page-header>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto space-y-4">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama alat..."
                   class="rounded-lg border-slate-300 text-sm flex-1 min-w-[200px]">
            <select name="status" class="rounded-lg border-slate-300 text-sm">
                <option value="">Semua Status</option>
                @foreach (['tersedia', 'digunakan', 'maintenance', 'rusak'] as $option)
                    <option value="{{ $option }}" {{ request('status') === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                @endforeach
            </select>
            <button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Filter</button>
            @if (request('q') || request('status'))
                <a href="{{ route('instruments.index') }}" class="px-4 py-2.5 rounded-lg text-sm text-slate-500 border border-slate-200">Reset</a>
            @endif
        </form>

        <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-slate-400 text-xs uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Kode</th>
                        <th class="px-6 py-3 font-medium">Nama Alat</th>
                        <th class="px-6 py-3 font-medium">Lokasi</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Kalibrasi Terakhir</th>
                        <th class="px-6 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($instruments as $instrument)
                        @php $lastCal = $instrument->calibrations->first(); @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-mono text-xs text-slate-500">{{ $instrument->kode_alat }}</td>
                            <td class="px-6 py-3 text-slate-700">{{ $instrument->nama_alat }}</td>
                            <td class="px-6 py-3 text-slate-500">{{ $instrument->lokasi }}</td>
                            <td class="px-6 py-3">
                                <x-status-badge :status="$instrument->status" />
                            </td>
                            <td class="px-6 py-3">
                                @if ($lastCal)
                                    <span class="text-slate-600">{{ $lastCal->tanggal_kalibrasi->format('d-m-Y') }}</span>
                                    <span class="text-xs {{ $lastCal->tanggal_jatuh_tempo->isPast() ? 'text-red-500' : 'text-slate-400' }}">
                                        (jatuh tempo {{ $lastCal->tanggal_jatuh_tempo->format('d-m-Y') }})
                                    </span>
                                @else
                                    <span class="text-slate-400">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <a href="{{ route('instruments.show', $instrument) }}" class="text-emerald-600 font-medium">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada data alat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $instruments->links() }}</div>
    </div>
</x-app-layout>
