let toggled = false;
document.getElementById('menu-btn')?.addEventListener('click', () => {
    const menu = document.getElementById('nav-menu');
    if (!toggled) {
        menu.style.top = "3.5rem";
    }
    else {
        menu.removeAttribute("style");
    }
    toggled = !toggled;
});

window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
        const menu = document.getElementById('nav-menu');
        menu.removeAttribute("style");
        toggled = false;
    }
});