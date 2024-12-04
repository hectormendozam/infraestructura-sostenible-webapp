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
                            <tr>
                                <td>${project.id}</td>
                                <td>${project.nombre}</td>
                                <td>${project.descripcion}</td>
                            <td>
                                <button class="project-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
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


    
        if (!name || !description) {
            alert('Por favor, completa todos los campos.');
            return;
        }
    
        $.ajax({
            url: '../../backend/project-add.php',
            type: 'POST',
            data: { name, description, user_id},
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

    $(document).on('click', '.project-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el proyecto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('projectId');
            $.post('../../backend/project-delete.php', {id}, (response) => {
                listarProductos();
            });
        }
    });

    $(document).on('click', '.project-item', (e) => {
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('projectId');
        $.post('../../backend/project-single.php', {id}, (response) => {
            // SE CONVIERTE A OBJETO EL JSON OBTENIDO
            let project = JSON.parse(response);
            // SE INSERTAN LOS DATOS ESPECIALES EN LOS CAMPOS CORRESPONDIENTES
            $('#name').val(project.nombre);
            $('#description').val(project.descripcion);
            // EL ID SE INSERTA EN UN CAMPO OCULTO PARA USARLO DESPUÉS PARA LA ACTUALIZACIÓN
            $('#projectId').val(project.id);
            
            // SE PONE LA BANDERA DE EDICIÓN EN true
            edit = true;
        });
        e.preventDefault();
    });
    
    document.addEventListener("DOMContentLoaded", () => {
        // Rellenar campos con datos del usuario
        if (userData) {
            document.getElementById("username").value = userData.username;
            document.getElementById("email").value = userData.email;
            document.getElementById("location").value = userData.location || "No especificado";
            document.getElementById("company").value = userData.company || "No especificado";
        }
    });
    
});