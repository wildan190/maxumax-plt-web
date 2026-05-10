<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementService
{
    public function __construct(
        protected UserRepository $users,
    ) {}

    /**
     * @return array{users: \Illuminate\Contracts\Pagination\LengthAwarePaginator, counts: array, role: mixed}
     */
    public function paginatedListing(Request $request): array
    {
        $search = $request->query('search');
        $role = $request->query('role');

        $users = $this->users->paginateFiltered($search, $role);
        $counts = $this->users->roleCounts();

        return compact('users', 'counts', 'role');
    }

    public function registerUser(Request $request): User
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:user,staff,admin'],
        ]);

        return $this->users->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    }

    public function syncUser(Request $request, User $user): void
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:user,staff,admin'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $this->users->save($user);
    }

    /**
     * @return array{deleted: bool, message?: string}
     */
    public function attemptDeleteAuthenticatedUser(?int $currentUserId, User $target): array
    {
        if ($currentUserId !== null && $currentUserId === $target->id) {
            return ['deleted' => false, 'message' => 'You cannot delete yourself.'];
        }

        $this->users->delete($target);

        return ['deleted' => true];
    }
}
