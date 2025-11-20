<?php

    $formDelete = '<input type="hidden" name="_method" value="DELETE">
                <input type="hidden" id="notebookDeleteId" name="id">
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="delete_notebook" class="btn-danger">
                        Eliminar Cuaderno
                    </button>
                </div>';
    $children .= $this->formModal('modal-delete', '¿Estás seguro de eliminar este cuaderno?', $formDelete, 'delete-notebook-form');

    ?>