<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Employer: see applicants of a job and update their status. */
class ApplicationController extends Controller
{
    public function index(Request $request, JobListing $job)
    {
        abort_unless($job->user_id === $request->user()->id, 403);

        $applications = $job->applications()->with('user')->latest()->get();

        return view('employer.applications.index', compact('job', 'applications'));
    }

    public function update(Request $request, Application $application)
    {
        abort_unless($application->jobListing->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(Application::STATUSES)],
        ]);

        if ($application->status !== $data['status']) {
            $application->update($data);
            $application->user->notify(new ApplicationStatusChanged($application));
        }

        return back()->with('success', 'Application status updated.');
    }
}
