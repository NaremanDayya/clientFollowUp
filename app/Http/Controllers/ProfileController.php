<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // DEBUG: Check if personal_image file is being received
        dd([
            'hasFile' => $request->hasFile('personal_image'),
            'file' => $request->file('personal_image'),
            'allFiles' => $request->allFiles(),
            'personal_image_in_data' => array_key_exists('personal_image', $data),
            'content_type' => $request->header('Content-Type'),
        ]);

        // Handle personal image upload
        if ($request->hasFile('personal_image')) {
            $path = $request->file('personal_image')->store('avatars', 'public');
            $data['personal_image'] = $path;
        }

        // Update email verification if email changed
        if (isset($data['email']) && $data['email'] !== $request->user()->email) {
            $data['email_verified_at'] = null;
        }

        $request->user()->update($data);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
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
