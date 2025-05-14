<?php

namespace App\Http\Controllers;

use App\Models\OrganizerApplication;
use Illuminate\Http\Request;
use App\Notifications\OrganizerApplicationStatusNotification;

class OrganizerApplicationController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'experience' => 'required|string'
        ]);

        $user = auth()->user();
        
        OrganizerApplication::create([
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'experience' => $request->experience,
            'status' => 'pending',
            'user_id' => $user->id
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }

    public function adminIndex()
    {
        $applications = OrganizerApplication::latest()->get();
        return view('admin.organizer-applications', compact('applications'));
    }

    public function updateStatus($application)
    {
        $application = OrganizerApplication::findOrFail($application);
        $status = request('status');
        
        $application->update(['status' => $status]);

        if ($status === 'approved') {
            $application->user->update(['role_id' => 2]);
        }

        // Send notification to the user
        $application->user->notify(new OrganizerApplicationStatusNotification($status));

        return redirect()->back()->with('success', 'Application status updated successfully');
    }
}