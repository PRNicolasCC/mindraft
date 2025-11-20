<?php

    $editNote = '<input type="hidden" name="_method" value="PUT">
                <div class="form-floating">
                    <input
                        type="text"
                        name="nombre"
                        id="editNotaNombre"
                        class="form-control border border-warning"
                        placeholder=" "
                        required
                        maxLength="75"
                        autofocus
                    />
                    <label for="editNotaNombre">Título de la nota</label>
                </div>

                <div class="form-floating">
                    <div id="editorQuillEdit" name="obs-edit" contenteditable="true"></div>
                </div>

                <!-- Campo para recibir el contenido del editor Quill.
                    Es el que se tiene que utilizar en el controlador -->
                <input type="hidden" name="observacion" id="contenido-obs-edit">

                <input type="hidden" id="noteIdNote" name="id">
                <input type="hidden" id="notebookIdNote" name="cuaderno_id">

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="edit_note" class="btn-primary">
                        Actualizar Nota
                    </button>
                </div>';
    $children .= $this->formModal('modal-edit-note', 'Editar Nota', $editNote, 'edit-note-form', 'modal-lg');
?>