<?php

$propsAuth = [
    'title' => '¿Olvidaste tu contraseña?',
    'subtitle' => 'No te preocupes, te ayudamos a recuperarla',
    'sendButton' => 'Enviar enlace de recuperación',
];

$previousEmail = SessionManager::has('redirectInputs') ? SessionManager::get('redirectInputs')['email'] : '';

$childrenAuth = '
            <div class="form-floating">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                    autofocus
                    maxlength="75"
                    value="'.htmlspecialchars($previousEmail) .'"
                />
                <label for="email" id="labelEmail" class="fs-5">Email registrado</label>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-info-circle"></i>
                <p>Te enviaremos un enlace seguro a tu email para que puedas crear una nueva contraseña.</p>
            </div>
            
            <input type="hidden" name="password_reset" value="1" required>';

$aditionalAuth = '
        <div class="navigation-links">
            <a href="auth" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Volver al inicio de sesión
            </a>
            <div class="divider">•</div>
            <a href="user" class="register-link">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>';

$scriptsAuth = '<script src="public/js/password/index.js"></script>';

require_once 'public/views/form.php';
?>