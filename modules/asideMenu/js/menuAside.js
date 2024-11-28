document.addEventListener("DOMContentLoaded", function () {
    const menu = document.getElementById("menu");
    const toggleButton = document.getElementById("toggle-menu");

    // Mostrar/Ocultar el menú cuando se hace clic en el botón
    toggleButton.addEventListener("click", function () {
        menu.classList.toggle("active");
        toggleButton.style.display = "none"; // Esconder el botón
    });

    // Cerrar el menú al hacer clic fuera de él
    document.addEventListener("click", function (e) {
        if (!menu.contains(e.target) && !toggleButton.contains(e.target)) {
            menu.classList.remove("active");
            toggleButton.style.display = "block"; // Mostrar el botón de nuevo
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Seleccionar todos los elementos con submenú
    const menuItemsWithSubmenu = document.querySelectorAll(".has-submenu");

    menuItemsWithSubmenu.forEach(item => {
        item.addEventListener("click", function (e) {
            e.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

            // Alternar la clase activa para mostrar/ocultar el submenú
            const parentLi = this.parentElement;
            parentLi.classList.toggle("submenu-active");
        });
    });
});
