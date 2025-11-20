<?php

$children = '
    <div class="dashboard-header">
        <div class="dashboard-container">
            <div class="d-flex justify-content-between align-items-center gap-1">
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

                <div>
                    <form action="" method="post">
                        <input type="hidden" name="csrf_token" value="'.SessionManager::get('csrf_token').'">
                        <input type="hidden" name="logout" value="1" required>
                        <button type="submit" class="btn-danger">
                            <i data-lucide="log-out" size="20"></i>
                            Cerrar Sesión
                        </button>
                    </form>
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
                        <button class="btn-primary"
                            id="button-notes"
                            data-id="'.$notebook['id'].'" 
                            data-nombre="'.$notebook['nombre'].'" 
                            data-color="'.$notebook['color'].'" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modal-notes"
                        >
                            <i data-lucide="file-text" size="16"></i>
                            Ver Notas
                        </button>
                        <button class="btn-edit" 
                            data-id="'.$notebook['id'].'" 
                            data-nombre="'.$notebook['nombre'].'" 
                            data-descripcion="'.$notebook['descripcion'].'" 
                            data-color="'.$notebook['color'].'" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modal-edit"
                        >
                            <i data-lucide="edit-2" size="16"></i>
                            Editar Cuaderno
                        </button>
                        <button class="btn-danger" 
                            data-id="'.$notebook['id'].'" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modal-delete"
                        >
                            <i data-lucide="trash-2" size="16"></i>
                            Eliminar Cuaderno
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
                    <button type="submit" name="create_notebook" class="btn-primary" id="btnCreateNotebook">
                        Crear Cuaderno
                    </button>
                </div>';
    $children .= $this->formModal('modal-create', 'Nuevo cuaderno', $formCreate, 'create-notebook-form');

    $notesList = '<div id="notes-list" class=""></div>';
    $children .= $this->modal('modal-notes', $notesList);
    
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

    $createNote = '
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
                    <div id="editorQuill" name="obs"></div>
                </div>
                <input type="hidden" id="notebookIdNote" name="cuaderno_id">

                <!-- Campo para recibir el contenido del editor Quill.
                    Es el que se tiene que utilizar en el controlador -->
                <input type="hidden" name="observacion" id="contenido-obs">

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" 
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="create_note" class="btn-primary">
                        Crear Nota
                    </button>
                </div>';
    $children .= $this->formModal('modal-create-note', 'Nueva nota', $createNote, 'create-note-form');
    
    $children .= '<script src="public/js/notebook/modal.js"></script>
    <script src="public/js/notebook/note.js"></script>
    
    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    
    <script src="public/js/notebook/index.js"></script>';

require_once 'public/views/index.php';

?>