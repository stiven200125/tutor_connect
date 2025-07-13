document.addEventListener('DOMContentLoaded', () => {
    // Cargar el header
    fetch('../components/header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-container').innerHTML = data;

            // Ejecutar lógica del perfil cuando el header ya esté cargado
            fetch('../backend/routes/profile.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        const nombre = data.nombre.split(" ")[0];
                        const apellido = data.apellido.split(" ")[0];
                        const rol = data.rol;

                        // Capitalizar el primer carácter de nombre y apellido
                        const capitalizedNombre = nombre.charAt(0).toUpperCase() + nombre.slice(1).toLowerCase();
                        const capitalizedApellido = apellido.charAt(0).toUpperCase() + apellido.slice(1).toLowerCase();

                        const nameUserSpan = document.getElementById("name-user");
                        const typerolSpan = document.getElementById("type-rol");
                        if (nameUserSpan) {
                            nameUserSpan.textContent = `${capitalizedNombre} ${capitalizedApellido}`;
                            typerolSpan.textContent = rol === 1 ? "Estudiante" : rol === 2 ? "Tutor" : "Administrador";
                        }
                    } else {
                        window.location.href = "/views/login.html";
                    }
                })
                .catch(() => {
                    window.location.href = "/views/login.html";
                });
        });

    // Cargar el footer
    fetch('../components/footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer-container').innerHTML = data;
        });
});
