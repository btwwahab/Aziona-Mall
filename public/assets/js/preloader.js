// Preloader: Hide when page fully loads
window.addEventListener("load", function () {
    let preloader = document.getElementById("preloader");
    if (preloader) {
        preloader.style.display = "none";
    }
});
