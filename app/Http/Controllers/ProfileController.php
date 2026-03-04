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
        // DEBUG: Check if personal_image file is being received (BEFORE validation)
        dd([
            'hasFile' => $request->hasFile('personal_image'),
            'file' => $request->file('personal_image'),
            'file_isValid' => $request->hasFile('personal_image') ? $request->file('personal_image')->isValid() : null,
            'file_error' => $request->hasFile('personal_image') ? $request->file('personal_image')->getError() : null,
            'file_errorMessage' => $request->hasFile('personal_image') ? $request->file('personal_image')->getErrorMessage() : null,
            'file_size' => $request->hasFile('personal_image') ? $request->file('personal_image')->getSize() : null,
            'file_mimeType' => $request->hasFile('personal_image') ? $request->file('personal_image')->getMimeType() : null,
            'allFiles' => $request->allFiles(),
            'content_type' => $request->header('Content-Type'),
            'php_upload_max_filesize' => ini_get('upload_max_filesize'),
            'php_post_max_size' => ini_get('post_max_size'),
        ]);

        $data = $request->validated();

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
