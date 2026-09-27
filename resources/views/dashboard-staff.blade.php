@php
    $statusChart = $statusChart ?? collect();
    $recentSamples = $recentSamples ?? collect();
    $calibrationAlerts = $calibrationAlerts ?? collect();

    $cards = [
        ['label' => 'Sampel Diajukan', 'value' => $data['total_sampel_diajukan'], 'icon' => 'clipboard-check', 'tone' => 'blue'],
        ['label' => 'Menunggu Pembayaran', 'value' => $data['total_menunggu_pembayaran'], 'icon' => 'currency', 'tone' => 'amber'],
        ['label' => 'Sedang Diproses', 'value' => $data['total_diproses'], 'icon' => 'beaker', 'tone' => 'indigo'],
        ['label' => 'Selesai Bulan Ini', 'value' => $data['total_selesai_bulan_ini'], 'icon' => 'chart', 'tone' => 'emerald'],
    ];
    $tone = [
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Dashboard" subtitle="Ringkasan operasional Lab Kimia" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Selamat datang, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
                <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }} — ringkasan operasional Lab Kimia hari ini.</p>
            </div>
            <a href="{{ route('samples.index') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg">
                <x-icon name="beaker" class="w-4 h-4" /> Kelola Sampel
            </a>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($cards as $card)
                @php $t = $tone[$card['tone']]; @endphp
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-lg {{ $t['bg'] }} flex items-center justify-center">
                            <x-icon :name="$card['icon']" class="w-5 h-5 {{ $t['text'] }}" />
                        </div>
                    </div>
                    <p class="text-2xl font-semibold text-slate-800 mt-3">{{ $card['value'] }}</p>
                    <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-1">Distribusi Status Sampel</h3>
                <p class="text-sm text-slate-500 mb-4">Sebaran seluruh sampel berdasarkan tahap saat ini</p>
                <canvas id="statusChart" height="220"></canvas>
            </div>

            <!-- Calibration alerts -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center gap-2 mb-1">
                    <x-icon name="wrench" class="w-4 h-4 text-orange-500" />
                    <h3 class="font-semibold text-slate-800">Kalibrasi Perlu Perhatian</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">Jatuh tempo ≤ 30 hari</p>

                @forelse ($calibrationAlerts as $cal)
                    <div class="flex items-center justify-between py-2.5 border-t border-slate-100 first:border-t-0">
                        <div>
                            <p class="text-sm font-medium text-slate-700">{{ $cal->instrument->nama_alat }}</p>
                            <p class="text-xs text-slate-400">Jatuh tempo {{ $cal->tanggal_jatuh_tempo->format('d M Y') }}</p>
                        </div>
                        <x-status-badge :status="$cal->tanggal_jatuh_tempo->isPast() ? 'terlambat' : 'terjadwal'" />
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-4 text-center">Tidak ada alat yang perlu perhatian 🎉</p>
                @endforelse

                <a href="{{ route('calibrations.index') }}" class="block mt-4 text-sm text-emerald-600 font-medium">Lihat semua kalibrasi →</a>
            </div>
        </div>

        <!-- Recent activity -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Sampel Terbaru</h3>
                <a href="{{ route('samples.index') }}" class="text-sm text-emerald-600 font-medium">Lihat semua →</a>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-medium">Kode</th>
                        <th class="px-6 py-3 font-medium">Nama Sampel</th>
                        <th class="px-6 py-3 font-medium">Pemohon</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentSamples as $sample)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-mono text-xs text-slate-500">
                                <a href="{{ route('samples.show', $sample) }}" class="text-emerald-600">{{ $sample->kode_sampel }}</a>
                            </td>
                            <td class="px-6 py-3 text-slate-700">{{ $sample->nama_sampel }}</td>
                            <td class="px-6 py-3 text-slate-500">{{ $sample->user->name }}</td>
                            <td class="px-6 py-3"><x-status-badge :status="$sample->status" /></td>
                            <td class="px-6 py-3 text-slate-400">{{ $sample->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const statusLabels = {!! json_encode(collect($statusChart)->keys()->map(fn($s) => ucwords(str_replace('_', ' ', $s)))) !!};
        const statusValues = {!! json_encode(collect($statusChart)->values()) !!};

        new Chart(document.getElementById('statusChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                    barThickness: 28,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
