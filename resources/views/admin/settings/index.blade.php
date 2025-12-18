@extends('layouts.admin')

@section('title', 'System Settings - LIVORA')

@section('page-title', 'System Settings')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">System Settings</h1>
            <p class="text-sm text-gray-600 mt-1">Konfigurasi dan pengaturan sistem LIVORA</p>
        </div>
        <div class="flex space-x-3">
            <button type="submit" form="settings-form" class="bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                Save Changes
            </button>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">There were some problems with your input:</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" id="setting_type" name="setting_type" value="general">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Settings Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <nav class="space-y-2">
                        <a href="#general" class="settings-tab active block px-3 py-2 text-sm font-medium text-livora-primary bg-blue-50 rounded-md">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            General Settings
                        </a>
                        <a href="#email" class="settings-tab block px-3 py-2 text-sm font-medium text-gray-700 hover:text-livora-primary hover:bg-gray-50 rounded-md">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Configuration
                        </a>
                        <a href="#payment" class="settings-tab block px-3 py-2 text-sm font-medium text-gray-700 hover:text-livora-primary hover:bg-gray-50 rounded-md">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Payment Settings
                        </a>
                        <a href="#notifications" class="settings-tab block px-3 py-2 text-sm font-medium text-gray-700 hover:text-livora-primary hover:bg-gray-50 rounded-md">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM11 17H7l-5 5v-5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3L8 21l5-7 5 7-5-18z"></path>
                            </svg>
                            Notifications
                        </a>
                        <a href="#security" class="settings-tab block px-3 py-2 text-sm font-medium text-gray-700 hover:text-livora-primary hover:bg-gray-50 rounded-md">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Security
                        </a>
                        <a href="#maintenance" class="settings-tab block px-3 py-2 text-sm font-medium text-gray-700 hover:text-livora-primary hover:bg-gray-50 rounded-md">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Maintenance
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- General Settings -->
                <div id="general-section" class="settings-section bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900">General Settings</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan dasar aplikasi LIVORA</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                                <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] ?? 'LIVORA' }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                            </div>
                            <div>
                                <label for="app_url" class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                                <input type="url" name="app_url" id="app_url" value="{{ $settings['app_url'] ?? url('/') }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                            </div>
                        </div>

                        <div>
                            <label for="app_description" class="block text-sm font-medium text-gray-700 mb-2">Application Description</label>
                            <textarea name="app_description" id="app_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">{{ $settings['app_description'] ?? 'Platform manajemen kos modern dan terpercaya' }}</textarea>
                        </div>

                        <div>
                            <label for="app_logo" class="block text-sm font-medium text-gray-700 mb-2">Application Logo</label>
                            <div class="flex items-center space-x-4">
                                @if(isset($settings['app_logo']) && $settings['app_logo'])
                                    <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Current Logo" class="h-16 w-16 object-contain">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="app_logo" id="app_logo" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-livora-primary file:text-white hover:file:bg-blue-700">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                <select name="timezone" id="timezone" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                                    <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                    <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                    <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                </select>
                            </div>
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                <select name="currency" id="currency" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                                    <option value="IDR" {{ ($settings['currency'] ?? '') == 'IDR' ? 'selected' : '' }}>Indonesian Rupiah (IDR)</option>
                                    <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Configuration -->
                <div id="email-section" class="settings-section bg-white rounded-lg shadow-sm border border-gray-100 p-6 hidden">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Email Configuration</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan email dan notifikasi</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                                <select name="mail_driver" id="mail_driver" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                                    <option value="smtp" {{ ($settings['mail_driver'] ?? '') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="mailgun" {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ ($settings['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                </select>
                            </div>
                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                                <input type="email" name="mail_from_address" id="mail_from_address" 
                                       value="{{ $settings['mail_from_address'] ?? 'noreply@livora.com' }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                                <input type="text" name="mail_host" id="mail_host" value="{{ $settings['mail_host'] ?? '' }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                            </div>
                            <div>
                                <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                                <input type="number" name="mail_port" id="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                                <input type="text" name="mail_username" id="mail_username" value="{{ $settings['mail_username'] ?? '' }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                            </div>
                            <div>
                                <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                                <input type="password" name="mail_password" id="mail_password" value="{{ $settings['mail_password'] ?? '' }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="testEmailConnection()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                Test Email Connection
                            </button>
                            <div id="email-test-result" class="text-sm hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div id="payment-section" class="settings-section bg-white rounded-lg shadow-sm border border-gray-100 p-6 hidden">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Payment Settings</h3>
                        <p class="text-sm text-gray-500 mt-1">Konfigurasi sistem pembayaran</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="payment_auto_verify" value="1" 
                                       {{ ($settings['payment_auto_verify'] ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                <span class="ml-2 text-sm text-gray-700">Auto-verify payments under certain amount</span>
                            </label>
                        </div>

                        <div>
                            <label for="auto_verify_limit" class="block text-sm font-medium text-gray-700 mb-2">Auto Verify Limit (IDR)</label>
                            <input type="number" name="auto_verify_limit" id="auto_verify_limit" 
                                   value="{{ $settings['auto_verify_limit'] ?? 1000000 }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                        </div>

                        <div>
                            <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">Platform Commission Rate (%)</label>
                            <input type="number" step="0.01" name="commission_rate" id="commission_rate" 
                                   value="{{ $settings['commission_rate'] ?? 5.0 }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                        </div>

                        <div>
                            <label for="payment_methods" class="block text-sm font-medium text-gray-700 mb-2">Accepted Payment Methods</label>
                            <div class="space-y-2">
                                @php
                                    $paymentMethods = ['Bank Transfer', 'E-Wallet', 'Credit Card', 'Cash'];
                                    $selectedMethods = $settings['payment_methods'] ?? ['Bank Transfer'];
                                @endphp
                                @foreach($paymentMethods as $method)
                                <label class="flex items-center">
                                    <input type="checkbox" name="payment_methods[]" value="{{ $method }}" 
                                           {{ in_array($method, $selectedMethods) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                    <span class="ml-2 text-sm text-gray-700">{{ $method }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div id="notifications-section" class="settings-section bg-white rounded-lg shadow-sm border border-gray-100 p-6 hidden">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Notification Settings</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan notifikasi sistem</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Email Notifications</h4>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_new_booking" value="1" 
                                           {{ ($settings['notify_new_booking'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                    <span class="ml-2 text-sm text-gray-700">New booking notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_payment_received" value="1" 
                                           {{ ($settings['notify_payment_received'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                    <span class="ml-2 text-gray-700">Payment received notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_ticket_created" value="1" 
                                           {{ ($settings['notify_ticket_created'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                    <span class="ml-2 text-sm text-gray-700">New ticket notifications</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-4">System Notifications</h4>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_system_updates" value="1" 
                                           {{ ($settings['notify_system_updates'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                    <span class="ml-2 text-sm text-gray-700">System update notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_maintenance" value="1" 
                                           {{ ($settings['notify_maintenance'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                    <span class="ml-2 text-sm text-gray-700">Maintenance notifications</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div id="security-section" class="settings-section bg-white rounded-lg shadow-sm border border-gray-100 p-6 hidden">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Security Settings</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan keamanan sistem</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                            <input type="number" name="session_timeout" id="session_timeout" 
                                   value="{{ $settings['session_timeout'] ?? 120 }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="force_https" value="1" 
                                       {{ ($settings['force_https'] ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                <span class="ml-2 text-sm text-gray-700">Force HTTPS connections</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="two_factor_auth" value="1" 
                                       {{ ($settings['two_factor_auth'] ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                <span class="ml-2 text-sm text-gray-700">Enable two-factor authentication</span>
                            </label>
                        </div>

                        <div>
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                            <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                   value="{{ $settings['max_login_attempts'] ?? 5 }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                        </div>
                    </div>
                </div>

                <!-- Maintenance -->
                <div id="maintenance-section" class="settings-section bg-white rounded-lg shadow-sm border border-gray-100 p-6 hidden">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Maintenance Mode</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan mode maintenance</p>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800">Warning</h4>
                                    <p class="text-sm text-yellow-700 mt-1">Enabling maintenance mode will make the site unavailable to all users except admins.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="maintenance_mode" value="1" 
                                       {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                                <span class="ml-2 text-sm text-gray-700">Enable maintenance mode</span>
                            </label>
                        </div>

                        <div>
                            <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-2">Maintenance Message</label>
                            <textarea name="maintenance_message" id="maintenance_message" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">{{ $settings['maintenance_message'] ?? 'We are currently performing scheduled maintenance. Please try again later.' }}</textarea>
                        </div>

                        <div class="flex space-x-4">
                            <button type="button" onclick="clearCache()" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                                Clear Cache
                            </button>
                            <button type="button" onclick="clearLogs()" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                                Clear Logs
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active classes
                document.querySelectorAll('.settings-tab').forEach(t => {
                    t.classList.remove('active', 'text-livora-primary', 'bg-blue-50');
                    t.classList.add('text-gray-700');
                });
                
                // Add active class to clicked tab
                this.classList.add('active', 'text-livora-primary', 'bg-blue-50');
                this.classList.remove('text-gray-700');
                
                // Hide all sections
                document.querySelectorAll('.settings-section').forEach(section => {
                    section.classList.add('hidden');
                });
                
                // Show target section
                const target = this.getAttribute('href').substring(1);
                document.getElementById(target + '-section').classList.remove('hidden');
            });
        });
    });

    function testEmailConnection() {
        const button = event.target;
        const result = document.getElementById('email-test-result');
        
        button.disabled = true;
        button.textContent = 'Testing...';
        result.className = 'text-sm text-gray-600';
        result.textContent = 'Testing email connection...';
        result.classList.remove('hidden');
        
        fetch('{{ route("admin.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                result.className = 'text-sm text-green-600';
                result.textContent = 'Email connection successful!';
            } else {
                result.className = 'text-sm text-red-600';
                result.textContent = 'Email connection failed: ' + data.message;
            }
        })
        .catch(error => {
            result.className = 'text-sm text-red-600';
            result.textContent = 'Connection test failed';
        })
        .finally(() => {
            button.disabled = false;
            button.textContent = 'Test Email Connection';
        });
    }

    function clearCache() {
        if (confirm('Are you sure you want to clear the application cache?')) {
            fetch('{{ route("admin.settings.clear-cache") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cache cleared successfully!');
                } else {
                    alert('Failed to clear cache');
                }
            });
        }
    }

    function clearLogs() {
        if (confirm('Are you sure you want to clear all system logs?')) {
            fetch('{{ route("admin.settings.clear-logs") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Logs cleared successfully!');
                } else {
                    alert('Failed to clear logs');
                }
            });
        }
    }

    // Handle tab switching and form submission
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Settings page loaded');
        
        const settingTypeInput = document.getElementById('setting_type');
        const tabs = document.querySelectorAll('.settings-tab');
        const form = document.getElementById('settings-form');
        
        console.log('Elements found:', {
            settingTypeInput: !!settingTypeInput,
            tabs: tabs.length,
            form: !!form
        });
        
        // Update setting type when tab changes
        function updateSettingType() {
            const activeTab = document.querySelector('.settings-tab.active');
            let settingType = 'general'; // default
            
            if (activeTab) {
                const href = activeTab.getAttribute('href');
                console.log('Active tab href:', href);
                if (href === '#general') settingType = 'general';
                else if (href === '#email') settingType = 'email';
                else if (href === '#notifications') settingType = 'notifications';
                else if (href === '#maintenance') settingType = 'maintenance';
            }
            
            if (settingTypeInput) {
                settingTypeInput.value = settingType;
                console.log('Setting type updated to:', settingType);
            }
        }
        
        // Listen for tab clicks
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                console.log('Tab clicked:', this.getAttribute('href'));
                setTimeout(updateSettingType, 100);
            });
        });
        
        // Initial setting type update
        updateSettingType();
        
        // Add form submit handler
        if (form) {
            form.addEventListener('submit', function(e) {
                updateSettingType();
                console.log('Form submitting with:', {
                    setting_type: settingTypeInput.value,
                    action: form.action,
                    method: form.method
                });
                
                // Log all form data
                const formData = new FormData(form);
                console.log('All form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ':', value);
                }
                
                // Show alert with form data for debugging
                const alertDiv = document.createElement('div');
                alertDiv.className = 'fixed top-4 left-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded z-50 max-w-md';
                alertDiv.innerHTML = '<strong>Debug:</strong> Form submitting with setting_type: ' + settingTypeInput.value;
                document.body.appendChild(alertDiv);
                
                // Remove debug alert after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            });
        }
    });
</script>
@endpush
@endsection