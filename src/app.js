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
                                <a href="#" style="cursor: pointer;">${project.nombre}</a>                            
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
                url: '../../backend/project-search.php',
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
                                        <td>
                                        <a href="#" style="cursor: pointer;">${project.nombre}</a>                            
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
                            // SE HACE VISIBLE LA BARRA DE ESTADO
                            //$('#project-result').show();
                            // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                            document.getElementById("projects").innerHTML = template;
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
            contentType: 'application/json',
            data: JSON.stringify({ name, description, user_id }),
            success: function (response) {
                let respuesta = JSON.parse(response);
                console.log("Respuesta del servidor:", respuesta);
                // La respuesta ya es un objeto, no necesitas JSON.parse()
                if (respuesta.status === 'success') {
                    alert(respuesta.message);    
                    listarProyectos();
                    edit = false;
                    $('#project-form')[0].reset();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error AJAX:', status, error);
                alert('Error al comunicarse con el servidor.');
            }
        });
    });

    $('#project-form').submit(e => {
        e.preventDefault();

        // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
        postData['nombre'] = $('#name').val();
        postData['id'] = $('#productId').val();
        postData['descripcion'] = $('#description').val();

        const url = edit === false ? '../../backend/project-add.php' : '../../backend/project-edit.php';
        
        $.post(url, postData, (response) => {
            console.log(response);
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let respuesta = JSON.parse(response);
            // SE MUESTRA UN MENSAJE DE ÉXITO O DE ERROR
            alert(respuesta.message);
            $('#name').val('');
            $('#description').val('');

            // SE HACE VISIBLE LA BARRA DE ESTADO
            $('#project-result').show();
            
            // SE LISTAN TODOS LOS PRODUCTOS
            listarProyectos();
            // SE REGRESA LA BANDERA DE EDICIÓN A false
            edit = false;
        });
    });



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
        e.preventDefault();
        const element = $(e.currentTarget).closest('tr'); 
        const id = $(element).attr('projectId');

        $.post('../../backend/project-single.php', {id: id}, (response) => {
            edit = true;
            // SE CONVIERTE A OBJETO EL JSON OBTENIDO
            let project = JSON.parse(response);
            console.log("Proyecto seleccionado:", project);
            // SE INSERTAN LOS DATOS ESPECIALES EN LOS CAMPOS CORRESPONDIENTES
            $('#projectId').val(project.id);
            $('#name').val(project.nombre);
            $('#description').val(project.descripcion);

            botonAgregar();
        });
        
    });

    function botonAgregar(){
        console.log(edit);
        if(edit){
            $('#botonFormulario').text('Agregar proyecto');
        }else{
            $('#botonFormulario').text('Editar proyecto');
        }
    }
    
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