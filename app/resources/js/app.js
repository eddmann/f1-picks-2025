document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("mobile-nav-toggle");
    const mobile = document.getElementById("mobile-nav");
    if (toggle && mobile) {
        toggle.addEventListener("click", () => {
            mobile.classList.toggle("hidden");
        });
    }
});
