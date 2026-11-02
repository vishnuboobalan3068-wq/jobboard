<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobAlert;
use App\Models\JobListing;
use App\Notifications\NewJobMatchesAlert;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Employer: post, edit, close and delete their own jobs. */
class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = $request->user()->jobListings()
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('employer.jobs.index', compact('jobs'));
    }

    public function create(Request $request)
    {
        $job = new JobListing(['company_name' => $request->user()->company_name, 'status' => 'open']);

        return view('employer.jobs.create', compact('job'));
    }

    public function store(Request $request)
    {
        $data = $this->validateJob($request);
        $data['status'] = $data['status'] ?? 'open';

        $job = $request->user()->jobListings()->create($data);

        $sent = $this->notifyAlertSubscribers($job);

        return redirect()->route('employer.jobs.index')
            ->with('success', "Job posted! {$sent} job seeker(s) were notified through job alerts.");
    }

    public function edit(Request $request, JobListing $job)
    {
        $this->authorizeOwner($request, $job);

        return view('employer.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobListing $job)
    {
        $this->authorizeOwner($request, $job);

        $job->update($this->validateJob($request, updating: true));

        return redirect()->route('employer.jobs.index')->with('success', 'Job updated.');
    }

    public function destroy(Request $request, JobListing $job)
    {
        $this->authorizeOwner($request, $job);

        $job->delete();

        return redirect()->route('employer.jobs.index')->with('success', 'Job deleted.');
    }

    private function validateJob(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(JobListing::CATEGORIES)],
            'job_type' => ['required', Rule::in(JobListing::TYPES)],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
            'description' => ['required', 'string', 'min:30'],
            'requirements' => ['nullable', 'string'],
            'deadline' => array_filter(['nullable', 'date', $updating ? null : 'after_or_equal:today']),
            'status' => [$updating ? 'required' : 'nullable', 'in:open,closed'],
        ]);
    }

    private function authorizeOwner(Request $request, JobListing $job): void
    {
        abort_unless($job->user_id === $request->user()->id, 403);
    }

    /** Job alerts: notify every seeker whose active alert matches this new job. */
    private function notifyAlertSubscribers(JobListing $job): int
    {
        $users = JobAlert::where('is_active', true)
            ->with('user')
            ->get()
            ->filter(fn (JobAlert $alert) => $alert->user && ! $alert->user->is_blocked && $alert->matches($job))
            ->pluck('user')
            ->unique('id');

        foreach ($users as $user) {
            try {
                $user->notify(new NewJobMatchesAlert($job));
            } catch (\Throwable $e) {
                report($e); // a mail problem should never stop the job from being posted
            }
        }

        return $users->count();
    }
}
