<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Detail Sampel — {{ $sample->kode_sampel }}</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded p-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Nama Sampel</dt><dd class="font-medium">{{ $sample->nama_sampel }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd class="font-medium">{{ str_replace('_', ' ', $sample->status) }}</dd></div>
                <div><dt class="text-gray-500">Pemohon</dt><dd class="font-medium">{{ $sample->user->name }} ({{ $sample->user->tipe }})</dd></div>
                <div><dt class="text-gray-500">Tanggal Daftar</dt><dd class="font-medium">{{ $sample->created_at->format('d-m-Y H:i') }}</dd></div>
            </dl>
            @if ($sample->deskripsi)
                <p class="mt-4 text-sm text-gray-600">{{ $sample->deskripsi }}</p>
            @endif
        </div>

        <div class="bg-white shadow rounded p-6">
            <h3 class="font-medium mb-3">Parameter Uji</h3>
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500">
                    <tr><th class="pb-2">Parameter</th><th class="pb-2">Hasil</th><th class="pb-2">Harga</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($sample->parameters as $parameter)
                        <tr>
                            <td class="py-2">{{ $parameter->nama_parameter }}</td>
                            <td class="py-2">{{ $parameter->pivot->hasil ?? '-' }} {{ $parameter->pivot->satuan_hasil }}</td>
                            <td class="py-2">Rp{{ number_format($parameter->pivot->harga_saat_daftar, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="mt-3 text-right font-medium">Total: Rp{{ number_format($sample->total_biaya, 0, ',', '.') }}</p>
        </div>

        @if ($sample->payment)
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-medium mb-2">Pembayaran</h3>
                <p class="text-sm">Total tagihan: <strong>Rp{{ number_format($sample->payment->total, 0, ',', '.') }}</strong></p>
                <p class="text-sm">Status: <strong>{{ str_replace('_', ' ', $sample->payment->status) }}</strong></p>
                <a href="{{ route('payments.show', $sample->payment) }}" class="inline-block mt-3 text-indigo-600 text-sm">Kelola Pembayaran →</a>
            </div>
        @endif

        @auth
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']) && $sample->status === 'diajukan')
                <form method="POST" action="{{ route('samples.verify', $sample) }}">
                    @csrf
                    <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Verifikasi Sampel</button>
                </form>
            @endif

            @if (auth()->user()->hasAnyRole(['laboran', 'admin']) && in_array($sample->status, ['dibayar', 'diproses']))
                <div class="bg-white shadow rounded p-6">
                    <h3 class="font-medium mb-3">Input Hasil Uji</h3>
                    <form method="POST" action="{{ route('samples.input-hasil', $sample) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        @foreach ($sample->parameters as $parameter)
                            <div class="flex items-center gap-3">
                                <label class="w-48 text-sm">{{ $parameter->nama_parameter }}</label>
                                <input type="text" name="hasil[{{ $parameter->id }}]" class="flex-1 rounded border-gray-300 text-sm" placeholder="Hasil">
                            </div>
                        @endforeach
                        <div>
                            <label class="block text-sm text-gray-700">File Sertifikat (opsional)</label>
                            <input type="file" name="file_sertifikat" class="mt-1">
                        </div>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan Hasil</button>
                    </form>
                </div>
            @endif

            @if (auth()->user()->hasRole('kepala_lab') && $sample->testResult && $sample->testResult->status_approval === 'menunggu')
                <form method="POST" action="{{ route('samples.approve-hasil', $sample) }}">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded text-sm">Setujui Hasil Uji</button>
                </form>
            @endif

            @if ($sample->status === 'selesai' && $sample->testResult && $sample->testResult->status_approval === 'disetujui')
                <a href="{{ route('certificates.download', $sample) }}" class="inline-block bg-gray-800 text-white px-4 py-2 rounded text-sm">
                    Unduh Sertifikat Hasil Uji (PDF)
                </a>
            @endif
        @endauth
    </div>
</x-app-layout>
