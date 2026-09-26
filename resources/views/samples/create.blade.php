<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Daftarkan Sampel Uji</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-6">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('samples.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Sampel</label>
                    <input type="text" name="nama_sampel" value="{{ old('nama_sampel') }}"
                        class="mt-1 block w-full rounded border-gray-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Sampel</label>
                    <textarea name="deskripsi" rows="3" class="mt-1 block w-full rounded border-gray-300">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Dokumen Pendukung (opsional)</label>
                    <input type="file" name="file_dokumen" class="mt-1 block w-full">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parameter Uji</label>
                    <div class="space-y-2 border rounded p-4 max-h-72 overflow-y-auto">
                        @foreach ($parameters as $parameter)
                            <label class="flex items-center justify-between text-sm">
                                <span>
                                    <input type="checkbox" name="parameter_ids[]" value="{{ $parameter->id }}" class="mr-2">
                                    {{ $parameter->nama_parameter }}
                                    <span class="text-gray-400">({{ $parameter->satuan }})</span>
                                </span>
                                <span class="text-gray-500">
                                    Rp{{ number_format($parameter->hargaUntuk(auth()->user()), 0, ',', '.') }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded">
                    Daftarkan Sampel
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
