//Navbar and Menus

jQuery(document).ready(function ($) {
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
});

function toggleMenu(header) {
    header.classList.toggle('active');
    const subMenu = header.nextElementSibling;
    subMenu.classList.toggle('open');
}