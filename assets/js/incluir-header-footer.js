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

                        // Capitalizar por separado
                        const capitalizedNombre = nombre.charAt(0).toUpperCase() + nombre.slice(1).toLowerCase();
                        const capitalizedApellido = apellido.charAt(0).toUpperCase() + apellido.slice(1).toLowerCase();

                        const nameUserSpan = document.getElementById("name-user");
                        if (nameUserSpan) {
                            nameUserSpan.textContent = `${capitalizedNombre} ${capitalizedApellido}`;
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
