<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Daftarkan Sampel Uji" subtitle="Isi detail sampel dan pilih parameter yang ingin diuji" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('samples.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Sampel</label>
                    <input type="text" name="nama_sampel" value="{{ old('nama_sampel') }}"
                        class="mt-1 block w-full rounded-lg border-slate-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Deskripsi Sampel</label>
                    <textarea name="deskripsi" rows="3" class="mt-1 block w-full rounded-lg border-slate-300">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Dokumen Pendukung (opsional)</label>
                    <input type="file" name="file_dokumen" class="mt-1 block w-full text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Parameter Uji</label>
                    <div class="border border-slate-200 rounded-lg divide-y divide-slate-100 max-h-72 overflow-y-auto">
                        @foreach ($parameters as $parameter)
                            <label class="flex items-center justify-between text-sm px-4 py-3 hover:bg-slate-50 cursor-pointer">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" name="parameter_ids[]" value="{{ $parameter->id }}"
                                           class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-slate-700">
                                        {{ $parameter->nama_parameter }}
                                        <span class="text-slate-400">({{ $parameter->satuan }})</span>
                                    </span>
                                </span>
                                <span class="text-slate-500 font-medium shrink-0">
                                    Rp{{ number_format($parameter->hargaUntuk(auth()->user()), 0, ',', '.') }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm">
                    <x-icon name="plus" class="w-4 h-4" /> Daftarkan Sampel
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
