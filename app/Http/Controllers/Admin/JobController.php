<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\Request;

/** Admin: moderate every job listing on the site. */
class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = JobListing::with('employer')
            ->withCount('applications')
            ->filter($request->only('q'))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function toggleStatus(JobListing $job)
    {
        $job->update(['status' => $job->status === 'open' ? 'closed' : 'open']);

        return back()->with('success', "\"{$job->title}\" is now {$job->status}.");
    }

    public function destroy(JobListing $job)
    {
        $job->delete();

        return back()->with('success', 'Job listing deleted.');
    }
}
