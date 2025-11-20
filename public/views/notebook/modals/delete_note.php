<?php

    $deleteNote = '<input type="hidden" name="_method" value="DELETE">
                <input type="hidden" id="noteDeleteId" name="id">
                <input type="hidden" id="notebookIdNoteDelete" name="cuaderno_id">
                <p>Se eliminará la nota y toda su información</p>
                <br>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="delete_note" class="btn-danger">
                        Eliminar Nota
                    </button>
                </div>';

    $children .= $this->formModal('modal-delete-note', '¿Estás seguro de eliminar esta nota?', $deleteNote, 'delete-note-form');
?>