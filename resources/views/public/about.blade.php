@extends('layouts.public')

@section('title', 'Tentang Kami - LIVORA')

@section('content')
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #ff6900 0%, #ff8533 50%, #ffb366 100%); color: white; padding: 5rem 0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Tentang LIVORA</h1>
        <p class="text-xl text-orange-100 max-w-3xl mx-auto">
            Platform terpercaya untuk menemukan hunian impian Anda
        </p>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-6">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Visi Kami</h2>
                <p class="text-gray-700 leading-relaxed">
                    Menjadi platform nomor satu di Indonesia yang menghubungkan pencari hunian dengan pemilik kost dan boarding house terbaik, menciptakan pengalaman pencarian hunian yang mudah, aman, dan terpercaya.
                </p>
            </div>
            
            <div>
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-6">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Misi Kami</h2>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-livora-accent mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Menyediakan platform yang user-friendly dan mudah diakses
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-livora-accent mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Memverifikasi setiap properti untuk keamanan pengguna
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-livora-accent mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Memberikan transparansi harga dan informasi lengkap
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-livora-accent mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Memfasilitasi proses booking yang cepat dan aman
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cerita Kami</h2>
            <div class="w-20 h-1 bg-orange-500 mx-auto"></div>
        </div>
        
        <div class="prose prose-lg max-w-none text-gray-700">
            <p class="mb-6">
                LIVORA lahir dari pengalaman pribadi para founder yang merasakan kesulitan dalam mencari hunian yang tepat saat menempuh pendidikan dan bekerja di kota besar. Proses pencarian yang memakan waktu, informasi yang tidak lengkap, dan ketidakpastian kondisi properti menjadi tantangan yang harus dihadapi.
            </p>
            
            <p class="mb-6">
                Kami percaya bahwa mencari tempat tinggal seharusnya menjadi pengalaman yang menyenangkan, bukan membingungkan. Oleh karena itu, kami menciptakan LIVORA - sebuah platform yang memudahkan pencari hunian untuk menemukan kost dan boarding house yang sesuai dengan kebutuhan mereka, dengan informasi yang lengkap dan proses booking yang transparan.
            </p>
            
            <p>
                Saat ini, LIVORA telah berkembang menjadi platform terpercaya yang menghubungkan ribuan pencari hunian dengan pemilik properti di berbagai kota di Indonesia. Kami terus berinovasi dan berkomitmen untuk memberikan pelayanan terbaik bagi semua pengguna kami.
            </p>
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Nilai-Nilai Kami</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Prinsip yang menjadi fondasi setiap keputusan dan tindakan kami
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Kepercayaan</h3>
                <p class="text-gray-600">Kami membangun kepercayaan melalui transparansi dan verifikasi setiap properti</p>
            </div>
            
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Inovasi</h3>
                <p class="text-gray-600">Terus berinovasi untuk memberikan pengalaman terbaik bagi pengguna</p>
            </div>
            
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Kolaborasi</h3>
                <p class="text-gray-600">Membangun ekosistem yang saling menguntungkan bagi semua pihak</p>
            </div>
            
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Kecepatan</h3>
                <p class="text-gray-600">Proses cepat dan efisien dalam setiap layanan kami</p>
            </div>
            
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Kepedulian</h3>
                <p class="text-gray-600">Peduli terhadap kebutuhan dan kepuasan setiap pengguna</p>
            </div>
            
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Integritas</h3>
                <p class="text-gray-600">Berkomitmen pada kejujuran dan transparansi dalam setiap aspek</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="padding: 4rem 0; background: linear-gradient(135deg, #ff6900 0%, #ff8533 50%, #ffb366 100%); color: white;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Bergabunglah dengan Kami
        </h2>
        <p class="text-xl mb-8 text-orange-100">
            Temukan hunian impian Anda atau daftarkan properti Anda bersama LIVORA
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="inline-flex items-center justify-center px-8 py-3 bg-white text-orange-600 font-bold rounded-lg transition-colors duration-200 hover:bg-orange-50">
                Daftar Sekarang
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center justify-center px-8 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold rounded-lg border-2 border-white transition-colors duration-200">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>
@endsection
