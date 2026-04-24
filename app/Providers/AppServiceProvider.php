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
        view()->composer('layouts.app', function ($view) {
            $recentPayments = \App\Models\Payment::with('project')
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($payment) {
                    return [
                        'type' => 'payment',
                        'title' => 'Pembayaran Baru',
                        'message' => 'Rp ' . number_format($payment->amount, 0, ',', '.') . ' dari ' . $payment->project->name,
                        'url' => route('projects.show', $payment->project_id),
                        'time' => $payment->created_at->diffForHumans(),
                        'icon' => 'fas fa-money-bill-wave text-success'
                    ];
                });

            $upcomingDeadlines = \App\Models\Project::where('status', '!=', 'completed')
                ->where('end_date', '<=', now()->addDays(7))
                ->where('end_date', '>=', now())
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($project) {
                    return [
                        'type' => 'deadline',
                        'title' => 'Deadline Mendekati',
                        'message' => $project->name . ' berakhir dalam ' . now()->diffInDays($project->end_date) . ' hari',
                        'url' => route('projects.show', $project->id),
                        'time' => $project->end_date->diffForHumans(),
                        'icon' => 'fas fa-clock text-warning'
                    ];
                });

            $notifications = $recentPayments->concat($upcomingDeadlines)->sortByDesc('time')->take(5);
            $view->with('notifications', $notifications);
        });
    }
}
