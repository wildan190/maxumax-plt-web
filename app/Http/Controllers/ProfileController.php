<?php

namespace App\Http\Controllers;

use App\Services\Profile\UserProfileSettingsService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request, UserProfileSettingsService $profileSettings)
    {
        $profileSettings->syncBasicProfile($request);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request, UserProfileSettingsService $profileSettings)
    {
        $profileSettings->changePassword($request);

        return back()->with('success', 'Kata sandi berhasil diperbarui');
    }

    public function destroy(Request $request, UserProfileSettingsService $profileSettings)
    {
        $profileSettings->terminateAccount($request);

        return redirect('/')->with('success', 'Akun berhasil dihapus');
    }
}
