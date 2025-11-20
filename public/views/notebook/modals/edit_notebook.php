<?php

    $formEdit = '<input type="hidden" name="_method" value="PUT">
                <div class="form-floating">
                    <input
                        type="text"
                        name="nombre"
                        id="editNombre"
                        class="form-control border border-warning"
                        placeholder=" "
                        required
                        maxLength="75"
                        autofocus
                    />
                    <label for="editNombre">Título del cuaderno</label>
                </div>

                <div class="form-floating">
                    <textarea
                        name="descripcion"
                        id="editDescripcion"
                        class="form-control border border-warning"
                        placeholder="Descripción"
                        maxLength="100"
                    ></textarea>
                    
                </div>

                <div class="form-floating">
                    <input
                        type="color"
                        name="color"
                        id="editColor"
                        class="form-control border border-warning"
                        required
                    />
                    <label for="editColor" class="text-dark">Color del cuaderno</label>
                </div>

                <input type="hidden" id="notebookId" name="id">

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="edit_notebook" class="btn-primary">
                        Actualizar Cuaderno
                    </button>
                </div>';
    $children .= $this->formModal('modal-edit', 'Editar cuaderno', $formEdit, 'edit-notebook-form');
?>