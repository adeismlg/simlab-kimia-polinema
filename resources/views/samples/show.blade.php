<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Detail Sampel" :subtitle="$sample->kode_sampel">
            @if (auth()->user()->hasAnyRole(['laboran', 'admin', 'kepala_lab']))
                <a href="{{ route('samples.label', $sample) }}" target="_blank"
                   class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-4 py-2.5 rounded-lg">
                    <x-icon name="clipboard-check" class="w-4 h-4" /> Cetak Label
                </a>
            @endif
        </x-page-header>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm border border-emerald-100">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <dl class="grid grid-cols-2 gap-x-4 gap-y-5 text-sm">
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Nama Sampel</dt>
                    <dd class="font-medium text-slate-800">{{ $sample->nama_sampel }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Status</dt>
                    <dd><x-status-badge :status="$sample->status" /></dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Pemohon</dt>
                    <dd class="font-medium text-slate-800">{{ $sample->user->name }} <span class="text-slate-400 font-normal">({{ $sample->user->tipe }})</span></dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Tanggal Daftar</dt>
                    <dd class="font-medium text-slate-800">{{ $sample->created_at->format('d-m-Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Label Fisik</dt>
                    <dd class="font-medium text-slate-800">
                        @if ($sample->label_dicetak_at)
                            Dicetak {{ $sample->label_dicetak_at->format('d-m-Y H:i') }}
                        @else
                            <span class="text-slate-400 font-normal">Belum dicetak</span>
                        @endif
                    </dd>
                </div>
            </dl>
            @if ($sample->deskripsi)
                <p class="mt-5 pt-5 border-t border-slate-100 text-sm text-slate-600">{{ $sample->deskripsi }}</p>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Parameter Uji</h3>
            <table class="w-full text-sm">
                <thead class="text-slate-400 text-xs uppercase border-b border-slate-100">
                    <tr><th class="pb-3 font-medium">Parameter</th><th class="pb-3 font-medium">Hasil</th><th class="pb-3 font-medium">Harga</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($sample->parameters as $parameter)
                        <tr>
                            <td class="py-3 text-slate-700">{{ $parameter->nama_parameter }}</td>
                            <td class="py-3 text-slate-600">{{ $parameter->pivot->hasil ?? '-' }} {{ $parameter->pivot->satuan_hasil }}</td>
                            <td class="py-3 text-slate-600">Rp{{ number_format($parameter->pivot->harga_saat_daftar, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="mt-4 pt-4 border-t border-slate-100 text-right font-semibold text-slate-800">Total: Rp{{ number_format($sample->total_biaya, 0, ',', '.') }}</p>
        </div>

        @if ($sample->payment)
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-3">Pembayaran</h3>
                <div class="flex items-center justify-between text-sm">
                    <div>
                        <p class="text-slate-500">Total tagihan</p>
                        <p class="font-semibold text-slate-800 text-base mt-0.5">Rp{{ number_format($sample->payment->total, 0, ',', '.') }}</p>
                    </div>
                    <x-status-badge :status="$sample->payment->status" />
                </div>
                <a href="{{ route('payments.show', $sample->payment) }}" class="inline-flex items-center gap-1 mt-4 text-emerald-600 text-sm font-medium">Kelola Pembayaran →</a>
            </div>
        @endif

        @auth
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']) && $sample->status === 'diajukan')
                <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-800">Verifikasi Sampel</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Konfirmasi sampel diterima dan buat tagihan pembayaran.</p>
                    </div>
                    <form method="POST" action="{{ route('samples.verify', $sample) }}">
                        @csrf
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm shrink-0">Verifikasi Sampel</button>
                    </form>
                </div>
            @endif

            @if (auth()->user()->hasAnyRole(['laboran', 'admin']) && in_array($sample->status, ['dibayar', 'diproses']))
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Input Hasil Uji</h3>
                    <form method="POST" action="{{ route('samples.input-hasil', $sample) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        @foreach ($sample->parameters as $parameter)
                            <div class="flex items-center gap-3">
                                <label class="w-48 text-sm text-slate-600">{{ $parameter->nama_parameter }}</label>
                                <input type="text" name="hasil[{{ $parameter->id }}]" class="flex-1 rounded-lg border-slate-300 text-sm" placeholder="Hasil">
                            </div>
                        @endforeach
                        <div class="pt-2">
                            <label class="block text-sm text-slate-700 mb-1">File Sertifikat (opsional)</label>
                            <input type="file" name="file_sertifikat" class="mt-1 text-sm">
                        </div>
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Simpan Hasil</button>
                    </form>
                </div>
            @endif

            @if (auth()->user()->hasRole('kepala_lab') && $sample->testResult && $sample->testResult->status_approval === 'menunggu')
                <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-800">Approval Hasil Uji</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Hasil uji sudah diinput laboran dan menunggu persetujuan Anda.</p>
                    </div>
                    <form method="POST" action="{{ route('samples.approve-hasil', $sample) }}">
                        @csrf
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm shrink-0">Setujui Hasil Uji</button>
                    </form>
                </div>
            @endif

            @if ($sample->status === 'selesai' && $sample->testResult && $sample->testResult->status_approval === 'disetujui')
                <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-800">Sertifikat Hasil Uji</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Pengujian selesai dan telah disetujui kepala lab.</p>
                    </div>
                    <a href="{{ route('certificates.download', $sample) }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm shrink-0">
                        Unduh Sertifikat (PDF)
                    </a>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
