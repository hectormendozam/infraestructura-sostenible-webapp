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
            url: 'add-project.php',
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
            url: './list-projects.php',
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
});
