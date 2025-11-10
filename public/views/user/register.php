<?php

$propsAuth = [
    'title' => 'Crear Cuenta',
    'subtitle' => 'Completa tus datos para registrarte',
    'sendButton' => 'Registrarse',
];

$previousEmail = isset($this->inputs['email']) ? $this->inputs['email'] : '';
$previousUsername = isset($this->inputs['username']) ? $this->inputs['username'] : '';

$childrenAuth = '
            <div class="form-floating">
                <input type="hidden" name="csrf_token" value="'.htmlspecialchars(SessionManager::get('csrf_token')).'">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control border border-warning"
                    value="'.htmlspecialchars($previousEmail) .'"
                    placeholder=""
                    required
                    autofocus
                    maxlength="75"
                />
                <label for="email" id="labelEmail" class="fs-5">Email</label>
            </div>

            <div class="form-floating">
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control border border-warning"
                    value="'.htmlspecialchars($previousUsername) .'"
                    placeholder=""
                    required
                    maxlength="50"
                />
                <label for="username" id="labelUsername" class="fs-5">Nombre de usuario</label>
            </div>

            <div class="form-floating password-field">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                    minlength="8"
                    maxlength="30"
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

            <div class="form-floating password-field">
                <input
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                />
                <label for="confirm_password" id="labelConfirmPassword" class="fs-5">Confirmar contraseña</label>
                <button 
                    type="button" 
                    class="password-toggle" 
                    onclick="togglePassword(\'confirm_password\', \'eyeIconConfirm\')"
                    aria-label="Mostrar u ocultar contraseña de confirmación"
                    tabindex="-1"
                >
                    <i class="fa-solid fa-eye-slash" id="eyeIconConfirm"></i>
                </button>
            </div>

            <!-- Indicador de fortaleza de contraseña -->
            <div class="password-strength" id="passwordStrength">
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <p class="strength-text" id="strengthText"></p>
            </div>

            <!-- Términos y condiciones -->
            <!-- <div class="terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    Acepto los <a href="#" target="_blank">términos y condiciones</a> y la <a href="#" target="_blank">política de privacidad</a>
                </label>
            </div> -->

            <!-- Input para enviar como remplazo del boton de envío al index.
             Esto sucede porque new FormData(form) solo recopila los datos de 
             los campos (input, select, textarea), pero no incluye el botón de submit que inició el evento. -->
            <input type="hidden" name="create_user" value="1" required>

            <!-- <button type="submit" class="btn btn-register" id="sendButton">
                <span class="btn-text">Crear Cuenta</span>
                <div class="spinner" id="sendSpinner"></div>
            </button> -->';

$aditionalAuth = '<div class="text-center pt-4">
    <p>¿Ya tienes cuenta? <a href="index.php" class="link_general" >Iniciar sesión</a></p>
</div>';

$scriptsAuth = '<script src="public/js/user/index.js"></script>
    <script src="public/js/user/register.js"></script>';

require_once 'public/views/form.php';

?>