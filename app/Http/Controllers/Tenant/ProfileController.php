<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function show()
    {
        return view('tenant.profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Validate current password if new password is provided
        if ($request->filled('password') && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        // Update user data
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show notification settings.
     */
    public function notifications()
    {
        return view('tenant.notifications', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'marketing_notifications' => ['boolean'],
        ]);

        $user->update([
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
            'marketing_notifications' => $request->has('marketing_notifications'),
        ]);

        return back()->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }

    /**
     * Show account security settings.
     */
    public function security()
    {
        return view('tenant.security', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Deactivate user account.
     */
    public function deactivate(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'reason' => ['nullable', 'string', 'max:500']
        ]);

        $user = Auth::user();
        
        // Mark account as deactivated
        $user->update([
            'is_active' => false,
            'deactivated_at' => now(),
            'deactivation_reason' => $request->reason
        ]);

        // Logout user
        Auth::logout();

        return redirect('/')->with('success', 'Akun Anda telah dinonaktifkan.');
    }

    /**
     * Delete user account permanently.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'in:DELETE']
        ]);

        $user = Auth::user();
        
        // Logout user before deletion
        Auth::logout();
        
        // Delete user account
        $user->delete();

        return redirect('/')->with('success', 'Akun Anda telah dihapus permanen.');
    }
}