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

$aditionalAuth = '<div class="text-center p-5">
                <p><a href="user/password" target="_blank" rel="noopener noreferrer" class="link_general">¿Olvidaste la contraseña?</a></p>
                <p><div class="link_register px-3 py-2 rounded-1"><a href="user" class="link_register px-3">Crear cuenta nueva</a></div></p>
            </div>';

$scriptsAuth = '<script src="public/js/user/index.js"></script>
    <script src="public/js/user/login.js"></script>';

require_once 'public/views/form.php';

?>