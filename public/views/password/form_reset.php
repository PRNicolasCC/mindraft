<?php

$propsAuth = [
    'title' => 'Restablecer contraseña',
    'subtitle' => 'Escribe la nueva contraseña para la cuenta registrada con ' . htmlspecialchars(SessionManager::get('redirectInputs')['email']) . '',
    'sendButton' => 'Restablecer contraseña',
];

$childrenAuth = '
            <input type="hidden" name="_method" value="PUT">
            <div class="form-floating password-field">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                    autofocus
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
            
            <input type="hidden" name="email" value="' . htmlspecialchars(SessionManager::get('redirectInputs')['email']) . '" required>
            <input type="hidden" name="token" value="' . htmlspecialchars(SessionManager::get('redirectInputs')['token']) . '" required>
            <input type="hidden" name="password_change" value="1" required>';
    
SessionManager::remove('redirectInputs');

$scriptsAuth = '<script src="public/js/password/form_reset.js"></script>';

require_once 'public/views/form.php';
?>