<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('user')
            ->where('status', 'approved')
            ->where(function($q) {
                $q->where('date', '>', now()->format('Y-m-d'))
                  ->orWhere(function($q) {
                      $q->where('date', '=', now()->format('Y-m-d'))
                        ->where('time', '>', now()->addHours(12)->format('H:i:s'));
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
        
        $events = $query->paginate(9);
        $categories = Event::distinct()->pluck('category')->sort();
        
        return view('components.hero', compact('events', 'categories'));
    }
} 