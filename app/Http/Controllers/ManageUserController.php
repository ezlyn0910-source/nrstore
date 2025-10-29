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
        return redirect()->route('manageuser.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not needed for user management from admin side
        return redirect()->route('manageuser.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $manageuser)
    {
        return view('manageuser.show', compact('manageuser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $manageuser)
    {
        return view('manageuser.edit', compact('manageuser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $manageuser)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $manageuser->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspended',
        ]);

        $manageuser->update($validated);

        return redirect()->route('manageuser.show', $manageuser)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $manageuser)
    {
        // Not implementing delete for now, use suspend instead
        return redirect()->route('manageuser.index');
    }

    /**
     * Suspend user
     */
    public function suspend(User $manageuser)
    {
        $manageuser->update(['status' => 'suspended']);
        return back()->with('success', 'User has been suspended.');
    }

    /**
     * Activate user
     */
    public function activate(User $manageuser)
    {
        $manageuser->update(['status' => 'active']);
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