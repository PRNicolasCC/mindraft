<?php

/* foreach($data as $notebook){
    echo $notebook;
} */

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
                    <button class="btn" id="new-notebook-btn" data-bs-toggle="modal" data-bs-target="#modal-overlay">
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
            foreach ($data as $index => $notebook) {
                $children .= '
                    <!-- <div class="notebook-card border-'.$notebook['color'].'">
                    <div class="notebook-header">
                        <div class="notebook-icon bg-'.$notebook['color'].'">
                            <i data-lucide="book" size="24"></i>
                        </div>
                        <div style="flex: 1;">
                            <h3 class="notebook-title">'.$notebook['nombre'].'</h3>
                        </div>
                    </div> -->

                    <div class="notebook-card border-primary">
                    <div class="notebook-header">
                        <div class="notebook-icon bg-primary">
                            <i data-lucide="book" size="24"></i>
                        </div>
                        <div style="flex: 1;">
                            <h3 class="notebook-title">'.$notebook['nombre'].'</h3>
                        </div>
                    </div>
                    
                    <p class="notebook-description">'. 'una descripción' .'</p>
                    
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
                        <button class="btn-edit" data-id="'.$notebook['id'].'">
                            <i data-lucide="edit-2" size="16"></i>
                            Editar
                        </button>
                        <button class="btn-danger" data-id="'.$notebook['id'].'">
                            <i data-lucide="trash-2" size="16"></i>
                            Eliminar
                        </button>
                    </div>
                </div>';
            }    
        }    
$children .= '</div>
    </div>

    <div class="modal fade" id="modal-overlay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo cuaderno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

            <form method="post" action="" id="notebook-form">
            <input type="hidden" name="csrf_token" value="'.htmlspecialchars(SessionManager::get('csrf_token')).'">
                <div class="form-floating">
                    <input
                        type="text"
                        name="nombre"
                        id="form-title"
                        class="form-control border border-warning"
                        placeholder=" "
                        required
                        maxLength="50"
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
                        required
                        maxLength="200"
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
                </div>
            </form>
        </div>
        </div>
        </div>
    </div>
    
    <script src="public/js/notebook/index.js"></script>
    <script>
        lucide.createIcons();
    </script>';

require_once 'public/views/index.php';

?>