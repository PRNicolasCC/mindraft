<?php

$children = '
    <div class="dashboard-header">
        <div class="dashboard-container">
            <div class="dashboard-header-content d-flex">
                <div>
                    <h1 class="m-0 fs-2">Mis Cuadernos</h1>
                    <p class="m-0 opacity-9" id="notebook-count-text">
                        </p>
                </div>
                <div>
                    <button class="btn" id="new-notebook-btn" data-bs-toggle="modal" data-bs-target="#modal-create">
                        <i data-lucide="plus" size="20"></i>
                        Nuevo Cuaderno
                    </button>
                </div>
            </div>
        </div>
    </div>';

    $children .= $this->getDescriptionMessage();

    $children .= '<div class="dashboard-container d-flex flex-column">
        <div class="dashboard-search-container">
            <i data-lucide="search" class="search-icon" size="20"></i>
            <input
                type="text"
                class="dashboard-search-input"
                id="search-input"
                placeholder="Buscar cuadernos..."
            />
        </div>
        
        <!-- <div class="notebooks-grid" id="notebooks-grid"> -->
        <div class="notebooks-grid">';

        if (!empty($data)) {
            foreach ($data as $notebook) {
                $children .= '
                    <div class="notebook-card" style="border-color: '.$notebook['color'].'">
                    <div class="notebook-header">
                        <div class="notebook-icon" style="background-color: '.$notebook['color'].'">
                            <i data-lucide="book" size="24"></i>
                        </div>
                        <div style="flex: 1;">
                            <h3 class="notebook-title">'.$notebook['nombre'].'</h3>
                        </div>
                    </div>
                    
                    <p class="notebook-description">'.$notebook['descripcion'].'</p>
                    
                    <div class="notebook-meta">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i data-lucide="file-text" size="16"></i>
                            <span>0 notas</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i data-lucide="calendar" size="16"></i>
                            <span>'.$notebook['ultima_modificacion'] .'</span>
                        </div>
                    </div>

                    <div class="notebook-actions">
                        <button class="btn-edit" data-id="'.$notebook['id'].'" data-nombre="'.$notebook['nombre'].'" data-descripcion="'.$notebook['descripcion'].'" data-color="'.$notebook['color'].'" data-bs-toggle="modal" data-bs-target="#modal-edit">
                            <i data-lucide="edit-2" size="16"></i>
                            Editar
                        </button>
                        <button class="btn-danger" data-id="'.$notebook['id'].'" data-bs-toggle="modal" data-bs-target="#modal-delete">
                            <i data-lucide="trash-2" size="16"></i>
                            Eliminar
                        </button>
                    </div>
                </div>';
            }    
        }    


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
                    <button type="submit" name="create_notebook" class="btn-primary">
                        Crear Cuaderno
                    </button>
                </div>';
    $children .= $this->modal('modal-create', 'Nuevo cuaderno', $formCreate, 'create-notebook-form');
    

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
    $children .= $this->modal('modal-edit', 'Editar cuaderno', $formEdit, 'edit-notebook-form');

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
    $children .= $this->modal('modal-delete', '¿Estás seguro de eliminar este cuaderno?', $formDelete, 'delete-notebook-form');
    
    $children .= '<script src="public/js/notebook/modal.js"></script>
    <script src="public/js/notebook/index.js"></script>
    <script>
        lucide.createIcons();
    </script>';

require_once 'public/views/index.php';

?>