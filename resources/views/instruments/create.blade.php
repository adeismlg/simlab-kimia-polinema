<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Tambah Alat" subtitle="Daftarkan alat/instrumen baru ke inventaris lab" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
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
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm">Simpan</button>
            </form>
        </div>
    </div>
</x-app-layout>
