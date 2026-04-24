<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $ongoingProjects = Project::whereIn('status', ['planning', 'development', 'testing'])
        ->withCount('payments')
        ->latest()
        ->take(5)
        ->get();

    $completedProjects = Project::where('status', 'completed')->count();
    
    // Total Revenue = DP of all projects + all confirmed payments
    $totalDP = Project::sum('dp_amount');
    $totalPayments = Payment::where('status', 'confirmed')->sum('amount');
    $totalRevenue = $totalDP + $totalPayments;
    
    // Revenue last 6 months (based on payment dates and project start dates for DP)
    $monthlyRevenue = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        
        // DP received this month
        $dpInMonth = Project::whereYear('start_date', $month->year)
            ->whereMonth('start_date', $month->month)
            ->sum('dp_amount');
            
        // Payments received this month
        $paymentsInMonth = Payment::where('status', 'confirmed')
            ->whereYear('payment_date', $month->year)
            ->whereMonth('payment_date', $month->month)
            ->sum('amount');

        $monthlyRevenue[] = [
            'month' => $month->format('M'),
            'amount' => $dpInMonth + $paymentsInMonth
        ];
    }

    $topClients = Client::withCount([
        'projects',
        'projects as completed_projects_count' => function ($query) {
            $query->where('status', 'completed');
        }
    ])->orderBy('completed_projects_count', 'desc')->take(3)->get();
    
    return view('dashboard', compact('ongoingProjects', 'completedProjects', 'totalRevenue', 'topClients', 'monthlyRevenue'));
}
}