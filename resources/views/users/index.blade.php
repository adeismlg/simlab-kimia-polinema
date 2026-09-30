<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Kelola User" subtitle="Kelola akun, tipe, dan role pengguna sistem" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto space-y-4">
        @if (session('success'))
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm border border-emerald-100">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
                   class="rounded-lg border-slate-300 text-sm flex-1 min-w-[200px]">
            <select name="role" class="rounded-lg border-slate-300 text-sm">
                <option value="">Semua Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm shadow-sm">Filter</button>
            @if (request('q') || request('role'))
                <a href="{{ route('users.index') }}" class="px-4 py-2.5 rounded-lg text-sm text-slate-500 border border-slate-200">Reset</a>
            @endif
        </form>

        <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-slate-400 text-xs uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Nama</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Tipe</th>
                        <th class="px-6 py-3 font-medium">Role</th>
                        <th class="px-6 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-semibold shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                    <span class="text-slate-700">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ ucfirst($user->tipe) }}</span>
                            </td>
                            <td class="px-6 py-3">
                                @forelse ($user->roles as $role)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">{{ $role->name }}</span>
                                @empty
                                    <span class="text-xs text-slate-400">Belum ada role</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('users.edit', $user) }}" class="text-emerald-600 font-medium">Edit</a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                              onsubmit="return confirm('Hapus user ini?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 font-medium">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Tidak ada user ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $users->links() }}</div>
    </div>
</x-app-layout>
