<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

/** Public job search + job detail page. */
class JobController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'location', 'category', 'type']);

        $jobs = JobListing::visible()
            ->filter($filters)
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('jobs.index', compact('jobs', 'filters'));
    }

    public function show(Request $request, JobListing $job)
    {
        $user = $request->user();

        // Closed jobs are still viewable by their owner and admins
        if ($job->status !== 'open' && ! ($user && ($user->isAdmin() || $user->id === $job->user_id))) {
            abort(404);
        }

        return view('jobs.show', [
            'job' => $job->load('employer'),
            'alreadyApplied' => $user?->isSeeker() && $user->hasAppliedTo($job),
        ]);
    }
}
