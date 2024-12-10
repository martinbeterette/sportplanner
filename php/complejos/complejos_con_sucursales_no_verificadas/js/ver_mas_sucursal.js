$(() => {
	$(document).on("click", ".btn-verificar", function() {
		let registros = $(this).data('registros');
		Swal.fire({
			title: 'Detalle',
			html:`
				<div class="div-info-modal" ">

					<p><strong>Sucursal:</strong> ${registros.descripcion_sucursal}</p>
					<p><strong>Fecha de fundacion:</strong> ${registros.fecha_de_creacion}</p>
					<p><strong>Fecha de insercion:</strong> ${registros.fecha_alta}</p>
					<p><strong>Direccion:</strong> ${registros.direccion}</p>
					<p><strong>Barrio:</strong> ${registros.descripcion_barrio}</p>
					<p><strong>Localidad:</strong> ${registros.descripcion_localidad}</p>
					<p><strong>Provincia:</strong> ${registros.descripcion_provincia}</p>
					<p><strong>Complejo:</strong> ${registros.descripcion_complejo}</p>
					<p><strong>Cstado:</strong> ${registros.verificado}</p>

				</div>

				<div class="div-btn-modal" style="margin-top:10px">
					<button class="btn-verificar" onclick="confirmarAccion('verificar', ${registros.id_sucursal}, ${registros.id_complejo})">Verificar</button>
					<button class="btn-invalidar" onclick="confirmarAccion('invalidar', ${registros.id_sucursal}, ${registros.id_complejo})">Invalidar</button>
					<button 
						class="btn-eliminar"
						onclick="deseaEliminarScurusal(${registros.id_sucursal}, ${registros.id_complejo})"
					>Eliminar Sucursal</button>
				</div>



				<button
					onclick="Swal.close()"
					style="
						position:absolute; 
						top:10px; 
						right:10px; 
						background: none;
						border: none; 
						color: orange; 
						font-size: 30px; 
						cursor: pointer; 
						padding: 0;"
				>&times;</button>
			`,
			animation:false,
			showConfirmButton: false,
		});
	});
});


function deseaEliminarScurusal(id_sucursal, id_complejo) {
	Swal.fire({
		title: 'Seguro que desea eliminar esta sucursal?',
		text: 'No podra deshacer esta accion',
		icon: 'warning',
		animation:false,
		confirmButtonText: 'Seguro, eliminar',
		showCancelButton: true,
		cancelButtonText:'Cancelar',
	}).then(function (result) {
		if (result.isConfirmed) {
			window.location.href = `eliminar_sucursal.php?id_sucursal=${id_sucursal}&id_complejo=${id_complejo}`;
		}
	});
};

function confirmarAccion(accion, id_sucursal, id_complejo) {
	Swal.fire({
		title: `¿Seguro que desea ${accion} esta sucursal?`,
		icon: 'warning',
		animation:false,
		confirmButtonText: `Seguro, ${accion}`,
		showCancelButton: true,
		cancelButtonText:'Cancelar',
	}).then(function(result) {
		if(result.isConfirmed) {
			window.location.href = `${accion}_sucursal.php?id_sucursal=${id_sucursal}&id_complejo=${id_complejo}`;
		}
	});
};