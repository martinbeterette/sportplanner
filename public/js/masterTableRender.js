
document.addEventListener('DOMContentLoaded', function() {
    // const url = '/api/personas';
    // const campos = ['nombre', 'apellido', 'fecha_nacimiento'];
    // const page = 1; // Página inicial
    
    renderTable(url, data, campos, page);
    

    //hay que corregit, el evento se dispara al clickear en el id no tiene sentido. 
    // deberia ser en la clas
    document.querySelector('.page-link').addEventListener('click', function(event) {
        if (event.target.matches('.page-link')) {
            let page = event.target.getAttribute('data-page');
            renderTable(url, data, campos, page);
        }
    });
});