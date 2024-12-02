$(document).ready(function(){
    let edit = false;

    let JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    $('#project-result').hide();
    listarProyectos();

    function listarProyectos() {
        $.ajax({
            url: './backend/project-list.php',
            type: 'GET',
            success: function(response) {
                console.log(response);
                // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                const proyectos = JSON.parse(response);
            
                // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                if(Object.keys(proyectos).length > 0) {
                    // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                    let template = '';

                    proyectos.forEach(producto => {
                        // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCT   
                        template += `
                            <tr productId="${proyecto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="project-item">${proyecto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="project-delete btn btn-danger">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                    $('#proyectos').html(template);
                }
            }
        });
    }

    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/project-search.php?search='+$('#search').val(),
                data: {search},
                type: 'GET',
                success: function (response) {
                    if(!response.error) {
                        // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                        const proyectos = JSON.parse(response);
                        
                        // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                        if(Object.keys(proyectos).length > 0) {
                            // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                            let template = '';

                            proyectos.forEach(proyecto => {
                            
                                template += `
                                    <tr productId="${producto.id}">
                                        <td>${producto.id}</td>
                                        <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                        <td><ul>${proyecto.descripcion}</ul></td>
                                        <td>
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

    $(document).ready(function() {
        // Manejar el envío del formulario
        $('#project-form').on('submit', function(event) {
            event.preventDefault();  // Evita que el formulario se envíe de forma tradicional
    
            // Recoger los datos del formulario
            var name = $('#name').val();
            var description = $('#description').val();
    
            // Validar que los campos no estén vacíos
            if (name === '' || description === '') {
                alert('Por favor, complete todos los campos.');
                return;
            }
    
            // Enviar los datos al servidor mediante AJAX
            $.ajax({
                url: 'project-add.php',
                type: 'POST',
                data: {
                    nombre: name,
                    descripcion: description
                },
                success: function(response) {
                    // Manejar la respuesta del servidor
                    var responseData = JSON.parse(response);  // Asumimos que el servidor devuelve JSON
    
                    if (responseData.status === 'success') {
                        alert(responseData.message);
                        // Aquí puedes agregar el nuevo proyecto a la tabla sin recargar la página
                        var newRow = `
                            <tr>
                                <td>${responseData.id}</td>
                                <td>${name}</td>
                                <td>${description}</td>
                            </tr>
                        `;
                        $('#projects').append(newRow);
                        // Limpiar el formulario
                        $('#name').val('');
                        $('#description').val('');
                    } else {
                        alert(responseData.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Hubo un problema al agregar el proyecto.');
                }
            });
        });
    });

    $(document).on('click', '.project-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el proyecto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('projectId');
            $.post('./backend/project-delete.php', {id}, (response) => {
                listarProductos();
            });
        }
    });

    $(document).on('click', '.project-item', (e) => {
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('projectId');
        $.post('./backend/project-single.php', {id}, (response) => {
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