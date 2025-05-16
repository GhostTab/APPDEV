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
        // Get pending applications count
        $pendingApplications = OrganizerApplication::where('status', 'pending')->count();
        
        // Get active organizers count (users with role_id = 2)
        $activeOrganizers = User::where('role_id', 2)->count();
        
        // Get active events (future date)
        $activeEvents = Event::where('date', '>=', now()->format('Y-m-d'))
            ->count();
            
        // Get inactive events (past date)
        $inactiveEvents = Event::where('date', '<', now()->format('Y-m-d'))
            ->count();
        
        return view('admin.dashboard', compact(
            'pendingApplications', 
            'activeOrganizers',
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
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized access.');
        }

        try {
            if ($user->role_id === 2) {
                $user->update(['role_id' => 3]); // Change to regular user role
                return redirect()->back()->with('success', 'User has been demoted from organizer role.');
            }
            return redirect()->back()->with('error', 'User is not an organizer.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to demote user: ' . $e->getMessage());
        }
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