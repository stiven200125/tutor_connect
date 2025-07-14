document.addEventListener("DOMContentLoaded", function () {
    const selectArea = document.querySelector('select[name="area"]');

    // 1. Primero carga las áreas (categorías)
    fetch("../backend/routes/categorias.php")
        .then(res => res.json())
        .then(categorias => {
            selectArea.innerHTML = "<option value=''>Selecciona un área</option>";
            categorias.forEach(area => {
                const option = document.createElement("option");
                option.value = area.idArea;
                option.textContent = area.nombre_area;
                selectArea.appendChild(option);
            });

            // 2. Luego carga el perfil
            return fetch("../backend/routes/profile.php");
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert("Error: " + data.error);
                console.error("Error del backend:", data.error);
                return;
            }

            // Llenar campos del perfil
            if (data.rol === 1) {
                document.querySelector('input[name="nombre"]').value = data.nombre || '';
                document.querySelector('input[name="apellido"]').value = data.apellido || '';
                document.querySelector('input[name="direccion"]').value = data.direccion || '';
                document.querySelector('input[name="telefono"]').value = data.telefono || '';
                document.querySelector('input[name="correo"]').value = data.correo_electronico || '';
                document.querySelector('select[name="area"]').value = data.idArea || '';
                document.getElementById("user-id").value = data.id || '';
                document.getElementById("user-rol").value = data.rol || '';
                document.querySelector('#correo-hidden').value = data.correo_electronico || '';
            } else if (data.rol === 2) {
                document.querySelector('input[name="nombre"]').value = data.nombre || '';
                document.querySelector('input[name="apellido"]').value = data.apellido || '';
                document.querySelector('input[name="direccion"]').value = data.direccion || '';
                document.querySelector('input[name="telefono"]').value = data.telefono || '';
                document.querySelector('input[name="correo"]').value = data.correo_electronico || '';
                document.querySelector('select[name="area"]').value = data.idArea || '';
                document.querySelector('textarea[name="descripcion"]').value = data.descripcion || '';
                document.querySelector('input[name="precio"]').value = data.precio || '';
                document.getElementById("user-id").value = data.id || '';
                document.getElementById("user-rol").value = data.rol || '';
                document.querySelector('#correo-hidden').value = data.correo_electronico || '';
            }
            // Habilitar el botón
            document.querySelector("button[type='submit']").disabled = false;
        })
        .catch(error => {
            console.error("Error en el flujo de carga:", error);
            alert("No se pudo cargar el perfil o las categorías.");
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

document.getElementById("btn-delete-account").addEventListener("click", function () {
    const confirmModal = new bootstrap.Modal(document.getElementById("confirmarEliminacionModal"));
    confirmModal.show();
});

// Acción cuando el usuario confirma eliminación
document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
    const id = document.getElementById("user-id").value;
    const rol = document.getElementById("user-rol").value;

    const formData = new FormData();
    formData.append("id", id);
    formData.append("rol", rol);

    fetch("../backend/routes/index.php?accion=eliminar", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        // Mostrar modal de éxito
        const successModal = new bootstrap.Modal(document.getElementById("eliminarExitoModal"));
        successModal.show();
    })
    .catch(err => {
        console.error("Error al eliminar cuenta:", err);
        alert("Hubo un problema al eliminar tu cuenta.");
    });
});

// Redirige después de cerrar la modal de éxito
document.getElementById("deleteSuccessRedirect").addEventListener("click", function () {
    window.location.href = "login.html"; // cambia esto a tu ruta real
});

/// Mostrar modales según el parámetro en la URL (?exito=1 o ?error=1)
const params = new URLSearchParams(window.location.search);

if (params.get("exito") === "1") {
    const registroModal = new bootstrap.Modal(document.getElementById('actualizarExitosoModal'));
    registroModal.show();
}

if (params.get("error") === "1") {
    const errorModal = new bootstrap.Modal(document.getElementById('actualizarFallidoModal'));
    errorModal.show();
}