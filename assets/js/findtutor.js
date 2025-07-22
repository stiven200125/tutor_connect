document.addEventListener("DOMContentLoaded", function () {
    const selectArea = document.querySelector("select[name='area']");
    const formFilter = document.getElementById("form-filter");
    const btnBorrarFiltro = document.getElementById("btn-delete-filter");
    const containerResultados = document.getElementById("main_container_two");
    const tituloResultados = document.getElementById("resultados-titulo");
    const modal = new bootstrap.Modal(document.getElementById("modalContacto"));
    const formContacto = document.getElementById("formContacto");
    const emailEstudiante = document.getElementById("emailEstudiante");
    const emailTutor = document.getElementById("emailTutor");
    const mensajeExito = document.getElementById("mensajeExito");


    // Cargar las áreas desde el backend
    fetch("../backend/routes/categorias.php")
        .then((res) => res.json())
        .then((areas) => {
            areas.forEach((area) => {
                const option = document.createElement("option");
                option.value = area.idArea;
                option.textContent = area.nombre_area;
                selectArea.appendChild(option);
            });
        })
        .catch((err) => {
            console.error("Error al cargar áreas:", err);
        });

    // Función para cargar tutores
    function cargarTutores(idArea = null, esFiltrado = false) {
        let url = "../backend/routes/buscartutores.php";
        if (idArea) {
            url += `?idArea=${idArea}`;
        }

        fetch(url)
            .then((res) => res.json())
            .then((tutores) => {
                containerResultados.innerHTML = "";

                if (!Array.isArray(tutores) || tutores.length === 0) {
                    containerResultados.innerHTML = "<p>No se encontraron tutores.</p>";
                    tituloResultados.style.display = esFiltrado ? "none" : "block";
                    return;
                }
                
                // Mostrar mensaje de resultados solo si se filtró
                tituloResultados.style.display = esFiltrado ? "block" : "none";
                
                // Mostrar tutores
                tutores.forEach((tutor) => {
                    const tutorHTML = `
                        <div id="find-tutor-container">
                            <img src="${tutor.foto_url}" alt="profile picture" id="profile-picture"/>
                            <div id="tutor-details">
                                <div>
                                    <p id="text-info"><span>Nombre: </span>${tutor.nombre} ${tutor.apellido}</p>
                                    <p id="text-info"><span>Área de desempeño: </span>${tutor.nombre_area}</p>
                                    <p id="text-info"><span>Descripción: </span>${tutor.descripcion}</p>
                                </div>
                                <div id="tutor-details-price">
                                    <span id="text-info">$${tutor.precio.toLocaleString("es-CO", { style: "currency", currency: "COP" })}</span>
                                    <button class="btn btn-outline-primary button-main btn-contactar" type="button" data-email="${tutor.email}" data-nombre="${tutor.nombre} ${tutor.apellido}">Contactar</button>
                                </div>
                            </div>
                        </div>
                    `;
                    containerResultados.insertAdjacentHTML("beforeend", tutorHTML);
                });
            })
            .catch((err) => {
                console.error("Error al cargar tutores:", err);
                containerResultados.innerHTML = "<p>Error al cargar tutores.</p>";
                tituloResultados.style.display = "none";
            });
    }

    // Leer datos enviados del formulario para filtrar por area seleccionada
    formFilter.addEventListener("submit", function (e) {
        e.preventDefault();
        const idAreaSeleccionada = selectArea.value;
        cargarTutores(idAreaSeleccionada || null, true);
    });

    // Borrar filtro
    btnBorrarFiltro.addEventListener("click", function () {
    selectArea.value = "";
    cargarTutores(null, false);
    tituloResultados.style.display = "none";
});

    // Cargar todos los tutores al inicio sin filtro
    cargarTutores(null, false);
    
    // Delegación para abrir modal al hacer clic en "Contactar"
    containerResultados.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-contactar")) {
            const emailTutorValue = e.target.dataset.email;
            const nombreTutor = e.target.dataset.nombre;
            const correoEstudiante = "estudiante@correo.com";
            
            emailEstudiante.value = correoEstudiante;
            emailTutor.value = emailTutorValue;
            formContacto.reset();
            mensajeExito.classList.add("d-none");
            
            modal.show();
        }
    });
});
