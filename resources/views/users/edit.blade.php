<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit User" :subtitle="$user->name" />
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-xl p-6">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded border-gray-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded border-gray-300" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe</label>
                        <select name="tipe" class="mt-1 block w-full rounded border-gray-300">
                            <option value="internal" {{ $user->tipe === 'internal' ? 'selected' : '' }}>Internal</option>
                            <option value="eksternal" {{ $user->tipe === 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" class="mt-1 block w-full rounded border-gray-300">
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Instansi</label>
                    <input type="text" name="instansi" value="{{ old('instansi', $user->instansi) }}" class="mt-1 block w-full rounded border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="mt-1 block w-full rounded border-gray-300">
                </div>

                <div class="flex gap-3">
                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm">Simpan Perubahan</button>
                    <a href="{{ route('users.index') }}" class="px-5 py-2 rounded border border-gray-200 text-gray-600">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
