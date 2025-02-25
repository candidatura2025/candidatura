function toggleDetalle(element) {
    let detalle = element.nextElementSibling;
    detalle.style.display = detalle.style.display === "block" ? "none" : "block";
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("form-contacto").addEventListener("submit", function (event) {
        event.preventDefault();

        let nombre = document.getElementById("nombre").value;
        let asunto = document.getElementById("asunto").value;
        let mensaje = document.getElementById("mensaje").value;
        let resultado = document.getElementById("resultado");

        let formData = new FormData();
        formData.append("nombre", nombre);
        formData.append("asunto", asunto);
        formData.append("mensaje", mensaje);

        // Cambié la ruta porque el script PHP está en `php/`
        fetch("../php/enviar_correo.php", { 
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            resultado.innerHTML = data;
            document.getElementById("form-contacto").reset();
        })
        .catch(error => {
            resultado.innerHTML = "Error al enviar el mensaje.";
        });
    });
});
