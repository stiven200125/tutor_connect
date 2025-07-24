document.addEventListener("DOMContentLoaded", function () {
    const containerResultados = document.getElementById("applications-container");
    const modal = new bootstrap.Modal(document.getElementById("modalAgendar"));
    const formAgendar = document.getElementById("formAgendar");
    const mensajeExito = document.getElementById("mensajeExito");
    const inputEmailEstudiante = document.getElementById("emailEstudiante");
    const inputEmailTutor = document.getElementById("emailTutor");
    const inputFecha = document.getElementById("fecha");
    const inputFranja = document.getElementById("franja");
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
            if (solicitudes.length == " ") {
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
                                    data-franja="${solicitud.descripcion_franja}"
                                    data-id-franja="${solicitud.idFranja}"
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
            const franjaTexto = boton.getAttribute("data-franja");
            const franjaId = boton.getAttribute("data-id-franja");
            inputFranja.value = franjaTexto;

            // Llenar el modal con la info
            inputEmailEstudiante.value = correoEstudiante;
            inputEmailTutor.value = correoTutor;
            inputFecha.value = fecha;


        // Cargar horas de la franja
        fetch(`../backend/routes/getHorariosPorFranjas.php?idFranja=${franjaId}`)
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('selectHorario');
                select.innerHTML = '<option value="">Selecciona una hora</option>'; // reset

                data.forEach(hora => {
                    const option = document.createElement('option');
                    option.value = hora.idHorario;
                    option.textContent = hora.horaTutoria;
                    select.appendChild(option);
                });
            })
            .catch(err => {
                console.error("Error al cargar horarios:", err);
            });
            document.getElementById("idTutoria").value = boton.getAttribute("data-id");
            modal.show();
        }
    });

    formAgendar.addEventListener("submit", function (e) {
        e.preventDefault();

        const idTutoria = document.getElementById("idTutoria").value;
        const idHorario = document.getElementById("selectHorario").value;
        const linkSesion = document.getElementById("linksesion").value;

        if (!idHorario || !linkSesion) {
            alert("Por favor selecciona una hora y agrega un enlace.");
            return;
        }

        const formData = new URLSearchParams();
        formData.append("idTutoria", idTutoria);
        formData.append("idHorario", idHorario);
        formData.append("linkSesion", linkSesion);

        fetch("../backend/routes/saveTutoring.php", {
            method: "POST",
            body: formData,
        })
        .then(res => res.text())
        .then(response => {
            if (response === "Tutoría Agendada con Éxito") {
                mensajeExito.classList.remove("d-none");

                // Ocultar modal después de 1.5s
                setTimeout(() => {
                    modal.hide();
                    
                    // Quitar la card del DOM
                    const card = document.querySelector(`.btn-agendar[data-id='${idTutoria}']`)?.closest(".card-solicitud");
                    if (card) card.remove();
                    
                    // Verificar si ya no quedan más cards
                    const quedanSolicitudes = containerResultados.querySelectorAll(".card-solicitud").length;
                    if (quedanSolicitudes === 0) {
                        containerResultados.innerHTML = `<p>No hay solicitudes pendientes.</p>`;
                    }
                }, 1500);

            } else {
                alert("Error: " + response);
            }
        })
        .catch(err => {
            console.error("Error al enviar el formulario:", err);
            alert("Ocurrió un error al agendar la tutoría.");
        });
    });
});
