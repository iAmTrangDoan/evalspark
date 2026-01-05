<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Share classes with the student dashboard layout
        \Illuminate\Support\Facades\View::composer('layouts.student_dashboard', function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user) {
                $classes = $user->classes; // Assuming 'classes' relationship exists
                $view->with('classes', $classes);
            }
        });
    }
}
