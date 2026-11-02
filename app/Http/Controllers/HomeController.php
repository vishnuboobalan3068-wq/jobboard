<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'latestJobs' => JobListing::visible()->latest()->take(6)->get(),
            'categoryCounts' => JobListing::visible()
                ->selectRaw('category, count(*) as total')
                ->groupBy('category')
                ->pluck('total', 'category'),
            'stats' => [
                'jobs' => JobListing::visible()->count(),
                'companies' => User::where('role', 'employer')->count(),
                'seekers' => User::where('role', 'seeker')->count(),
            ],
        ]);
    }

    /** Sends each role to its own home page after login. */
    public function dashboard(Request $request)
    {
        return match ($request->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'employer' => redirect()->route('employer.jobs.index'),
            default => redirect()->route('jobs.index'),
        };
    }
}
