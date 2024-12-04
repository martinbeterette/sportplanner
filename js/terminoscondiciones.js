
document.querySelector('.vercondiciones').addEventListener('click', function () {
    Swal.fire({
        title: '<strong>Términos y Condiciones</strong>',
        html: `
                <div style="text-align: left; max-height: 70vh; overflow-y: auto; padding: 10px; font-size: 14px;">
                    <h3>1. Introducción</h3>
                    <p>Al utilizar la plataforma Sportsplanner, el usuario acepta los términos y condiciones aquí establecidos...</p>
                    
                    <h3>2. Alcance del Servicio</h3>
                    <p>Sportsplanner proporciona una plataforma para la gestión de reservas de canchas deportivas...</p>
                    
                    <h3>3. Responsabilidades del Usuario</h3>
                    <p>El usuario es responsable de proveer información completa y veraz...</p>
                    
                    <h3>4. Limitación de Responsabilidad</h3>
                    <p>Sportsplanner no será responsable de errores en el sistema debido a fallos técnicos...</p>
                    
                    <h3>5. Pagos y Transacciones</h3>
                    <p>Los pagos realizados a través de la plataforma están sujetos a las políticas del proveedor...</p>
                    
                    <h3>6. Seguridad de los Datos</h3>
                    <p>Sportsplanner implementa medidas razonables para proteger la información del usuario...</p>
                    
                    <h3>7. Propiedad Intelectual</h3>
                    <p>Todos los derechos sobre el diseño, funcionalidad, y contenido de Sportsplanner son propiedad...</p>
                    
                    <h3>8. Modificaciones al Servicio o al Deslinde</h3>
                    <p>Sportsplanner se reserva el derecho de modificar el servicio, los términos...</p>
                    
                    <h3>9. Contacto y Asistencia</h3>
                    <p>Para reportar problemas técnicos, disputas, o consultas generales, el usuario puede contactarnos...</p>
                    
                    <h3>10. Aceptación de los Términos</h3>
                    <p>El uso continuo de la plataforma implica la aceptación de este deslinde de responsabilidad en su totalidad...</p>
                </div>
            `,
        showCloseButton: true,
        showConfirmButton: false,
        width: '90%',
        customClass: {
            popup: 'swal2-modal-custom'
        }
    });
});