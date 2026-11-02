<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobAlert;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Job seeker: manage job alerts (get notified when a matching job is posted). */
class AlertController extends Controller
{
    public function index(Request $request)
    {
        return view('seeker.alerts.index', [
            'alerts' => $request->user()->jobAlerts()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'keyword' => ['nullable', 'string', 'max:100', 'required_without_all:location,category,job_type'],
            'location' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', Rule::in(JobListing::CATEGORIES)],
            'job_type' => ['nullable', Rule::in(JobListing::TYPES)],
        ], [
            'keyword.required_without_all' => 'Fill in at least one field for your alert.',
        ]);

        $request->user()->jobAlerts()->create($data);

        return back()->with('success', 'Job alert created. We will notify you when a matching job is posted.');
    }

    public function toggle(Request $request, JobAlert $alert)
    {
        abort_unless($alert->user_id === $request->user()->id, 403);

        $alert->update(['is_active' => ! $alert->is_active]);

        return back()->with('success', $alert->is_active ? 'Alert turned on.' : 'Alert paused.');
    }

    public function destroy(Request $request, JobAlert $alert)
    {
        abort_unless($alert->user_id === $request->user()->id, 403);

        $alert->delete();

        return back()->with('success', 'Alert deleted.');
    }
}
