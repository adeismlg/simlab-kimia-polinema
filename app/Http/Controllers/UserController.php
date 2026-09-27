<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = User::query()->with('roles');

        if ($search = $request->get('q')) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($role = $request->get('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $role));
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        $roles = Role::pluck('name');

        return view('users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();

        $roles = Role::pluck('name');

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'tipe' => ['required', 'in:internal,eksternal'],
            'instansi' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tipe' => $validated['tipe'],
            'instansi' => $validated['instansi'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        abort_if($user->id === Auth::id(), 422, 'Tidak bisa menghapus akun sendiri.');

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);
    }
}
