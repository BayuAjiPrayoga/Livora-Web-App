@extends('layouts.public')

@section('title', 'Hubungi Kami - LIVORA')

@section('content')
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #ff6900 0%, #ff8533 50%, #ffb366 100%); color: white; padding: 5rem 0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Hubungi Kami</h1>
        <p class="text-xl text-orange-100 max-w-3xl mx-auto">
            Kami siap membantu Anda. Jangan ragu untuk menghubungi kami
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Informasi Kontak</h2>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Silakan hubungi kami melalui salah satu cara di bawah ini. Tim kami akan merespons pertanyaan Anda sesegera mungkin.
                </p>
                
                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Alamat Kantor</h3>
                            <p class="text-gray-600">
                                Jl. Sudirman No. 123<br>
                                Jakarta Pusat 10220<br>
                                Indonesia
                            </p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Email</h3>
                            <p class="text-gray-600">
                                <a href="mailto:info@livora.com" class="text-orange-600 hover:text-orange-700">info@livora.com</a><br>
                                <a href="mailto:support@livora.com" class="text-orange-600 hover:text-orange-700">support@livora.com</a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Telepon</h3>
                            <p class="text-gray-600">
                                <a href="tel:+622112345678" class="text-orange-600 hover:text-orange-700">+62 21 1234 5678</a><br>
                                <a href="https://wa.me/6281234567890" target="_blank" class="text-green-600 hover:text-green-700">
                                    +62 812 3456 7890 (WhatsApp)
                                </a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Working Hours -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Jam Operasional</h3>
                            <p class="text-gray-600">
                                Senin - Jumat: 09:00 - 18:00<br>
                                Sabtu: 09:00 - 15:00<br>
                                Minggu & Hari Libur: Tutup
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="flex items-center justify-center w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="flex items-center justify-center w-10 h-10 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition-colors" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="flex items-center justify-center w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors" aria-label="WhatsApp">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent"
                               placeholder="Masukkan nama lengkap Anda">
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent"
                               placeholder="nama@email.com">
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent"
                               placeholder="+62 812 3456 7890">
                    </div>
                    
                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subjek *</label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent"
                               placeholder="Topik pesan Anda">
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan *</label>
                        <textarea id="message" 
                                  name="message" 
                                  required
                                  rows="5"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent resize-none"
                                  placeholder="Tulis pesan Anda di sini..."></textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Kirim Pesan
                    </button>
                    
                    <p class="text-sm text-gray-500 text-center">
                        * Wajib diisi
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section (Optional) -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Lokasi Kantor Kami</h2>
            <p class="text-gray-600">Kunjungi kami di kantor pusat LIVORA</p>
        </div>
        
        <!-- Placeholder for Map -->
        <div class="bg-gray-200 rounded-xl overflow-hidden h-96 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                <p class="text-lg">Map Integration Coming Soon</p>
                <p class="text-sm">Google Maps / OpenStreetMap</p>
            </div>
        </div>
    </div>
</section>
@endsection
