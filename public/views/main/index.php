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
                    <button class="btn" id="new-notebook-btn">
                        <i data-lucide="plus" size="20"></i>
                        Nuevo Cuaderno
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-container d-flex flex-column">
        <div class="dashboard-search-container">
            <i data-lucide="search" class="search-icon" size="20"></i>
            <input
                type="text"
                class="dashboard-search-input"
                id="search-input"
                placeholder="Buscar cuadernos..."
            />
        </div>
        
        <div class="notebooks-grid" id="notebooks-grid">
            </div>

        <div class="empty-state hidden" id="empty-state">
            <i data-lucide="book" size="64" stroke-width="1"></i>
            <h3>No se encontraron cuadernos</h3>
            <p>Intenta con otra búsqueda o crea un nuevo cuaderno</p>
        </div>
    </div>

    <div class="modal-overlay hidden" id="modal-overlay">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal-title">Nuevo Cuaderno</h2>
            </div>

            <form id="notebook-form">
                <div class="form-floating">
                    <input
                        type="text"
                        name="title"
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
                        name="description"
                        id="form-description"
                        class="form-control border border-warning"
                        placeholder=" "
                        required
                        maxLength="200"
                    ></textarea>
                    <label for="form-description">Descripción</label>
                </div>

                <div class="color-selector">
                    <label class="position-static text-dark fw-medium">
                        Color del cuaderno
                    </label>
                    <div class="color-options" id="color-options-container">
                        </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="modal-cancel-btn">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary" id="modal-submit-btn">
                        Crear Cuaderno
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="public/js/main/index.js"></script>';

/* $aditionalAuth = '<div class="text-center pt-4">
    <form method="POST" action="" class="d-inline">
        <input type="hidden" name="csrf_token" value="'.htmlspecialchars(SessionManager::get('csrf_token')).'">
        <input type="hidden" name="logout" value="1">
        <button type="submit" class="btn btn-outline-danger">
            <i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar Sesión
        </button>
    </form>
</div>'; */

#$scriptsAuth = '<script src="public/js/main/index.js"></script>';

#require_once 'public/views/form.php';
require_once 'public/views/index.php';

?>