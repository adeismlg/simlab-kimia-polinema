<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Pembayaran" subtitle="Detail tagihan dan status pembayaran" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm border border-emerald-100">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-3 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <dl class="grid grid-cols-2 gap-x-4 gap-y-5 text-sm">
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Jumlah</dt>
                    <dd class="font-medium text-slate-800">Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">PPN</dt>
                    <dd class="font-medium text-slate-800">Rp{{ number_format($payment->ppn, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total</dt>
                    <dd class="font-semibold text-slate-900 text-base">Rp{{ number_format($payment->total, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Status</dt>
                    <dd><x-status-badge :status="$payment->status" /></dd>
                </div>
            </dl>

            @if ($payment->bukti_bayar)
                <a href="{{ Storage::url($payment->bukti_bayar) }}" target="_blank"
                   class="inline-flex items-center gap-1 mt-5 pt-5 border-t border-slate-100 text-emerald-600 text-sm font-medium">
                    Lihat bukti bayar yang sudah diunggah →
                </a>
            @endif
        </div>

        @if ($payment->status !== 'lunas' && auth()->id() === ($payment->payable->user_id ?? null))
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-1">Bayar Online</h3>
                <p class="text-sm text-slate-500 mb-4">Instan via kartu, VA bank, e-wallet, atau QRIS — diverifikasi otomatis.</p>
                <button id="pay-button" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow-sm">
                    <x-icon name="currency" class="w-4 h-4" /> Bayar Sekarang
                </button>
                <p id="pay-error" class="text-sm text-red-500 mt-2 hidden"></p>
            </div>

            <div class="relative text-center text-xs text-slate-400">
                <span class="bg-slate-50 px-3 relative z-10">atau transfer manual</span>
                <div class="absolute inset-x-0 top-1/2 border-t border-slate-200"></div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-3">Unggah Bukti Transfer</h3>
                <form method="POST" action="{{ route('payments.upload-proof', $payment) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="file" name="bukti_bayar" required class="text-sm">
                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Unggah</button>
                </form>
            </div>
        @endif

        @auth
            @if (auth()->user()->hasAnyRole(['laboran', 'admin']) && $payment->status === 'menunggu_verifikasi' && $payment->bukti_bayar)
                <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-800">Verifikasi Pembayaran</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Bukti transfer sudah diunggah dan menunggu konfirmasi Anda.</p>
                    </div>
                    <div class="flex gap-3 shrink-0">
                        <form method="POST" action="{{ route('payments.verify', $payment) }}">
                            @csrf
                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Verifikasi Lunas</button>
                        </form>
                        <form method="POST" action="{{ route('payments.reject', $payment) }}">
                            @csrf
                            <button class="bg-white border border-red-200 hover:bg-red-50 text-red-600 px-4 py-2.5 rounded-lg text-sm">Tolak</button>
                        </form>
                    </div>
                </div>
            @endif
        @endauth
    </div>

    @if ($payment->status !== 'lunas' && auth()->id() === ($payment->payable->user_id ?? null))
        @push('scripts')
        <script src="https://app.{{ $midtransIsProduction ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
                data-client-key="{{ $midtransClientKey }}"></script>
        <script>
            document.getElementById('pay-button').addEventListener('click', async function () {
                const btn = this;
                const errorEl = document.getElementById('pay-error');
                errorEl.classList.add('hidden');
                btn.disabled = true;
                btn.textContent = 'Memuat...';

                try {
                    const res = await fetch("{{ route('payments.snap-token', $payment) }}", {
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                    });
                    const data = await res.json();

                    if (!res.ok) throw new Error(data.message || 'Gagal memuat pembayaran.');

                    window.snap.pay(data.snap_token, {
                        onSuccess: () => window.location.reload(),
                        onPending: () => window.location.reload(),
                        onError: () => { errorEl.textContent = 'Pembayaran gagal, coba lagi.'; errorEl.classList.remove('hidden'); },
                        onClose: () => {},
                    });
                } catch (e) {
                    errorEl.textContent = e.message;
                    errorEl.classList.remove('hidden');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Bayar Sekarang';
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>
