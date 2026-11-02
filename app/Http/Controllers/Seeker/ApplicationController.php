<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use App\Notifications\NewApplicationReceived;
use Illuminate\Http\Request;

/** Job seeker: apply for jobs (with resume upload) and track applications. */
class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = $request->user()->applications()
            ->with('jobListing')
            ->latest()
            ->paginate(10);

        return view('seeker.applications.index', compact('applications'));
    }

    public function store(Request $request, JobListing $job)
    {
        $user = $request->user();

        if (! $job->isOpen()) {
            return back()->with('error', 'This job is no longer accepting applications.');
        }
        if ($user->hasAppliedTo($job)) {
            return back()->with('error', 'You have already applied for this job.');
        }

        $request->validate([
            // Resume is optional only if the seeker already saved one on their profile
            'resume' => [$user->resume_path ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ]);

        $resumePath = $request->hasFile('resume')
            ? $request->file('resume')->store('resumes', 'local')
            : $user->resume_path;

        $application = $user->applications()->create([
            'job_listing_id' => $job->id,
            'resume_path' => $resumePath,
            'cover_letter' => $request->input('cover_letter'),
        ]);

        $job->employer?->notify(new NewApplicationReceived($application));

        return redirect()->route('seeker.applications.index')
            ->with('success', 'Application sent for '.$job->title.'. Good luck!');
    }
}
