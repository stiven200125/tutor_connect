document.addEventListener("DOMContentLoaded", function () {
    const calendarioEl = document.getElementById("calendario");

    const calendario = new FullCalendar.Calendar(calendarioEl, {
        locale: 'es',
        themeSystem: 'bootstrap5',
        initialView: 'dayGridMonth',
        headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function (info) {
        const props = info.event.extendedProps;

        document.getElementById("emailEstudiante").textContent = props.correoEstudiante;
        document.getElementById("emailTutor").textContent = props.correoTutor;
        document.getElementById("fecha").textContent = props.fecha;
        document.getElementById("franja").textContent = props.franja;
        document.getElementById("horaSeleccionada").textContent = props.horaTutoria;

        const enlace = document.getElementById("linksesion");
        enlace.innerHTML = `<a href="${props.enlaceSesion}" target="_blank">${props.enlaceSesion}</a>`;

        const modal = new bootstrap.Modal(document.getElementById("modalCalendar"));
        modal.show();
        },
        events: {
        url: '../backend/routes/getTutoriasCalendar.php',
        failure: function () {
            alert('Hubo un error al cargar los eventos');
        }
        }
    });

    calendario.render();
});
