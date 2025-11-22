<?php

$propsAuth = [
    'title' => 'Iniciar Sesión',
    'subtitle' => 'Ingresa tus credenciales para acceder a tu cuenta',
    'sendButton' => 'Ingresar',
];

$previousEmail = SessionManager::has('redirectInputs') ? SessionManager::get('redirectInputs')['email'] : '';

$childrenAuth = '
            <div class="form-floating">
                <input
                    type="email"
                    name="emailLogin"
                    id="email"
                    class="form-control border border-warning"
                    value="'.htmlspecialchars($previousEmail) .'"
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
                </div>
                
                <input type="hidden" name="login" value="1" required>';

$aditionalAuth = '<div class="text-center pt-5">
                <p><a href="password" target="_blank" rel="noopener noreferrer" class="link_general">¿Olvidaste la contraseña?</a></p>
                <p><a href="user"><button type="button" class="link_register px-3 py-2 rounded-1">Crear cuenta nueva</button></a></p>
            </div>';

$scriptsAuth = '<script src="public/js/auth/index.js"></script>';

require_once BASE_PATH . 'public/views/form.php';

?>