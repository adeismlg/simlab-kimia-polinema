<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="$instrument->nama_alat" :subtitle="'Kode: ' . $instrument->kode_alat" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm border border-emerald-100">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <dl class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Merk</dt>
                    <dd class="font-medium text-slate-800">{{ $instrument->merk ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Lokasi</dt>
                    <dd class="font-medium text-slate-800">{{ $instrument->lokasi ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Status</dt>
                    <dd><x-status-badge :status="$instrument->status" /></dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Riwayat Kalibrasi</h3>
            <table class="w-full text-sm">
                <thead class="text-slate-400 text-xs uppercase border-b border-slate-100">
                    <tr><th class="pb-3 font-medium">Tanggal</th><th class="pb-3 font-medium">Jatuh Tempo</th><th class="pb-3 font-medium">Vendor</th><th class="pb-3 font-medium">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($instrument->calibrations as $cal)
                        <tr>
                            <td class="py-3 text-slate-700">{{ $cal->tanggal_kalibrasi->format('d-m-Y') }}</td>
                            <td class="py-3 text-slate-700">{{ $cal->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                            <td class="py-3 text-slate-600">{{ $cal->vendor_kalibrasi ?? '-' }}</td>
                            <td class="py-3"><x-status-badge :status="$cal->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-400">Belum ada riwayat kalibrasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @auth
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']))
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Catat Kalibrasi Baru</h3>
                    <form method="POST" action="{{ route('calibrations.store', $instrument) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-slate-700 mb-1">Tanggal Kalibrasi</label>
                                <input type="date" name="tanggal_kalibrasi" class="block w-full rounded-lg border-slate-300 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-700 mb-1">Jatuh Tempo Berikutnya</label>
                                <input type="date" name="tanggal_jatuh_tempo" class="block w-full rounded-lg border-slate-300 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-700 mb-1">Vendor Kalibrasi</label>
                            <input type="text" name="vendor_kalibrasi" class="block w-full rounded-lg border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-700 mb-1">Sertifikat Kalibrasi (PDF)</label>
                            <input type="file" name="file_sertifikat_kalibrasi" class="block w-full text-sm">
                        </div>
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Simpan Kalibrasi</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
