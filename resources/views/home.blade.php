
<style>
/* Force white text for hero section */
.hero-section, .home-section, .section-hero {
    color: #ffffff !important;
}

.hero-section h1,
.hero-section h2,
.hero-section h3,
.hero-section p,
.hero-section span,
.hero-section div {
    color: #ffffff !important;
}

/* Force white text for all text elements */
* {
    color: #ffffff !important;
}
</style>
@extends('layouts.app')

@section('title', 'Beranda - SMP Negeri 01 Namrole')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Selamat Datang di SMP Negeri 01 Namrole</h1>
            <p class="text-xl text-primary-100 mb-8">Sekolah Unggul Berkarakter, Berprestasi, dan Berdaya Saing Global</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('profil') }}" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-primary-50 transition-colors">
                    Lihat Profil Sekolah
                </a>
                <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition-colors">
                    Informasi Pendaftaran
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Info Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Guru Berkualitas</h3>
                    <p class="text-gray-600">Tenaga pendidik berpengalaman dan berkompeten</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Akreditasi A</h3>
                    <p class="text-gray-600">Sekolah terakreditasi A dengan skor 95</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Fasilitas Lengkap</h3>
                    <p class="text-gray-600">Fasilitas pembelajaran yang modern dan memadai</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
document.addEventListener("DOMContentLoaded", function() {
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        element.style.color = "#ffffff !important";
    });
});
</script>