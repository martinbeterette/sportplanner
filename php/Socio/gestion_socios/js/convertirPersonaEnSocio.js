
	function mostrarFormularioSocio(persona,id_complejo) {
		let opciones = '';
		membresias.forEach(function(membresia) {
	        opciones += `<option value="${membresia.id_membresia}">${membresia.descripcion_membresia}</option>`;
	    });
	    Swal.fire({
	        title: 'Detalles de la Membresía',
	        html: `

	            <label for="meses">Cantidad de Meses de Membresía:</label>
	            <input type="number" id="meses" class="swal2-input" placeholder="Meses de Membresía" required>

	            <label for="tipo_membresia">Tipo de Membresía:</label>
	            <select id="tipo_membresia" class="swal2-input">
	                ${opciones}
	            </select>
	        `,
	        confirmButtonText: 'Guardar',
	        showCancelButton: true,
	        preConfirm: function () {
	            let meses = $('#meses').val();
	            let membresia = $('#tipo_membresia').val();
	            console.log("Membresia seleccionada: " + membresia);
	            alert(`persona: ${persona} meses: ${meses} membresia: ${membresia} complejo: ${id_complejo}`);
	            // Llamar a la función para insertar el socio
	            insertarSocio(persona, meses, membresia,id_complejo);
	        }
	    });
	}

	function insertarSocio(persona, meses, membresia, id_complejo) {
	    $.ajax({
	        url: '../ajax/insertarSocio.php',
	        method: 'POST',
	        data: {
	            persona: persona,
	            meses: meses,
	            membresia: membresia,
	            id_complejo: id_complejo
	        },
	        success: function(response) {
	        	if(response.status === 'success'){
	            	Swal.fire('¡Socio agregado con éxito!', '', 'success');
	            	$('#form-insercion-socio')[0].reset();
	        	} else {
	        		Swal.fire('falta terminar', `${response.error}`, 'error');
	        	}
	        },
	        error: function() {
	            Swal.fire('Error al agregar socio', '', 'error');
	        }
	    });
	}
