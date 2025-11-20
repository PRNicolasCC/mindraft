// Espera a que el documento esté completamente cargado
$(document).ready(function() {
    //let idGeneral = null;
    $("#modal-notes").on("show.bs.modal", function (event) {
        const button = $(event.relatedTarget);
        const id = button.data("id");
        //idGeneral = id;
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
                    let html_contenido = '<table class="table table-striped table-hover"><thead><tr><th scope="col">Nota</th><th scope="col">Fecha</th><th scope="col">Acciones</th></tr></thead><tbody>';     
                    datosRecibidos.forEach(element => {
                        html_contenido += `                        
                        <tr>
                            <th scope="row">${element.nombre}</th>
                            <td>${element.fecha}</td>
                            <td class="d-flex gap-1 justify-content-evenly">
                                <button class="btn-edit w-25 d-flex align-items-center" data-id="${element.id}" data-bs-toggle="modal" data-bs-target="#modal-edit-note"><i data-lucide="edit-2" size="16"></i></button>
                                <button class="btn-danger w-25 d-flex align-items-center" data-id="${element.id}"><i data-lucide="trash-2" size="16" data-bs-toggle="modal" data-bs-target="#modal-delete-note"></i></button>
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
                $("#notes-list").html("<p>❌ Error al cargar los datos: " + status + " (" + error + ")</p>");
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
        const button = $(event.relatedTarget);
        const id = button.data("id");
        const modal = $(this);
        modal.find("#notebookIdNote").val(id);
    });
});
