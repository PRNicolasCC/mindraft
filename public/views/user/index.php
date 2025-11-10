<?php

$propsAuth = array(
    'title' => 'Iniciar Sesión',
    'subtitle' => 'Ingresa tus credenciales para acceder a tu cuenta',
    'sendButton' => 'Ingresar',
);

$childrenAuth = '
            <div class="form-floating">
                <input type="hidden" name="csrf_token" value="'.htmlspecialchars(SessionManager::get('csrf_token')).'">
                <input
                    type="email"
                    name="emailLogin"
                    id="email"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                    autofocus
                />
                <label for="email" id="labelName" class="fs-5">Email</label>
            </div>

            <div class="form-floating password-field">
                <input
                    type="password"
                    name="passwordLogin"
                    id="password"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                />
                <label for="password" id="labelPassword" class="fs-5">Contraseña</label>
                <button 
                    type="button" 
                    class="password-toggle" 
                    onclick="togglePassword(\'password\', \'eyeIcon\')"
                    aria-label="Mostrar u ocultar contraseña"
                    tabindex="-1"
                >
                    <i class="fa-solid fa-eye-slash" id="eyeIcon"></i>
                </button>
            </div>';

$aditionalAuth = '<div class="text-center p-5">
                <p><a href="'.htmlspecialchars($_ENV['DOMAIN']).'/password" target="_blank" rel="noopener noreferrer" class="link_general">¿Olvidaste la contraseña?</a></p>
                <!-- <br>
                <p><div class="link_register px-3 py-2 rounded-1"><a href="'.htmlspecialchars($_ENV['DOMAIN']).'/user" class="link_register px-3">Crear cuenta nueva</a></div></p> -->
            </div>';

$scriptsAuth = '<script src="public/js/user/index.js"></script>
    <script src="public/js/user/login.js"></script>';

require_once 'public/views/form.php';

?>