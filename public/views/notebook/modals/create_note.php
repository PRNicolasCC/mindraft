<?php
    $createNote = '
                <div class="form-floating">
                    <input
                        type="text"
                        name="nombre"
                        id="createNotaNombre"
                        class="form-control border border-warning"
                        placeholder=" "
                        required
                        maxLength="75"
                        autofocus
                    />
                    <label for="createNotaNombre">Título de la nota</label>
                </div>

                <div class="form-floating">
                    <div id="editorQuill" name="obs"></div>
                </div>

                <!-- Campo para recibir el contenido del editor Quill.
                    Es el que se tiene que utilizar en el controlador -->
                <input type="hidden" name="observacion" id="contenido-obs">

                <input type="hidden" id="notebookIdNote" name="cuaderno_id">

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" 
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="create_note" class="btn-primary">
                        Crear Nota
                    </button>
                </div>';
    $children .= $this->formModal('modal-create-note', 'Nueva nota', $createNote, 'create-note-form', 'modal-lg');
?>