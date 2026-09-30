<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Kalibrasi Alat" subtitle="Riwayat dan jadwal kalibrasi seluruh alat lab" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto space-y-6">
        @if ($akanJatuhTempo->count())
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex items-center gap-2 mb-3">
                    <x-icon name="wrench" class="w-4 h-4 text-amber-600" />
                    <h3 class="font-semibold text-amber-800 text-sm">Perlu Perhatian (≤30 hari / lewat jatuh tempo)</h3>
                </div>
                <ul class="space-y-1.5">
                    @foreach ($akanJatuhTempo as $cal)
                        <li class="text-sm text-amber-700 flex items-center justify-between">
                            <span>{{ $cal->instrument->nama_alat }}</span>
                            <span class="text-amber-600">jatuh tempo {{ $cal->tanggal_jatuh_tempo->format('d-m-Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-slate-400 text-xs uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Alat</th>
                        <th class="px-6 py-3 font-medium">Tanggal Kalibrasi</th>
                        <th class="px-6 py-3 font-medium">Jatuh Tempo</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($calibrations as $cal)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 text-slate-700">{{ $cal->instrument->nama_alat }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $cal->tanggal_kalibrasi->format('d-m-Y') }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $cal->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                            <td class="px-6 py-3"><x-status-badge :status="$cal->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data kalibrasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $calibrations->links() }}</div>
    </div>
</x-app-layout>
