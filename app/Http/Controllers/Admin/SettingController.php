<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            // General Settings
            'app_name' => cache('app.name', config('app.name', 'LIVORA')),
            'app_url' => cache('app.url', config('app.url', url('/'))),
            'app_description' => cache('app.description', 'Platform manajemen kos modern dan terpercaya'),
            'app_logo' => cache('app.logo'),
            'timezone' => cache('app.timezone', config('app.timezone', 'Asia/Jakarta')),
            'currency' => cache('app.currency', 'IDR'),
            
            // Payment Settings
            'commission_rate' => cache('payment.commission_rate', 5.0),
            'auto_verify_limit' => cache('payment.auto_verify_limit', 1000000),
            'payment_auto_verify' => cache('payment.auto_verify', false),
            'payment_methods' => cache('payment.methods', ['transfer_bank', 'e_wallet', 'virtual_account']),
            
            // Email Settings
            'mail_driver' => cache('mail.driver', config('mail.default', 'smtp')),
            'mail_from_address' => cache('mail.from_address', config('mail.from.address', 'noreply@livora.com')),
            'mail_host' => cache('mail.host', config('mail.mailers.smtp.host', 'smtp.gmail.com')),
            'mail_port' => cache('mail.port', config('mail.mailers.smtp.port', 587)),
            'mail_username' => cache('mail.username', config('mail.mailers.smtp.username')),
            'mail_password' => cache('mail.password'),
            
            // Notification Settings
            'notify_new_booking' => cache('notifications.new_booking', true),
            'notify_payment_received' => cache('notifications.payment_received', true),
            'notify_ticket_created' => cache('notifications.ticket_created', true),
            'notify_system_updates' => cache('notifications.system_updates', false),
            
            // Security Settings
            'session_timeout' => cache('security.session_timeout', 120),
            'max_login_attempts' => cache('security.max_login_attempts', 5),
            
            // Maintenance Mode
            'maintenance_mode' => cache('maintenance.enabled', false),
            'maintenance_message' => cache('maintenance.message', 'Website sedang dalam pemeliharaan. Silakan coba lagi nanti.'),
            'maintenance_allowed_ips' => cache('maintenance.allowed_ips', []),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        // Debug: Log the general settings data
        \Log::info('Updating general settings:', $request->only([
            'app_name', 'app_url', 'app_description', 'timezone', 'currency',
            'commission_rate', 'auto_verify_limit', 'payment_auto_verify', 'payment_methods'
        ]));

        $request->validate([
            'app_name' => 'required|string|max:100',
            'app_url' => 'nullable|url',
            'app_description' => 'required|string|max:500',
            'app_logo' => 'nullable|image|max:2048',
            'timezone' => 'required|string',
            'currency' => 'required|string',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'auto_verify_limit' => 'required|numeric|min:0',
            'payment_auto_verify' => 'nullable|boolean',
            'payment_methods' => 'nullable|array',
        ]);

        // Update app settings
        Cache::forever('app.name', $request->app_name);
        Cache::forever('app.url', $request->app_url ?: url('/'));
        Cache::forever('app.description', $request->app_description);
        Cache::forever('app.timezone', $request->timezone);
        Cache::forever('app.currency', $request->currency);
        
        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $logoPath = $request->file('app_logo')->store('settings', 'public');
            Cache::forever('app.logo', $logoPath);
        }
        
        // Update payment settings
        Cache::forever('payment.commission_rate', $request->commission_rate);
        Cache::forever('payment.auto_verify_limit', $request->auto_verify_limit);
        Cache::forever('payment.auto_verify', $request->boolean('payment_auto_verify'));
        Cache::forever('payment.methods', $request->payment_methods ?: []);

        \Log::info('General settings updated successfully');

        return back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        \Log::info('Updating email settings:', $request->only([
            'mail_driver', 'mail_from_address', 'mail_host', 'mail_port', 'mail_username', 'mail_password'
        ]));

        $request->validate([
            'mail_driver' => 'required|string|in:smtp,mailgun,ses',
            'mail_from_address' => 'required|email',
            'mail_host' => 'required|string|max:100',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
        ]);

        // Update email configuration in cache
        Cache::forever('mail.driver', $request->mail_driver);
        Cache::forever('mail.from_address', $request->mail_from_address);
        Cache::forever('mail.host', $request->mail_host);
        Cache::forever('mail.port', $request->mail_port);
        Cache::forever('mail.username', $request->mail_username);
        
        if ($request->filled('mail_password')) {
            Cache::forever('mail.password', $request->mail_password);
        }

        \Log::info('Email settings updated successfully');

        return back()->with('success', 'Email settings updated successfully.');
    }

    /**
     * Update notification settings.
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notify_new_booking' => 'boolean',
            'notify_payment_received' => 'boolean',
            'notify_property_verification' => 'boolean',
            'notify_ticket_created' => 'boolean',
        ]);

        // Update notification settings
        Cache::forever('notifications.new_booking', $request->boolean('notify_new_booking'));
        Cache::forever('notifications.payment_received', $request->boolean('notify_payment_received'));
        Cache::forever('notifications.property_verification', $request->boolean('notify_property_verification'));
        Cache::forever('notifications.ticket_created', $request->boolean('notify_ticket_created'));

        return back()->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Update maintenance mode settings.
     */
    public function updateMaintenance(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'required|string|max:1000',
            'maintenance_allowed_ips' => 'nullable|string',
        ]);

        $allowedIps = [];
        if ($request->filled('maintenance_allowed_ips')) {
            $allowedIps = array_map('trim', explode(',', $request->maintenance_allowed_ips));
        }

        // Update maintenance settings
        Cache::forever('maintenance.enabled', $request->boolean('maintenance_mode'));
        Cache::forever('maintenance.message', $request->maintenance_message);
        Cache::forever('maintenance.allowed_ips', $allowedIps);

        $status = $request->boolean('maintenance_mode') ? 'enabled' : 'disabled';
        
        return back()->with('success', "Maintenance mode {$status} successfully.");
    }

    /**
     * Update all settings.
     */
    public function update(Request $request)
    {
        try {
            // Debug: Log the request data
            \Log::info('Settings update request received:', [
                'all_data' => $request->all(),
                'setting_type' => $request->get('setting_type'),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user' => Auth::id() ?? 'guest'
            ]);

            // Validate setting type
            $validated = $request->validate([
                'setting_type' => 'required|string|in:general,email,notifications,maintenance',
            ]);

            \Log::info('Validation passed, routing to specific update method');

            switch ($request->setting_type) {
                case 'general':
                    $result = $this->updateGeneral($request);
                    break;
                case 'email':
                    $result = $this->updateEmail($request);
                    break;
                case 'notifications':
                    $result = $this->updateNotifications($request);
                    break;
                case 'maintenance':
                    $result = $this->updateMaintenance($request);
                    break;
                default:
                    $result = back()->withErrors(['setting_type' => 'Invalid setting type provided.']);
            }

            \Log::info('Settings update completed successfully');
            return $result;
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Settings validation failed:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Settings update failed:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return back()->with('error', 'Failed to update settings: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        // Clear various caches
        Cache::flush();
        
        // Clear config cache
        \Artisan::call('config:clear');
        
        // Clear route cache
        \Artisan::call('route:clear');
        
        // Clear view cache
        \Artisan::call('view:clear');

        return back()->with('success', 'Application cache cleared successfully.');
    }

    /**
     * Test email configuration.
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Send test email
            \Mail::raw('This is a test email from LIVORA admin panel.', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('LIVORA - Test Email');
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Clear application logs.
     */
    public function clearLogs()
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            
            if (file_exists($logPath)) {
                file_put_contents($logPath, '');
            }

            // Also clear other log files
            $logFiles = glob(storage_path('logs/*.log'));
            foreach ($logFiles as $file) {
                file_put_contents($file, '');
            }

            return response()->json(['success' => true, 'message' => 'Logs cleared successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear logs: ' . $e->getMessage()]);
        }
    }
}