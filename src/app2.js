$(document).ready(function() {
    listarProyectos();

    // Manejar el envío del formulario
    $('#project-form').on('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        // Recoger los datos del formulario
        const nombre = $('#name').val();
        const descripcion = $('#description').val();

        if (!nombre || !descripcion) {
            alert('Por favor, completa todos los campos.');
            return;
        }

        // Enviar los datos mediante AJAX
        $.ajax({
            url: '../docs/add-project.php',
            type: 'POST',
            data: { nombre: nombre, descripcion: descripcion },
            success: function(response) {
                const data = JSON.parse(response);

                if (data.status === 'success') {
                    alert(data.message);
                    $('#project-form')[0].reset(); // Limpiar formulario
                    listarProyectos(); // Actualizar la lista de proyectos
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Error al procesar la solicitud.');
            }
        });
    });

    // Función para listar proyectos
    function listarProyectos() {
        $.ajax({
            url: '../docs/list-projects.php',
            type: 'GET',
            success: function(response) {
                const projects = JSON.parse(response);

                if (projects.length > 0) {
                    let template = '';
                    projects.forEach(project => {
                        template += `
                            <tr>
                                <td>${project.id}</td>
                                <td>${project.nombre}</td>
                                <td>${project.descripcion}</td>
                            </tr>
                        `;
                    });
                    $('#projects').html(template);
                } else {
                    $('#projects').html('<tr><td colspan="3">No hay proyectos registrados.</td></tr>');
                }
            },
            error: function() {
                alert('Error al cargar los proyectos.');
            }
        });
    }

    $('#project-form').submit(function (e) {
        e.preventDefault();
    
        let name = $('#name').val().trim();
        let description = $('#description').val().trim();
    
        if (!name || !description) {
            alert('Por favor, completa todos los campos.');
            return;
        }
    
        $.ajax({
            url: '../docs/add-project.php',
            type: 'POST',
            data: { name, description },
            success: function (response) {
                try {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        alert('Proyecto agregado correctamente');
                        listarProyectos();
                    } else {
                        alert('Error: ' + jsonResponse.message);
                    }
                } catch (e) {
                    console.error('Error de JSON:', e, response);
                    alert('Error inesperado en el servidor.');
                }
            },
            error: function () {
                alert('Error al comunicarse con el servidor.');
            }
        });
    });
    
});
