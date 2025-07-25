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
                        const navLinks = document.getElementById("nav-links");

                        if (nameUserSpan && typerolSpan) {
                            nameUserSpan.textContent = `${capitalizedNombre} ${capitalizedApellido}`;
                            typerolSpan.textContent = rol === 1 ? "Estudiante" : rol === 2 ? "Tutor" : "Administrador";
                        }

                        // Lógica de menú dinámico según el rol
                        if (navLinks) {
                            navLinks.innerHTML = ""; // Limpiar menú previo

                            if (rol === 1) {
                                // Menú para Estudiante
                                navLinks.innerHTML = `
                                    <li class="nav-item"><a class="nav-link" href="studentProfile.html">Perfil</a></li>
                                    <li class="nav-item"><a class="nav-link" href="findTutor.html">Buscar Tutores</a></li>
                                    <li class="nav-item"><a class="nav-link" href="calendar.html">Calendario</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#" id="logout-link">Cerrar sesión</a></li>
                                `;
                            } else if (rol === 2) {
                                // Menú para Tutor
                                navLinks.innerHTML = `
                                    <li class="nav-item"><a class="nav-link" href="tutorProfile.html">Perfil</a></li>
                                    <li class="nav-item"><a class="nav-link" href="applications.html">Solicitudes</a></li>
                                    <li class="nav-item"><a class="nav-link" href="calendar.html">Calendario</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#" id="logout-link">Cerrar sesión</a></li>
                                `;
                            } 
                            // Asignar evento al logout dinámico
                            const logoutLink = document.getElementById("logout-link");
                            if (logoutLink) {
                                logoutLink.addEventListener("click", function (e) {
                                    e.preventDefault();
                                    fetch('../backend/routes/logout.php') // Debes tener este archivo para cerrar sesión
                                        .then(() => {
                                            window.location.href = "../views/login.html";
                                        });
                                });
                            }
                        }

                    } else {
                        window.location.href = "../views/login.html";
                    }
                })
                .catch(() => {
                    window.location.href = "../views/login.html";
                });
        });

    // Cargar el footer
    fetch('../components/footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer-container').innerHTML = data;
        });
});
