<?php

    $formCreate = '<div class="form-floating">
                    <input
                        type="text"
                        name="nombre"
                        id="form-title"
                        class="form-control border border-warning"
                        placeholder=" "
                        required
                        maxLength="75"
                        autofocus
                    />
                    <label for="form-title">Título del cuaderno</label>
                </div>

                <div class="form-floating">
                    <textarea
                        name="descripcion"
                        id="form-description"
                        class="form-control border border-warning"
                        placeholder="Descripción"
                        maxLength="100"
                    ></textarea>
                    
                </div>

                <div class="form-floating">
                    <input
                        type="color"
                        name="color"
                        id="form-color"
                        class="form-control border border-warning"
                        required
                    />
                    <label for="form-color" class="text-dark">Color del cuaderno</label>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="create_notebook" class="btn-primary" id="btnCreateNotebook">
                        Crear Cuaderno
                    </button>
                </div>';
    $children .= $this->formModal('modal-create', 'Nuevo cuaderno', $formCreate, 'create-notebook-form');
    ?>