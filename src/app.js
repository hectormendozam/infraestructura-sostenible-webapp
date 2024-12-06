$(document).ready(function(){
    let edit = false;

    listarProyectos();

        // Manejar el envío del formulario
        /*$('#project-form').on('submit', function(event) {
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
                url: '../backend/project-add.php',
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
        });*/
    

    // Función para listar proyectos
    function listarProyectos() {
        $.ajax({
            url: '../../backend/project-list.php',
            type: 'GET',
            success: function(response) {
                const projects = JSON.parse(response);

                if (projects.length > 0) {
                    let template = '';
                    projects.forEach(project => {
                        template += `
                            <tr projectId="${project.id}">
                                <td>${project.id}</td>
                                <td>
                                <a href="#" class="project-item">${project.nombre}</a>                                
                                </td>
                                <td>${project.descripcion}</td>
                            <td>
                                <button class="project-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                    document.getElementById("projects").innerHTML = template;
                } else {
                    $('#projects').html('<tr><td colspan="3">No hay proyectos registrados.</td></tr>');
                }
            },
            error: function() {
                alert('Error al cargar los proyectos.');
            }
        });
    }

    listarProyectos();

    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: '../../backend/project-search.php?search='+$('#search').val(),
                data: {search},
                type: 'GET',
                success: function (response) {
                    if(!response.error) {
                        // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                        const projects = JSON.parse(response);
                        
                        // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                        if(Object.keys(projects).length > 0) {
                            // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                            let template = '';

                            projects.forEach(project => {
                            
                                template += `
                                    <tr projectId="${project.id}">
                                        <td>${project.id}</td>
                                        <td>${project.nombre}</td>
                                        <td>${project.descripcion}</td>
                                            <button class="project-delete btn btn-danger">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                            // SE HACE VISIBLE LA BARRA DE ESTADO
                            $('#project-result').show();
                            // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                            $('#projects').html(template);    
                        }
                    }
                }
            });
        }
        else {
            $('#project-result').hide();
        }
    });

    $('#project-form').submit(function (e) {
        e.preventDefault();
    
        let name = $('#name').val().trim();
        let description = $('#description').val().trim();
        let user_id = $('#user_id').val();
        let projectId = $('#projectId').val(); // Si está vacío significa que estamos creando un nuevo proyecto
    
        // Verificar que los campos no estén vacíos
        if (!name || !description) {
            alert('Por favor, completa todos los campos.');
            return;
        }
    
        // Preparar los datos a enviar
        let postData = {
            name: name,
            description: description,
            user_id: user_id,
            projectId: projectId // En caso de edición, se pasa el id del proyecto
        };
    
        // Determinar la URL a la que se enviará el request dependiendo si es creación o edición
        const url = projectId ? '../../backend/project-edit.php' : '../../backend/project-add.php';
    
        // Enviar los datos mediante AJAX
        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(postData),
            success: function (response) {
                let respuesta = JSON.parse(response);
                console.log("Respuesta del servidor:", respuesta);
    
                // Comprobar si la respuesta es exitosa
                if (respuesta.status === 'success') {
                    alert(respuesta.message);
                    listarProyectos(); // Refrescar la lista de proyectos
                    $('#project-form')[0].reset(); // Limpiar el formulario
                    $('#projectId').val(''); // Limpiar el campo oculto del ID
                    edit = false; // Reiniciar la bandera de edición
                } else {
                    alert('Error: ' + respuesta.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error AJAX:', status, error);
                alert('Error al comunicarse con el servidor.');
            }
        });
    });

    listarProyectos();

    $(document).on('click', '.project-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el proyecto?')) {
            const element = $(e.currentTarget).closest('tr'); 
            const id = $(element).attr('projectId');
            $.ajax({
                url: '../../backend/project-delete.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id: id }),
                success: function(response) {
                    console.log("Respuesta del servidor:", response);
                    listarProyectos();
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', status, error);
                }
            });
        }
    });

    listarProyectos();

    $(document).on('click', '.project-item', (e) => {
        let row = $(this)[0].parentElement.parentElement;
        const id = $(element).attr('projectId');
        $.post('../../backend/project-single.php', {id}, (response) => {
            edit = true;
            // SE CONVIERTE A OBJETO EL JSON OBTENIDO
            let project = JSON.parse(response);
            // SE INSERTAN LOS DATOS ESPECIALES EN LOS CAMPOS CORRESPONDIENTES
            $('#projectId').val(project['id']);
            $('#name').val(project['name']);
            $('#description').val(project['description']);

            $('#botonFormulario').html(edit === false ? 'Agregar producto' : 'Editar producto');
        });
        e.preventDefault();
    });
    
    document.addEventListener("DOMContentLoaded", () => {
        // Rellenar campos con datos del usuario
        if (userData) {
            document.getElementById("username").value = userData.username;
            document.getElementById("email").value = userData.email;
            document.getElementById("ubicacion").value = userData.ubicacion || "No especificado";
            document.getElementById("company").value = userData.company || "No especificado";
        }
    });

    
    
});