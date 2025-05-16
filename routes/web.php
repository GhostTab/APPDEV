<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizerApplicationController;
use App\Http\Controllers\OrganizerController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/past', [EventController::class, 'pastEvents'])->name('events.past');

Route::middleware(['admin'])->group(function () {
    Route::get('/test-admin', function () {
        dd([
            'user' => auth()->user(),
            'role_id' => auth()->user()->role_id ?? 'no role',
            'is_authenticated' => auth()->check(),
        ]);
    });
});

Route::get('/test-user', function () {
    return 'User route works!';
})->middleware(['auth']);

Route::get('/test-middleware', function () {
    return 'Testing middleware registration';
})->middleware(['test']);

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.update-image');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password'); // Add this line
});

// Organizer Application Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/organizer/apply', [OrganizerController::class, 'showApplicationForm'])->name('organizer.apply');
    Route::post('/organizer/apply', [OrganizerController::class, 'submitApplication'])->name('organizer.apply.submit');
});

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized access.');
        }
        return app()->call([app(AdminController::class), 'dashboard']);
    })->name('admin.dashboard');

    Route::get('/admin/organizer-applications', function () {
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized access.');
        }
        return app()->call([app(OrganizerApplicationController::class), 'adminIndex']);
    })->name('admin.organizer-applications');

    Route::put('/admin/organizer-applications/{application}', function ($application) {
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized access.');
        }
        return app()->call([app(OrganizerApplicationController::class), 'updateStatus'], ['application' => $application]);
    })->name('admin.organizer-applications.update');

    Route::get('/admin/organizers', function () {
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized access.');
        }
        return app()->call([app(AdminController::class), 'organizers']);
    })->name('admin.organizers');

    Route::put('/admin/organizers/{user}/demote', [AdminController::class, 'demoteOrganizer'])
        ->middleware('auth')
        ->where('user', '[0-9]+')
        ->name('admin.organizers.demote');

    Route::get('/admin/events', function () {
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Unauthorized access.');
        }
        return app()->call([app(AdminController::class), 'events']);
    })->name('admin.events');

    // Inside the auth middleware group
    Route::post('/notifications/mark-as-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markAsRead');

    // Add these routes in the admin group
    Route::patch('/admin/events/{event}/remove', [EventController::class, 'remove'])->name('admin.events.remove');
    Route::patch('/admin/events/{event}/restore', [EventController::class, 'restore'])->name('admin.events.restore');
});

// Event Creation Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/manage', [EventController::class, 'manage'])->name('events.manage');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

