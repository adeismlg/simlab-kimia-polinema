<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Kelola User" subtitle="Kelola akun, tipe, dan role pengguna sistem" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
                   class="rounded border-gray-300 text-sm flex-1 min-w-[200px]">
            <select name="role" class="rounded border-gray-300 text-sm">
                <option value="">Semua Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Filter</button>
        </form>

        <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-xl overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ ucfirst($user->tipe) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @forelse ($user->roles as $role)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">{{ $role->name }}</span>
                                @empty
                                    <span class="text-xs text-gray-400">Belum ada role</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 space-x-3">
                                <a href="{{ route('users.edit', $user) }}" class="text-emerald-600 font-medium">Edit</a>
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Hapus user ini?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Tidak ada user ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>
