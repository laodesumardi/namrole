
@extends("layouts.app")

@section("content")
<div class="hero-section" style="background-color: #000000; color: #ffffff !important;">
    <div class="container">
        <h1 style="color: #ffffff !important;">Selamat Datang di SMP Negeri 01 Namrole</h1>
        <h2 style="color: #ffffff !important;">Sekolah Unggulan dengan Pendidikan Berkualitas</h2>
        <p style="color: #ffffff !important;">Selamat Datang di SMP Negeri 01 Namrole</p>
        <span style="color: #ffffff !important;">Sekolah Unggulan dengan Pendidikan Berkualitas</span>
    </div>
</div>

<style>
/* Force white text for all elements */
* {
    color: #ffffff !important;
    color: white !important;
    color: #fff !important;
}

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
body, html, div, span, p, h1, h2, h3, h4, h5, h6 {
    color: #ffffff !important;
}
</style>

<script>
// Force white text on page load
document.addEventListener("DOMContentLoaded", function() {
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        element.style.color = "#ffffff !important";
    });
});
</script>
@endsection
