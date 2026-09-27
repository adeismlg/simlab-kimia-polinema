<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Kalibrasi Alat" subtitle="Riwayat dan jadwal kalibrasi seluruh alat lab" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto space-y-6">
        @if ($akanJatuhTempo->count())
            <div class="bg-amber-50 border border-amber-300 rounded p-4">
                <h3 class="font-medium text-amber-800 mb-2">⚠️ Perlu Perhatian (≤30 hari / lewat jatuh tempo)</h3>
                <ul class="text-sm text-amber-700 list-disc list-inside">
                    @foreach ($akanJatuhTempo as $cal)
                        <li>{{ $cal->instrument->nama_alat }} — jatuh tempo {{ $cal->tanggal_jatuh_tempo->format('d-m-Y') }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Alat</th>
                        <th class="px-4 py-3">Tanggal Kalibrasi</th>
                        <th class="px-4 py-3">Jatuh Tempo</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($calibrations as $cal)
                        <tr>
                            <td class="px-4 py-3">{{ $cal->instrument->nama_alat }}</td>
                            <td class="px-4 py-3">{{ $cal->tanggal_kalibrasi->format('d-m-Y') }}</td>
                            <td class="px-4 py-3">{{ $cal->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                            <td class="px-4 py-3"><x-status-badge :status="$cal->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada data kalibrasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $calibrations->links() }}</div>
    </div>
</x-app-layout>
