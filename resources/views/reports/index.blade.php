<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Laporan Rekap" subtitle="Export data sampel uji ke Excel berdasarkan periode" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <form method="GET" action="{{ route('reports.samples.export') }}" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Bulan</label>
                        <select name="month" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                            <option value="">Semua Bulan</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tahun</label>
                        <select name="year" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                            @foreach (range(now()->year, now()->year - 3) as $y)
                                <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Status (opsional)</label>
                    <select name="status" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                        <option value="">Semua Status</option>
                        @foreach (['diajukan', 'diverifikasi', 'menunggu_pembayaran', 'dibayar', 'diproses', 'hasil_terbit', 'selesai', 'ditolak'] as $status)
                            <option value="{{ $status }}">{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow-sm">
                    <x-icon name="chart" class="w-4 h-4" /> Download Excel
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
