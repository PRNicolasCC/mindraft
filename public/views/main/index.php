<?php

$propsAuth = [
    'title' => 'Bienvenido',
    'subtitle' => 'Panel de Control',
    'sendButton' => '',
];

$childrenAuth = '
            <div class="welcome-container">
                <div class="welcome-header text-center mb-4">
                    <div class="avatar-circle mb-3">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3 class="mb-2">¡Hola, '.SessionManager::get('user')['nombre'].'!</h3>
                    <p class="text-muted">'.SessionManager::get('user')['email'].'</p>
                </div>

                <!-- <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-chart-line fs-1 text-warning mb-3"></i>
                                <h5 class="card-title">Estadísticas</h5>
                                <p class="card-text text-muted">Ver tu progreso y métricas</p>
                                <a href="#" class="btn btn-outline-warning btn-sm">Ver más</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-gear fs-1 text-warning mb-3"></i>
                                <h5 class="card-title">Configuración</h5>
                                <p class="card-text text-muted">Personaliza tu cuenta</p>
                                <a href="#" class="btn btn-outline-warning btn-sm">Configurar</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-bell fs-1 text-warning mb-3"></i>
                                <h5 class="card-title">Notificaciones</h5>
                                <p class="card-text text-muted">Revisa tus alertas</p>
                                <a href="#" class="btn btn-outline-warning btn-sm">Ver todo</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-circle-info fs-1 text-warning mb-3"></i>
                                <h5 class="card-title">Ayuda</h5>
                                <p class="card-text text-muted">Soporte y guías</p>
                                <a href="#" class="btn btn-outline-warning btn-sm">Obtener ayuda</a>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-3"></i>
                    <div>
                        <strong>Consejo:</strong> Completa tu perfil para aprovechar todas las funciones.
                    </div>
                </div> -->
            </div>';

$aditionalAuth = '<div class="text-center pt-4">
    <form method="POST" action="" class="d-inline">
        <input type="hidden" name="csrf_token" value="'.htmlspecialchars(SessionManager::get('csrf_token')).'">
        <input type="hidden" name="logout" value="1">
        <button type="submit" class="btn btn-outline-danger">
            <i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar Sesión
        </button>
    </form>
</div>';

$scriptsAuth = '';

require_once 'public/views/form.php';

?>