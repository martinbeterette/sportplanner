$(document).ready(function(){

	$('.menu-btn').on('click', function () {
		toggleMenu();
	});

	function toggleMenu() {
	    var menu = document.getElementById("aside-menu");
	    // var menu = $("#side-menu");
	    if (menu.style.width === "250px") {
	        menu.style.width = "0"; // Cerrar menú
	        $(".menu-btn").css("color", "black");
	    } else {
	        menu.style.width = "250px"; // Abrir menú
	        $(".menu-btn").css("color", "white");
	    }
	}	
});

