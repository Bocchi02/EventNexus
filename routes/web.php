<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\OrganizerController;

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
    Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.toggleStatus');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});

// Organizer Routes
Route::middleware(['auth', RoleMiddleware::class.':organizer'])->group(function () {
    Route::get('/organizer/dashboard', [OrganizerController::class, 'dashboard'])->name('organizer.dashboard');
});



require __DIR__.'/auth.php';
