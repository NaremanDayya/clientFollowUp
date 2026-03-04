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

        // Handle personal image upload
        if ($request->hasFile('personal_image')) {
            try {
                $file = $request->file('personal_image');
                \Log::info('Attempting profile image upload', [
                    'user_id' => $request->user()->id,
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);
                
                $path = $file->store('avatars', 'public');
                $data['personal_image'] = $path;
                
                \Log::info('Profile image uploaded successfully', ['path' => $path]);
            } catch (\Exception $e) {
                \Log::error('Profile image upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return Redirect::route('profile.edit')->withErrors(['personal_image' => 'فشل رفع الصورة: ' . $e->getMessage()]);
            }
        } else {
            \Log::info('No profile image uploaded in request');
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
