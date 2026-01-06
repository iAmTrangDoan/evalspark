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
            if ($user && $user->role === 'student') {
                 $classes = $user->classes()->orderBy('created_at', 'desc')->get();
                 $view->with('classes', $classes);
            }
        });

        // Share teaching classes with the lecturer dashboard layout (SORTED)
        \Illuminate\Support\Facades\View::composer('layouts.lecturer_dashboard', function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && $user->role === 'lecturer') {
                // Ensure consistent sorting (e.g., by name or creation date) AND only ACTIVE classes
                $teaching_classes = $user->teachingClasses()
                                         ->where('status', 'active')
                                         ->orderBy('name', 'asc')
                                         ->get();
                $view->with('teaching_classes', $teaching_classes);
            }
        });
    }
}
