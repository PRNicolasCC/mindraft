// Espera a que el documento esté completamente cargado
$(document).ready(function() {
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
    
    const quill = new Quill('#editorQuill', {
        theme: 'snow'
    });

    const quillEdit = new Quill('#editorQuillEdit', {
        theme: 'snow'
    });

    $("#modal-notes").on("show.bs.modal", function (event) {
        const button = $(event.relatedTarget);
        const id = button.data("id");
        const nombre = button.data("nombre");
        const modal = $(this);
        modal.find("#modalTitle").text(nombre);

        $("#notes-list").html("<p>Cargando datos...</p>");
        
        // La función principal de jQuery para la solicitud AJAX
        $.ajax({
            // URL del script de PHP que nos dará los datos
            url: 'note/content/'+id, 
            // Método de la solicitud (puede ser 'GET' o 'POST')
            type: 'GET',
            // Tipo de datos que esperamos recibir de PHP
            dataType: 'json', 
            
            // Función que se ejecuta si la solicitud es exitosa
            success: function(datosRecibidos) {
                // 'datosRecibidos' es ahora un objeto JavaScript gracias a 'dataType: "json"'
                
                $("#notes-list").html("<button class='btn-primary' data-id='"+id+"' data-bs-toggle='modal' data-bs-target='#modal-create-note'><i data-lucide='plus'></i>Agregar Nota</button><br><br>");
                if (datosRecibidos.length === 0) {
                    $("#notes-list").append("<p>No hay notas registradas para este cuaderno</p>");
                } else {       
                    let html_contenido = '<table class="table table-striped table-hover"><thead><tr><th scope="col">Nota</th><th scope="col">Última modificación</th><th scope="col">Acciones</th></tr></thead><tbody>';     
                    datosRecibidos.forEach(element => {
                        html_contenido += `                        
                        <tr>
                            <th scope="row">${element.nombre}</th>
                            <td>${element.fecha}</td>
                            <td class="d-flex gap-1 justify-content-evenly">
                                <button class="btn-edit w-25 d-flex align-items-center" data-id="${element.id}" data-nombre="${element.nombre}" data-cuaderno-id="${element.cuaderno_id}" data-bs-toggle="modal" data-bs-target="#modal-edit-note"><i data-lucide="edit-2" size="16"></i></button>
                                <button class="btn-danger w-25 d-flex align-items-center" data-id="${element.id}" data-cuaderno-id="${element.cuaderno_id}" data-bs-toggle="modal" data-bs-target="#modal-delete-note"><i data-lucide="trash-2" size="16"></i></button>
                            </td>
                        </tr>`;
                    });
                    html_contenido += '</tbody></table>';
                    // Mostrar el resultado en el div
                    $("#notes-list").append(html_contenido);
                    lucide.createIcons();
                }
            },
            
            // Función que se ejecuta si hay un error en la solicitud
            error: function(xhr, status, error) {
                // Mostrar el error si algo falla
                $("#notes-list").html("<p>Error al cargar los datos: " + status + " (" + error + ")</p>");
                console.error("Error AJAX:", status, error);
            }
        });
    });

    $("#modal-create-note").on("show.bs.modal", function(event) {
        const button = $(event.relatedTarget);
        const id = button.data("id");
        
        const modal = $(this);
        modal.find("#notebookIdNote").val(id);
    });

    $("#modal-edit-note").on("show.bs.modal", function(event) {

        $('#create-note-form').submit(function() {
            //var contenidoDiv = $("#editorQuill").html();
            var contenidoDiv = quill.getContents(); // Obtener el contenido como Delta y no como HTML
            const contenidoDeltaJSON = JSON.stringify(contenidoDiv);
            $("#contenido-obs").val(contenidoDeltaJSON);  
        });

        $('#edit-note-form').submit(function() {
            var contenidoDiv = quillEdit.getContents();
            // Esto es necesario para guardar el objeto en un campo de texto de la base de datos
            const contenidoDeltaJSON = JSON.stringify(contenidoDiv);
            $("#contenido-obs-edit").val(contenidoDeltaJSON);  
        });

        const button = $(event.relatedTarget);
        const id = button.data("id");
        const idCuaderno = button.data("cuaderno-id");
        const nombre = button.data("nombre");
        const modal = $(this);
            modal.find("#notebookIdNote").val(idCuaderno);
            modal.find("#editNotaNombre").val(nombre);
            modal.find("#noteIdNote").val(id);

            //$("#editorQuillEdit").html("<p>Cargando datos...</p>");
            
            // La función principal de jQuery para la solicitud AJAX
            $.ajax({
                // URL del script de PHP que nos dará los datos
                url: 'note/detail/'+idCuaderno+'/'+id, 
                // Método de la solicitud (puede ser 'GET' o 'POST')
                type: 'GET',
                // Tipo de datos que esperamos recibir de PHP
                dataType: 'json', 
                
                // Función que se ejecuta si la solicitud es exitosa
                success: function(datosRecibidos) {
                    // 'datosRecibidos' es ahora un objeto JavaScript gracias a 'dataType: "json"'
                    
                    /* if (datosRecibidos.length === 0) {
                        $("#editorQuillEdit").html("<p>No se ha encontrado información para la nota</p>");
                    } else {
                        // Mostrar el resultado en el div
                        $("#editorQuillEdit").html(datosRecibidos.descripcion);
                        lucide.createIcons();
                    } */

                    // Inserta el HTML (ya sanitizado) en la posición 0 (el inicio del editor)
                    //quillEdit.clipboard.dangerouslyPasteHTML(0, datosRecibidos.descripcion);

                    quillEdit.setContents([
                        { insert: '\n' } // Esto asegura que Quill tenga al menos un párrafo vacío para escribir a fin de que no se duplique la descripción de una nota anterior en dado caso de que la presente esté vacía.
                    ]);
                    const deltaObjeto = JSON.parse(datosRecibidos.descripcion);
                    quillEdit.setContents(deltaObjeto);
                },
                
                // Función que se ejecuta si hay un error en la solicitud
                error: function(xhr, status, error) {
                    // Mostrar el error si algo falla
                    //$("#editorQuillEdit").html("<p>Error al cargar los datos: " + status + " (" + error + ")</p>");
                    quillEdit.clipboard.dangerouslyPasteHTML(0, "Error al cargar los datos: " + status + " (" + error + ")");
                    console.error("Error AJAX:", status, error);
                }
            });
        });

    $('#modal-delete-note').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');    
        const idCuaderno = button.data('cuaderno-id');    
        const modal = $(this);
        modal.find('#noteDeleteId').val(id);
        modal.find('#notebookIdNoteDelete').val(idCuaderno);
    });
});
