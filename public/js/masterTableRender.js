
document.addEventListener('DOMContentLoaded', function() {
    // const url = '/api/personas';
    // const campos = ['nombre', 'apellido', 'fecha_nacimiento'];
    // const page = 1; // Página inicial
    
    renderTable(url, data, campos, page);
    

    //hay que corregit, el evento se dispara al clickear en el id no tiene sentido. 
    // deberia ser en la clas
    //funcion js vanilla que no le encuentro la vuelta
    /* 
        document.querySelector('#paginator').addEventListener('click', function(event) {
            if (event.target.matches('.page-link')) {
                let page = event.target.getAttribute('data-page');
                renderTable(url, data, campos, page);
            }
        }); 
    */

});

$(document).on('click','.page-btn', function() {
    let page = $(this).data('page');

    // alert(`page:  ${page}\n url: ${url} \n data: ${data} \n campos: ${campos}`);
    
    renderTable(url, data, campos, page);
});

$(document).on('keyup', '#filtro', function () {
    let valor = $(this).val();
    data.filtro = valor; // ✅ agregás o actualizás la key 'filtro' en el objeto data
    renderTable(url, data, campos, page);
});