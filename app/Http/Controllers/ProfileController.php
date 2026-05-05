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
        $validated = $request->validated();

        // Handle file upload with error handling
        if ($request->hasFile('foto')) {
            try {
                $file = $request->file('foto');
                
                // Validate file
                if (!$file->isValid()) {
                    return Redirect::route('profile.edit')->withErrors(['foto' => 'File upload failed. Please try again.']);
                }
                
                // Generate unique filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                
                // Store file
                $path = $file->storeAs('public/profile', $filename);
                
                if ($path) {
                    $validated['foto'] = 'storage/profile/' . $filename;
                } else {
                    return Redirect::route('profile.edit')->withErrors(['foto' => 'Failed to save file.']);
                }
            } catch (\Exception $e) {
                return Redirect::route('profile.edit')->withErrors(['foto' => 'File upload error: ' . $e->getMessage()]);
            }
        }

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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
