const idDeporte = document.getElementById('deporte')
idDeporte.addEventListener('change', getTipoDeporte)

const TipoDeporte = document.getElementById('tipoDeporte')

function fetchAndSetData(url, formData, targetElement) {
    return fetch(url, {
        method: "POST",
        body: formData,
        mode: 'cors'
    })
        .then(response => response.json())
        .then(data => {
            targetElement.innerHTML = data
        })
        .catch(err => console.log(err))
}

function getTipoDeporte() {
    let deporte = idDeporte.value
    let url = 'getTipoDeporte.php'
    let formData = new FormData()
    formData.append('id_deporte', deporte)

    fetchAndSetData(url, formData, TipoDeporte)
}