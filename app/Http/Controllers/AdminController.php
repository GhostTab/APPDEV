<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\OrganizerApplication;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingApplications = OrganizerApplication::where('status', 'pending')->count();
        $approvedApplications = OrganizerApplication::where('status', 'approved')->count();
        $rejectedApplications = OrganizerApplication::where('status', 'rejected')->count();
        
        // Get active events (future date)
        $activeEvents = Event::where('date', '>=', now()->format('Y-m-d'))
            ->count();
            
        // Get inactive events (past date)
        $inactiveEvents = Event::where('date', '<', now()->format('Y-m-d'))
            ->count();
        
        return view('admin.dashboard', compact(
            'pendingApplications', 
            'approvedApplications', 
            'rejectedApplications',
            'activeEvents',
            'inactiveEvents'
        ));
    }

    public function organizers()
    {
        $organizers = User::where('role_id', 2)
            ->whereDoesntHave('organizerApplication', function($query) {
                $query->where('status', 'pending');
            })
            ->withCount('events')
            ->get();
        
        return view('admin.organizers', compact('organizers'));
    }

    public function demoteOrganizer(User $user)
    {
        if ($user->role_id === 2) {
            $user->update(['role_id' => 3]); // Change to regular user role
        }
        
        return redirect()->back()->with('success', 'User has been demoted from organizer role.');
    }

    public function events()
    {
        $events = Event::with('user')
            ->latest()
            ->paginate(10);
        
        return view('admin.events', compact('events'));
    }

    public function updateEventStatus(Request $request, $eventId)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $event = Event::findOrFail($eventId);
        $event->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Event status updated successfully.');
    }
}