
// ULTIMATE FORCE WHITE TEXT JAVASCRIPT
document.addEventListener("DOMContentLoaded", function() {
    // Force all text elements to be white
    const textElements = document.querySelectorAll("h1, h2, h3, h4, h5, h6, p, span, div, a, button, .title, .subtitle, .description, .hero-text, .hero-title, .hero-subtitle, .hero-description, .welcome-text, .school-text, .quality-text");
    
    textElements.forEach(function(element) {
        element.style.color = "#ffffff";
        element.style.color = "white";
        element.style.color = "#fff";
        element.style.color = "rgb(255, 255, 255)";
        element.style.color = "rgba(255, 255, 255, 1)";
        element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
        element.setAttribute("style", element.getAttribute("style") + "; color: white !important;");
        element.setAttribute("style", element.getAttribute("style") + "; color: #fff !important;");
    });
    
    // Force white text for all elements
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        if (element.style.color) {
            element.style.color = "#ffffff !important";
        }
    });
    
    // Force white text for body and html
    document.body.style.color = "#ffffff !important";
    document.documentElement.style.color = "#ffffff !important";
    
    // Force white text for specific hero elements
    const heroElements = document.querySelectorAll(".hero-section, .home-section, .section-hero");
    heroElements.forEach(function(element) {
        element.style.color = "#ffffff !important";
        const children = element.querySelectorAll("*");
        children.forEach(function(child) {
            child.style.color = "#ffffff !important";
        });
    });
});
