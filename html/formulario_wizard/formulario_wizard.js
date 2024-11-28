function mostrarPaso(paso) {
  $(".paso").hide();  // Oculta todos los pasos
  $("#paso-" + paso).show();  // Muestra el paso actual
}

$(document).ready(function() {
  // Inicializa el primer paso
  mostrarPaso(1);
});
