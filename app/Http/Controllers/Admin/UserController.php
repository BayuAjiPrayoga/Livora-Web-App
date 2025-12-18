<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', (bool) $request->status);
        }

        // Sort by created_at desc by default
        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(15)->withQueryString();

        // Get statistics for the view
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'owners' => User::where('role', 'owner')->count(),
            'tenants' => User::where('role', 'tenant')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,owner,tenant',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'email_verified_at' => now(), // Auto-verify admin created users
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        User::create($userData);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        // Load relationships for detailed view with select to avoid errors
        $user->load([
            'boardingHouses',
            'bookings' => function ($query) {
                $query->with(['room' => function ($q) {
                    $q->with('boardingHouse:id,name');
                }]);
            },
            'payments',
            'tickets'
        ]);

        // Get user statistics
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'active_bookings' => $user->bookings()->where('status', 'active')->count(),
            'total_payments' => $user->payments()->sum('amount'),
            'total_tickets' => $user->tickets()->count(),
            'open_tickets' => $user->tickets()->where('status', 'open')->count(),
        ];

        if ($user->role === 'mitra') {
            $stats['total_properties'] = $user->boardingHouses()->count();
            $stats['total_rooms'] = $user->boardingHouses()->withCount('rooms')->get()->sum('rooms_count');
            $stats['occupied_rooms'] = $user->boardingHouses()
                ->with(['rooms' => function ($query) {
                    $query->where('is_occupied', true);
                }])->get()->sum(function ($boardingHouse) {
                    return $boardingHouse->rooms->count();
                });
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,mitra,tenant',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active'),
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting own account or other admins
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($user->role === 'admin' && Auth::user()->role !== 'super_admin') {
            return back()->with('error', 'Tidak dapat menghapus admin lain.');
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dihapus.');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return back()->with('success', 'User berhasil diaktifkan.');
    }

    public function deactivate(User $user)
    {
        // Prevent deactivating own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => false]);

        return back()->with('success', 'User berhasil dinonaktifkan.');
    }

    public function bulkActivate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        // Exclude own account from bulk operations
        $userIds = array_filter($request->user_ids, function ($id) {
            return $id != Auth::id();
        });

        User::whereIn('id', $userIds)->update(['is_active' => true]);

        return response()->json(['message' => 'Users berhasil diaktifkan.']);
    }

    public function bulkDeactivate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        // Exclude own account from bulk operations
        $userIds = array_filter($request->user_ids, function ($id) {
            return $id != Auth::id();
        });

        User::whereIn('id', $userIds)->update(['is_active' => false]);

        return response()->json(['message' => 'Users berhasil dinonaktifkan.']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        // Exclude own account and other admins from bulk delete
        $userIds = User::whereIn('id', $request->user_ids)
                      ->where('id', '!=', Auth::id())
                      ->where('role', '!=', 'admin')
                      ->pluck('id')
                      ->toArray();

        // Delete avatars
        $users = User::whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        User::whereIn('id', $userIds)->delete();

        return response()->json(['message' => 'Users berhasil dihapus.']);
    }

    public function export(Request $request)
    {
        $query = User::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', (bool) $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $csvData = [];
        $csvData[] = ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Created At', 'Last Login'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->phone,
                $user->role,
                $user->is_active ? 'Active' : 'Inactive',
                $user->created_at->format('Y-m-d H:i:s'),
                $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
            ];
        }

        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $handle = fopen('php://temp', 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}