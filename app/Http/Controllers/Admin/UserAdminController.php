<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserManagementService;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function __construct(
        protected UserManagementService $userManagement,
    ) {}

    public function index(Request $request)
    {
        return view('admin.users.index', $this->userManagement->paginatedListing($request));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->userManagement->registerUser($request);

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->userManagement->syncUser($request, $user);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $result = $this->userManagement->attemptDeleteAuthenticatedUser(auth()->id(), $user);
        if (!$result['deleted']) {
            return back()->with('error', $result['message'] ?? 'Cannot delete.');
        }

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}
