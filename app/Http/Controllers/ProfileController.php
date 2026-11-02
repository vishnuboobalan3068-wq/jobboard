<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'company_name' => [Rule::requiredIf($user->isEmployer()), 'nullable', 'string', 'max:255'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Seekers can keep a default resume on their profile
        if ($user->isSeeker() && $request->hasFile('resume')) {
            $data['resume_path'] = $request->file('resume')->store('resumes', 'local');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }
        unset($data['current_password'], $data['resume']);

        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function resume(Request $request)
    {
        $user = $request->user();
        abort_unless($user->resume_path && Storage::disk('local')->exists($user->resume_path), 404);

        return Storage::disk('local')->download($user->resume_path);
    }
}
