<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizerApplication;

class OrganizerController extends Controller
{
    public function showApplicationForm()
    {
        if (auth()->user()->role_id === 1) {
            return redirect()->route('home')->with('error', 'You are already an admin.');
        }

        if (auth()->user()->role_id === 2) {
            return redirect()->route('home')->with('error', 'You are already an organizer.');
        }

        return view('organizer.apply');
    }

    public function submitApplication(Request $request)
    {
        $request->validate([
            'experience' => 'required|string|min:10',
        ]);

        if (auth()->user()->organizerApplication && auth()->user()->organizerApplication->status === 'pending') {
            return redirect()->route('organizer.apply')->with('error', 'You already have a pending application.');
        }

        OrganizerApplication::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'first_name' => auth()->user()->first_name,
                'last_name' => auth()->user()->last_name,
                'email' => auth()->user()->email,
                'experience' => $request->experience,
                'status' => 'pending'
            ]
        );

        return redirect()->route('organizer.apply')->with('success', 'Your application has been submitted successfully.');
    }
} 