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

        require_once 'modals/create_notebook.php';
        require_once 'modals/edit_notebook.php';
        require_once 'modals/delete_notebook.php';
        require_once 'modals/list_notes.php';
        require_once 'modals/create_note.php';
        require_once 'modals/edit_note.php';
        //require_once 'modals/delete_note.php';
    
    $children .= '<script src="public/js/notebook/modal.js"></script>
    <script src="public/js/notebook/note.js"></script>
    
    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    
    <script src="public/js/notebook/index.js"></script>';

require_once 'public/views/index.php';

?>