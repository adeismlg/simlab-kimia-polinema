<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Alat</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-6">
            <form method="POST" action="{{ route('instruments.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Alat</label>
                    <input type="text" name="kode_alat" class="mt-1 block w-full rounded border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Alat</label>
                    <input type="text" name="nama_alat" class="mt-1 block w-full rounded border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Merk</label>
                    <input type="text" name="merk" class="mt-1 block w-full rounded border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" name="lokasi" class="mt-1 block w-full rounded border-gray-300">
                </div>
                <button class="bg-indigo-600 text-white px-5 py-2 rounded">Simpan</button>
            </form>
        </div>
    </div>
</x-app-layout>
