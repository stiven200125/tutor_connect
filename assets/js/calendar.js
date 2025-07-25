document.addEventListener("DOMContentLoaded", function () {
    const calendarioEl = document.getElementById("calendario");

    const calendario = new FullCalendar.Calendar(calendarioEl, {
        locale: 'es',
        themeSystem: 'bootstrap5', // Esto es clave
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        selectable: true,
        dateClick: function (info) {
            alert("Haz clic en: " + info.dateStr);
        },
        events: [
            {
                title: 'Tutoría con Juan',
                start: '2025-07-20',
                color: '#0d6efd'
            },
            {
                title: 'Clase agendada',
                start: '2025-07-22',
                color: '#198754'
            }
        ]
    });

    calendario.render();
});
