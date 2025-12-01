<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10);

        // Get stats for the dashboard section
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'suspended_users' => User::where('status', 'suspended')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        $recent_users = User::latest()->take(5)->get();

        return view('manageuser.index', compact('users', 'stats', 'recent_users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not needed for user management from admin side
        return redirect()->route('admin.manageuser.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not needed for user management from admin side
        return redirect()->route('admin.manageuser.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)  // Changed from $manageuser to $user
    {
        return view('manageuser.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)  // Changed from $manageuser to $user
    {
        return view('manageuser.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)  // Changed from $manageuser to $user
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspended',
        ]);

        $user->update($validated);

        return redirect()->route('admin.manageuser.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)  // Changed from $manageuser to $user
    {
        // Not implementing delete for now, use suspend instead
        return redirect()->route('admin.manageuser.index');
    }

    /**
     * Suspend user
     */
    public function suspend(User $user)  // Changed from $manageuser to $user
    {
        $user->update(['status' => 'suspended']);
        return back()->with('success', 'User has been suspended.');
    }

    /**
     * Activate user
     */
    public function activate(User $user)  // Changed from $manageuser to $user
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User has been activated.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $userIds = $request->user_ids;

        if (!$userIds) {
            return back()->with('error', 'No users selected.');
        }

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => 'active']);
                $message = 'Selected users have been activated.';
                break;
            case 'suspend':
                User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                $message = 'Selected users have been suspended.';
                break;
            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
    }
}