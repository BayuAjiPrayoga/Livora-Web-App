<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    <script>
        // Handle image loading errors for property images only
        document.addEventListener('DOMContentLoaded', function() {
            // Only handle images in property cards, not all images
            const propertyImages = document.querySelectorAll('img[alt*="Kost"], img[alt*="Property"], img[src*="/storage/properties/"]');
            
            propertyImages.forEach(function(img) {
                img.addEventListener('error', function() {
                    console.error('Property image failed to load:', this.src);
                    
                    // Don't replace, just hide the img and show the placeholder that's already in the HTML
                    this.style.display = 'none';
                    
                    // If there's a next sibling div (placeholder), show it
                    if (this.nextElementSibling && this.nextElementSibling.classList.contains('bg-gray-200')) {
                        this.nextElementSibling.style.display = 'flex';
                    } else {
                        // Create a placeholder if it doesn't exist
                        const placeholder = document.createElement('div');
                        placeholder.className = 'w-full h-full bg-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300';
                        placeholder.innerHTML = `
                            <div class="text-center p-4">
                                <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-500 text-xs">Gambar tidak dapat dimuat</span>
                            </div>
                        `;
                        this.parentNode.insertBefore(placeholder, this.nextSibling);
                    }
                });
                
                img.addEventListener('load', function() {
                    console.log('✓ Image loaded:', this.src);
                });
            });
            
            // Debug storage link (silent check)
            fetch('/storage/', { method: 'HEAD' })
                .then(response => {
                    if (!response.ok) {
                        console.warn('⚠️ Storage link may not be working. Run: php artisan storage:link');
                    } else {
                        console.log('✓ Storage link is working');
                    }
                })
                .catch(error => {
                    console.warn('⚠️ Storage link check failed:', error.message);
                });
        });
    </script>
    </body>
</html>
