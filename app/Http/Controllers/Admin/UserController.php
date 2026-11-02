<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Admin: manage user accounts. */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")))
            ->when($request->role, fn ($q, $role) => $q->where('role', $role))
            ->withCount(['jobListings', 'applications'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleBlock(Request $request, User $user)
    {
        $this->preventSelfChange($request, $user);

        $user->update(['is_blocked' => ! $user->is_blocked]);

        return back()->with('success', $user->name.($user->is_blocked ? ' has been blocked.' : ' has been unblocked.'));
    }

    public function updateRole(Request $request, User $user)
    {
        $this->preventSelfChange($request, $user);

        $data = $request->validate(['role' => ['required', Rule::in(User::ROLES)]]);
        $user->update($data);

        return back()->with('success', "{$user->name} is now a {$user->role}.");
    }

    public function destroy(Request $request, User $user)
    {
        $this->preventSelfChange($request, $user);

        $user->delete(); // their jobs, applications and alerts are removed by cascade

        return back()->with('success', 'User deleted.');
    }

    private function preventSelfChange(Request $request, User $user): void
    {
        abort_if($user->id === $request->user()->id, 403, 'You cannot change your own admin account here.');
    }
}
