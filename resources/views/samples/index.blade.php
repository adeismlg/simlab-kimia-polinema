<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Daftar Sampel Uji</h2>
            <a href="{{ route('samples.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">
                + Daftarkan Sampel
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Kode Sampel</th>
                        <th class="px-4 py-3">Nama Sampel</th>
                        @auth
                            @if (auth()->user()->hasAnyRole(['laboran', 'admin', 'kepala_lab']))
                                <th class="px-4 py-3">Pemohon</th>
                            @endif
                        @endauth
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal Daftar</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($samples as $sample)
                        <tr>
                            <td class="px-4 py-3 font-mono">{{ $sample->kode_sampel }}</td>
                            <td class="px-4 py-3">{{ $sample->nama_sampel }}</td>
                            @auth
                                @if (auth()->user()->hasAnyRole(['laboran', 'admin', 'kepala_lab']))
                                    <td class="px-4 py-3">{{ $sample->user->name }}</td>
                                @endif
                            @endauth
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs bg-gray-100">{{ str_replace('_', ' ', $sample->status) }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $sample->created_at->format('d-m-Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('samples.show', $sample) }}" class="text-indigo-600">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada sampel terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $samples->links() }}</div>
    </div>
</x-app-layout>
