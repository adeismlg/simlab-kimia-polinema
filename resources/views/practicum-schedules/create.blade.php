<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Buat Jadwal Praktikum" subtitle="Atur sesi praktikum baru untuk mahasiswa" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <form method="POST" action="{{ route('practicum-schedules.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Praktikum</label>
                    <input type="text" name="nama_praktikum" class="mt-1 block w-full rounded border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alat yang Digunakan</label>
                    <select name="instrument_id" class="mt-1 block w-full rounded border-gray-300">
                        <option value="">-- Tanpa alat khusus --</option>
                        @foreach ($instruments as $instrument)
                            <option value="{{ $instrument->id }}">{{ $instrument->nama_alat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal" class="mt-1 block w-full rounded border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="mt-1 block w-full rounded border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="mt-1 block w-full rounded border-gray-300" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Kapasitas</label>
                    <input type="number" name="kapasitas" min="1" value="20" class="mt-1 block w-full rounded border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Catatan</label>
                    <textarea name="catatan" rows="2" class="mt-1 block w-full rounded border-gray-300"></textarea>
                </div>
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm">Simpan Jadwal</button>
            </form>
        </div>
    </div>
</x-app-layout>
