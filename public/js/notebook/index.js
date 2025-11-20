$(document).ready(function() {
    const quill = new Quill('#editorQuill', {
        theme: 'snow'
    });

    $('#create-note-form').submit(function() {
        var contenidoDiv = $("#editorQuill").html();    
        $("#contenido-obs").val(contenidoDiv);  
    });

    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
});