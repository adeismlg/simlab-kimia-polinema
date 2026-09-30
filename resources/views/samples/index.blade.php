<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Daftar Sampel Uji" subtitle="Kelola pendaftaran dan pantau status pengujian sampel">
            <a href="{{ route('samples.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm">
                <x-icon name="plus" class="w-4 h-4" /> Daftarkan Sampel
            </a>
        </x-page-header>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto space-y-4">
        @if (session('success'))
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm border border-emerald-100">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap gap-3 flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama sampel..."
                       class="rounded-lg border-slate-300 text-sm flex-1 min-w-[200px]">
                <select name="status" class="rounded-lg border-slate-300 text-sm">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}" {{ request('status') === $option ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $option)) }}
                        </option>
                    @endforeach
                </select>
                <button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Filter</button>
                @if (request('q') || request('status'))
                    <a href="{{ route('samples.index') }}" class="px-4 py-2.5 rounded-lg text-sm text-slate-500 border border-slate-200">Reset</a>
                @endif
            </form>

            @if ($isStaff)
                <a href="{{ route('reports.samples.export', request()->query()) }}"
                   class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-4 py-2.5 rounded-lg shrink-0">
                    <x-icon name="chart" class="w-4 h-4" /> Export Excel
                </a>
            @endif
        </div>

        @if ($isStaff)
            <form method="POST" action="{{ route('samples.label-batch') }}" target="_blank" id="label-batch-form">
                @csrf
                <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-400 text-xs uppercase border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3 font-medium w-10">
                                    <input type="checkbox" id="select-all" class="rounded border-slate-300">
                                </th>
                                <th class="px-6 py-3 font-medium">Kode Sampel</th>
                                <th class="px-6 py-3 font-medium">Nama Sampel</th>
                                <th class="px-6 py-3 font-medium">Pemohon</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium">Tanggal Daftar</th>
                                <th class="px-6 py-3 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($samples as $sample)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-3">
                                        <input type="checkbox" name="sample_ids[]" value="{{ $sample->id }}" class="label-checkbox rounded border-slate-300">
                                    </td>
                                    <td class="px-6 py-3 font-mono text-xs text-slate-500">{{ $sample->kode_sampel }}</td>
                                    <td class="px-6 py-3 text-slate-700">{{ $sample->nama_sampel }}</td>
                                    <td class="px-6 py-3 text-slate-500">{{ $sample->user->name }}</td>
                                    <td class="px-6 py-3"><x-status-badge :status="$sample->status" /></td>
                                    <td class="px-6 py-3 text-slate-400">{{ $sample->created_at->format('d-m-Y') }}</td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('samples.show', $sample) }}" class="text-emerald-600 font-medium">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada sampel terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($samples->count())
                    <div class="mt-3 flex items-center justify-between">
                        <p class="text-xs text-slate-400">Centang sampel untuk cetak label sekaligus (buka tab baru sebagai PDF)</p>
                        <button type="submit" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-4 py-2.5 rounded-lg">
                            <x-icon name="clipboard-check" class="w-4 h-4" /> Cetak Label Terpilih
                        </button>
                    </div>
                @endif
            </form>
        @else
            <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-slate-400 text-xs uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 font-medium">Kode Sampel</th>
                            <th class="px-6 py-3 font-medium">Nama Sampel</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Tanggal Daftar</th>
                            <th class="px-6 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($samples as $sample)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-mono text-xs text-slate-500">{{ $sample->kode_sampel }}</td>
                                <td class="px-6 py-3 text-slate-700">{{ $sample->nama_sampel }}</td>
                                <td class="px-6 py-3"><x-status-badge :status="$sample->status" /></td>
                                <td class="px-6 py-3 text-slate-400">{{ $sample->created_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('samples.show', $sample) }}" class="text-emerald-600 font-medium">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada sampel terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <div>{{ $samples->links() }}</div>
    </div>

    @if ($isStaff)
        @push('scripts')
        <script>
            document.getElementById('select-all')?.addEventListener('change', function () {
                document.querySelectorAll('.label-checkbox').forEach(cb => cb.checked = this.checked);
            });
        </script>
        @endpush
    @endif
</x-app-layout>
