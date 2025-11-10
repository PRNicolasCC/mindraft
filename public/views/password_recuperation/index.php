<?php

$propsAuth = array(
    'title' => '¿Olvidaste tu contraseña?',
    'subtitle' => 'No te preocupes, te ayudamos a recuperarla',
    'sendButton' => 'Enviar enlace de recuperación',
);

$childrenAuth = '
            <div class="form-floating">
                <input type="hidden" name="csrf_token" value="'.htmlspecialchars(SessionManager::get('csrf_token')).'">
                <input
                    type="email"
                    name="emailRecuperacion"
                    id="email"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                    autofocus
                    maxlength="75"
                />
                <label for="email" id="labelEmail" class="fs-5">Email registrado</label>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-info-circle"></i>
                <p>Te enviaremos un enlace seguro a tu email para que puedas crear una nueva contraseña.</p>
            </div>';

    $aditionalAuth = '
        <div class="navigation-links">
            <a href="index.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Volver al inicio de sesión
            </a>
            <div class="divider">•</div>
            <a href="'.htmlspecialchars($_ENV['DOMAIN']).'/user" class="register-link">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>';

    $scriptsAuth = '<script src="public/js/password_recuperation/index.js"></script>';

    require_once 'public/views/form.php';
?>