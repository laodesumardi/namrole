// Force White Text for Home Sections
document.addEventListener("DOMContentLoaded", function() {
    // Force all text elements to be white
    const textElements = document.querySelectorAll("h1, h2, h3, h4, h5, h6, p, span, div, a, button, .title, .subtitle, .description, .section-title, .section-subtitle, .section-description");
    
    textElements.forEach(function(element) {
        element.style.color = "#ffffff";
        element.style.color = "white";
        element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
    });
    
    // Force specific hero section elements
    const heroElements = document.querySelectorAll(".hero-section, .section-hero, .home-section");
    heroElements.forEach(function(element) {
        const textChildren = element.querySelectorAll("*");
        textChildren.forEach(function(child) {
            child.style.color = "#ffffff";
            child.style.color = "white";
            child.setAttribute("style", child.getAttribute("style") + "; color: #ffffff !important;");
        });
    });
    
    // Force all elements with text content
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        if (element.textContent && element.textContent.trim() !== "") {
            element.style.color = "#ffffff";
            element.style.color = "white";
            element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
        }
    });
    
    console.log("Force white text applied to all elements");
});

// Force white text on window load
window.addEventListener("load", function() {
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        element.style.color = "#ffffff";
        element.style.color = "white";
        element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
    });
    console.log("Force white text applied on window load");
});