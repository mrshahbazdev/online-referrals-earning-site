<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'mobile_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($user->profile_image_url) {
            Storage::disk('public')->delete($user->profile_image_url);
        }

        $path = $request->file('profile_image')->store('profile-photos', 'public');
        $user->profile_image_url = $path;
        $user->save();

        return response()->json([
            'success' => 'Profile image updated successfully.',
            'image_url' => asset('storage/' . $path)
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
