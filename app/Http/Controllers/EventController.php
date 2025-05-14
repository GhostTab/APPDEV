<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Notifications\NewEventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('user')
            ->where('status', 'approved')
            ->where(function($q) {
                $q->where('date', '>', now()->format('Y-m-d'))
                  ->orWhere(function($q) {
                      $q->where('date', '=', now()->format('Y-m-d'))
                        ->where('time', '>', now()->format('H:i:s'));
                  });
            })
            ->latest();
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }
        
        $events = $query->paginate(9);
        $categories = Event::distinct()->pluck('category')->sort();
        
        return view('events.index', compact('events', 'categories'));
    }

    public function pastEvents(Request $request)
    {
        $query = Event::with('user')
            ->where('status', 'approved')
            ->where(function($q) {
                $q->where('date', '<', now()->format('Y-m-d'))
                  ->orWhere(function($q) {
                      $q->where('date', '=', now()->format('Y-m-d'))
                        ->where('time', '<=', now()->addHours(1)->format('H:i:s'));
                  });
            })
            ->latest();
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('date', 'asc')->orderBy('time', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('date', 'desc')->orderBy('time', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('title', 'desc');
                    break;
            }
        }
        
        $events = $query->paginate(9);
        $categories = Event::distinct()->pluck('category')->sort();
        
        return view('events.past', compact('events', 'categories'));
    }

    public function create()
    {
        return view('events.create');
    }

    // Remove these duplicate use statements
    // use App\Notifications\NewEventNotification;
    // use App\Models\User;

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'description' => 'required|string|min:50|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $request->file('image')->store('event-images', 'public');

        $event = Event::create([
            'title' => $request->title,
            'category' => $request->category,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'description' => $request->description,
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'status' => 'approved'
        ]);

        // Notify all users except the event creator
        $users = User::where('id', '!=', auth()->id())->get();
        foreach ($users as $user) {
            $user->notify(new NewEventNotification($event));
        }

        return redirect()->route('events.create')->with('success', 'Event published successfully!');
    }

    public function manage(Request $request)
    {
        $query = Event::where('user_id', auth()->id())
            ->with('user');

        // Filter based on status
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'done':
                    $query->where(function($q) {
                        $q->where('date', '<', now()->format('Y-m-d'))
                          ->orWhere(function($q) {
                              $q->where('date', '=', now()->format('Y-m-d'))
                                ->where('time', '<', now()->format('H:i:s'));
                          });
                    });
                    break;
                case 'removed':
                    $query->where('status', 'removed');
                    break;
                case 'current':
                    $query->where('status', 'approved')
                          ->where(function($q) {
                              $q->where('date', '>', now()->format('Y-m-d'))
                                ->orWhere(function($q) {
                                    $q->where('date', '=', now()->format('Y-m-d'))
                                      ->where('time', '>', now()->format('H:i:s'));
                                });
                          });
                    break;
                default:
                    // Show all events
                    break;
            }
        }

        $events = $query->latest()->paginate(9);
        
        return view('events.manage', compact('events'));
    }

    public function edit(Event $event)
    {
        // Allow admins to edit any event, or users to edit their own events
        if (auth()->user()->role_id !== 1 && $event->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        // Allow admins to update any event, or users to update their own events
        if (auth()->user()->role_id !== 1 && $event->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image && file_exists(public_path($event->image))) {
                unlink(public_path($event->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/events'), $imageName);
            $data['image'] = 'images/events/' . $imageName;
        }

        $event->update($data);

        // Redirect based on user role
        if (auth()->user()->role_id === 1) {
            return redirect()->route('admin.events')->with('success', 'Event updated successfully!');
        }
        return redirect()->route('events.manage')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        // Allow admins to delete any event, or users to delete their own events
        if (auth()->user()->role_id !== 1 && $event->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($event->image && file_exists(public_path($event->image))) {
            unlink(public_path($event->image));
        }

        $event->delete();

        // Redirect based on user role
        if (auth()->user()->role_id === 1) {
            return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');
        }
        return redirect()->route('events.manage')->with('success', 'Event deleted successfully!');
    }

    public function show(Event $event)
    {
        // Only show approved events to non-admin users
        if (auth()->user()->role_id !== 1 && $event->status !== 'approved') {
            abort(404);
        }

        $similarEvents = Event::where('category', $event->category)
            ->where('id', '!=', $event->id)
            ->where('status', 'approved')
            ->take(3)
            ->get();

        return view('events.show', compact('event', 'similarEvents'));
    }

    public function remove(Event $event)
    {
        // Only allow admins to remove events
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        $event->update(['status' => 'removed']);
        return back()->with('success', 'Event has been removed from public view');
    }

    public function restore(Event $event)
    {
        // Only allow admins to restore events
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        $event->update(['status' => 'approved']);
        return back()->with('success', 'Event has been restored to public view');
    }
}
