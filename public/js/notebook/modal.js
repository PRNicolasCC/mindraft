$(document).ready(function() {
    $('#modal-edit').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const nombre = button.data('nombre');
        const id = button.data('id');
        const descripcion = button.data('descripcion');
        const color = button.data('color');
        
        const modal = $(this);
        modal.find('#notebookId').val(id);
        modal.find('#editNombre').val(nombre);
        modal.find('#editDescripcion').val(descripcion);
        modal.find('#editColor').val(color);
    });

    $('#modal-delete').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');    
        const modal = $(this);
        modal.find('#notebookDeleteId').val(id);
    });

    /* function capturarContenido() {
        var contenidoDiv = $("#editorQuill").html();    
        $("#contenido-obs").val(contenidoDiv);    
        //return true;
    } */
});