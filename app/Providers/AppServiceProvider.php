<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin-only', function (User $user) {
        return $user->role === 'admin';
        });

        if (Schema::hasTable('events')) {
        $now = now();

        Event::where('status', 'upcoming')
            ->where('start_date', '<=', $now)
            ->update(['status' => 'ongoing']);

        Event::where('status', 'ongoing')
            ->where('end_date', '<=', $now)
            ->update(['status' => 'completed']);
        }
    }
}
