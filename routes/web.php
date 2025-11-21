<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientInvitationController;
use App\Http\Controllers\InviteGuestController;
use App\Http\Middleware\ClientMiddleware; //Meron middleware para mafortify yung access meaning client lang makakaaccess --call filbert for full details

use Spatie\Permission\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardRedirectController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', RoleMiddleware::class.':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.storeUser');
    Route::post('/admin/users/{id}/assign-role', [AdminController::class, 'assignRole'])->name('admin.assignRole');
    Route::post('/admin/users/toggle-status/{id}', [AdminController::class, 'toggleStatus'])->name('admin.toggleStatus');
    Route::get('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/admin/getUsers', [AdminController::class, 'getUsers'])->name('users.data'); //get user data for datatable
    Route::post('/admin/users/edit/{id}', [AdminController::class, 'editUser'])->name('admin.editUser');
});

// Organizer Routes
Route::middleware(['auth', RoleMiddleware::class.':organizer'])->group(function () {
    Route::get('/organizer/dashboard', [OrganizerController::class, 'dashboard'])->name('organizer.dashboard');
    Route::get('/organizer/events', [OrganizerController::class, 'events'])->name('organizer.events');
    Route::get('/organizer/getEvents', [OrganizerController::class, 'getEvents'])->name('events.data');
    Route::post('/organizer/events/store', [OrganizerController::class, 'storeEvent'])->name('organizer.storeEvent');
    Route::post('/organizer/events/{id}/update', [OrganizerController::class, 'updateEvent'])->name('organizer.updateEvent');
    Route::get('/organizer/event/{id}', [OrganizerController::class, 'show'])->name('organizer.showEvent');
    Route::get('/organizer/events/{id}/edit', [OrganizerController::class, 'editEvent'])->name('organizer.editEvent');
    Route::delete('/organizer/events/{id}', [OrganizerController::class, 'deleteEvent'])->name('organizer.deleteEvent');
    Route::get('/organizer/searchClient', [OrganizerController::class, 'searchClient'])->name('organizer.searchClient');
    Route::get('/organizer/events/{id}', [OrganizerController::class, 'show']);
    Route::post('/organizer/events/{id}/cancel', [OrganizerController::class, 'cancelEvent'])->name('organizer.cancelEvent');
});

// Client Routes
Route::middleware(['auth', RoleMiddleware::class . ':client'])->group(function () {
        Route::get('/client/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');
        Route::get('/client/events', [ClientController::class, 'showEvent'])->name('client.events');
        Route::get('/client/getEvents', [ClientController::class, 'getEvents'])->name('client.events.data');
        Route::get('/client/events/{id}', [ClientController::class, 'show']);

        // Guest management per event
        Route::get('/client/events/{eventId}/guests', [ClientInvitationController::class, 'index']);
        Route::get('/client/events/{eventId}/guests/list', [ClientInvitationController::class, 'list']);

        // Invite guest
        Route::post('/client/events/{eventId}/invite', [InviteGuestController::class, 'inviteGuest'])->name('client.events.inviteGuest');

        // Update status
        Route::post('/client/events/{eventId}/guest/{guestId}/status', [ClientInvitationController::class, 'updateStatus']);

        // Remove guest
        Route::delete('/client/events/{eventId}/guest/{guestId}', [ClientInvitationController::class, 'remove']);

         // Send invitation
       // Route::post('/client/events/{event}/invite', [InviteGuestController::class, 'sendInvite'])->name('client.invite.guest');


    });

    // Public — guest accepts invitation
Route::get('/invitation/accept/{token}', [InviteGuestController::class, 'acceptInvite'])->name('invitation.accept');
require __DIR__.'/auth.php';
