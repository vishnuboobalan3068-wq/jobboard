<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resumes are stored on the private "local" disk (storage/app/private),
 * so they can only be downloaded through this controller.
 */
class ResumeController extends Controller
{
    public function download(Request $request, Application $application)
    {
        $user = $request->user();
        $application->load('jobListing', 'user');

        $allowed = $user->isAdmin()
            || $user->id === $application->user_id                 // the applicant
            || $user->id === $application->jobListing->user_id;    // the employer who posted the job

        abort_unless($allowed, 403);
        abort_unless(Storage::disk('local')->exists($application->resume_path), 404, 'Resume file not found.');

        $extension = pathinfo($application->resume_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download(
            $application->resume_path,
            Str::slug($application->user->name).'-resume.'.$extension
        );
    }
}
