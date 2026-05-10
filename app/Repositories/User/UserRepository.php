<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function paginateFiltered(?string $search, ?string $role, int $perPage = 15): LengthAwarePaginator
    {
        return User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->when($role && $role !== 'all', function ($query) use ($role) {
                return $query->where('role', $role);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * @return array{all: int, admin: int, staff: int}
     */
    public function roleCounts(): array
    {
        return [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'staff' => User::where('role', 'staff')->count(),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): User
    {
        return User::create($attributes);
    }

    public function save(User $user): bool
    {
        return $user->save();
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
