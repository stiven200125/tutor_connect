//Carga del perfil del usuario al cargar la página
document.addEventListener("DOMContentLoaded", function () {
    fetch("../backend/routes/profile.php")
        .then((res) => res.json())
        .then((data) => {
            if (data.error) {
                alert("Error: " + data.error);
                console.error("Error del backend:", data.error);
                return;
            }
            // Llenar los campos del formulario con los datos del perfil
            if (data.rol === 1) {
                document.querySelector('input[name="nombre"]').value = data.nombre || '';
                document.querySelector('input[name="apellido"]').value = data.apellido || '';
                document.querySelector('input[name="direccion"]').value = data.direccion || '';
                document.querySelector('input[name="telefono"]').value = data.telefono || '';
                document.querySelector('input[name="correo"]').value = data.correo_electronico || '';
                document.querySelector('input[name="area"]').value = data.idArea || '';
                document.getElementById("user-id").value = data.id || '';
                document.getElementById("user-rol").value = data.rol || '';
            } else if (data.rol === 2) {
                document.querySelector('input[name="nombre"]').value = data.nombre || '';
                document.querySelector('input[name="apellido"]').value = data.apellido || '';
                document.querySelector('input[name="direccion"]').value = data.direccion || '';
                document.querySelector('input[name="telefono"]').value = data.telefono || '';
                document.querySelector('input[name="correo"]').value = data.correo_electronico || '';
                document.querySelector('input[name="area"]').value = data.idArea || '';
                document.getElementById("user-id").value = data.id || '';
                document.getElementById("user-rol").value = data.rol || '';
            }

            // Habilitar el botón después de llenar los campos
            document.querySelector("button[type='submit']").disabled = false;
        })
        .catch((error) => {
            console.error("Error al obtener el perfil:", error);
            alert("No se pudo cargar el perfil.");
        });

    // Validar envío del formulario
    document.querySelector("form").addEventListener("submit", function (e) {
        const id = document.getElementById("user-id").value;
        const rol = document.getElementById("user-rol").value;

        console.log("Enviando con ID:", id, "ROL:", rol);

        if (!id || !rol) {
            e.preventDefault();
            alert("Por favor espera a que se cargue tu perfil antes de enviar.");
        }
    });
});

// Mostrar modales según el parámetro en la URL (?exito=1 o ?error=1)
const params = new URLSearchParams(window.location.search);

if (params.get("exito") === "1") {
    alert("Perfil actualizado con éxito");
} else if (params.get("error") === "1") {
    alert("Hubo un error al actualizar tu perfil");
}