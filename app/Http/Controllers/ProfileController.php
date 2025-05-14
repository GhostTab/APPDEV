<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
    
        $user->update($request->only(['first_name', 'middle_name', 'last_name', 'email']));
    
        return back()->with('success', 'Profile updated successfully');
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if (auth()->user()->profile_image && file_exists(public_path(auth()->user()->profile_image))) {
                unlink(public_path(auth()->user()->profile_image));
            }

            // Store new image
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/profile'), $imageName);
            $path = 'images/profile/' . $imageName;

            // Update user profile_image
            auth()->user()->update([
                'profile_image' => $path
            ]);

            return back()->with('success', 'Profile picture updated successfully!');
        }

        return back()->with('error', 'Please select an image to upload.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $user = Auth::user();
    
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }
    
        $user->update([
            'password' => Hash::make($request->password)
        ]);
    
        return back()->with('success', 'Password updated successfully');
    }
}
