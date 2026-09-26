<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Pembayaran</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white shadow rounded p-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Jumlah</dt><dd>Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</dd></div>
                <div><dt class="text-gray-500">PPN</dt><dd>Rp{{ number_format($payment->ppn, 0, ',', '.') }}</dd></div>
                <div><dt class="text-gray-500">Total</dt><dd class="font-medium">Rp{{ number_format($payment->total, 0, ',', '.') }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd>{{ str_replace('_', ' ', $payment->status) }}</dd></div>
            </dl>

            @if ($payment->bukti_bayar)
                <p class="mt-4 text-sm">
                    <a href="{{ Storage::url($payment->bukti_bayar) }}" target="_blank" class="text-indigo-600">Lihat bukti bayar yang sudah diunggah</a>
                </p>
            @endif
        </div>

        @if ($payment->status !== 'lunas' && auth()->id() === ($payment->payable->user_id ?? null))
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-medium mb-3">Unggah Bukti Transfer</h3>
                <form method="POST" action="{{ route('payments.upload-proof', $payment) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="file" name="bukti_bayar" required>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Unggah</button>
                </form>
            </div>
        @endif

        @auth
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']) && $payment->status === 'menunggu_verifikasi' && $payment->bukti_bayar)
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('payments.verify', $payment) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded text-sm">Verifikasi Lunas</button>
                    </form>
                    <form method="POST" action="{{ route('payments.reject', $payment) }}">
                        @csrf
                        <button class="bg-red-600 text-white px-4 py-2 rounded text-sm">Tolak</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
