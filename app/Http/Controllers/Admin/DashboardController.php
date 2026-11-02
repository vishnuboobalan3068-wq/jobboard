<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'employers' => User::where('role', 'employer')->count(),
                'seekers' => User::where('role', 'seeker')->count(),
                'openJobs' => JobListing::where('status', 'open')->count(),
                'closedJobs' => JobListing::where('status', 'closed')->count(),
                'applications' => Application::count(),
                'blocked' => User::where('is_blocked', true)->count(),
            ],
            'applicationsByStatus' => Application::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'recentJobs' => JobListing::with('employer')->withCount('applications')->latest()->take(5)->get(),
            'recentUsers' => User::latest()->take(5)->get(),
        ]);
    }
}
