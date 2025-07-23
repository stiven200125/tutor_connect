document.addEventListener("DOMContentLoaded", function () {
    const containerResultados = document.getElementById("applications-container");
    const modal = new bootstrap.Modal(document.getElementById("modalAgendar"));
    const formAgendar = document.getElementById("formAgendar");
    const mensajeExito = document.getElementById("mensajeExito");

    const inputEmailEstudiante = document.getElementById("emailEstudiante");
    const inputEmailTutor = document.getElementById("emailTutor");
    const inputFecha = document.getElementById("fecha");
    const inputFranja = document.getElementById("horario");
    const inputLinkSesion = document.getElementById("linksesion");

    // 1. Cargar solicitudes del backend
    fetch("../backend/routes/applications.php")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error al cargar solicitudes");
            }
            return response.json();
        })
        .then((solicitudes) => {
            if (solicitudes.length === 0) {
                containerResultados.innerHTML = `<p>No hay solicitudes pendientes.</p>`;
                return;
            }

            solicitudes.forEach((solicitud) => {
                const card = document.createElement("div");
                card.classList.add("card-solicitud");
                card.innerHTML = `
                    <div id="applications-card-container">
                        <img src="${solicitud.foto_estudiante_url}" alt="profile picture" id="profile-picture"/>
                        <div id="student-details">
                            <div>
                                <p id="text-info"><span>Nombre: </span>${solicitud.nombre_estudiante}</p>
                                <p id="text-info"><span>Asunto: </span>${solicitud.asunto}</p>
                                <p id="text-info"><span>Descripción: </span>${solicitud.descripcionTutoria}</p>
                            </div>
                            <div id="schedule-tutoring">
                                <button 
                                    class="btn btn-outline-primary button-main btn-agendar" 
                                    type="button"
                                    data-id="${solicitud.idTutoria}"
                                    data-correo-estudiante="${solicitud.correo_estudiante}"
                                    data-correo-tutor="${solicitud.correo_tutor}"
                                    data-fecha="${solicitud.fecha}"
                                    data-franja="${solicitud.nombre_franja}"
                                >Agendar</button>
                            </div>
                        </div>
                    </div>
                `;
                containerResultados.appendChild(card);
            });
        })
        .catch((error) => {
            console.error(error);
            containerResultados.innerHTML = `<p>Error al cargar solicitudes.</p>`;
        });

    // 2. Abrir modal y llenar datos al hacer clic en Agendar
    containerResultados.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-agendar")) {
            // Reset modal
            formAgendar.reset();
            mensajeExito.classList.add("d-none");

            // Extraer atributos del botón
            const boton = e.target;
            const correoEstudiante = boton.getAttribute("data-correo-estudiante");
            const correoTutor = boton.getAttribute("data-correo-tutor");
            const fecha = boton.getAttribute("data-fecha");
            const franja = boton.getAttribute("data-franja");

            // Llenar el modal con la info
            inputEmailEstudiante.value = correoEstudiante;
            inputEmailTutor.value = correoTutor;
            inputFecha.value = fecha;
            inputFranja.value = franja;

            modal.show();
        }
    });
});
