<?php

$propsAuth = array(
    'title' => 'Crear Cuenta',
    'subtitle' => 'Completa tus datos para registrarte'
);

$childrenAuth = '
        <!-- Formulario -->
        <form action="" method="post" id="registerForm">
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
                />
                <label for="email" id="labelEmail" class="fs-5">Email</label>
            </div>

            <div class="form-floating">
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                    maxlength="30"
                    pattern="[a-zA-Z0-9_-]+"
                    title="Solo letras, números, guiones y guiones bajos"
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
                    maxlength="75"
                />
                <label for="password" id="labelPassword" class="fs-5">Contraseña</label>
                <button 
                    type="button" 
                    class="password-toggle" 
                    onclick="togglePassword(\'password\')"
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
                    minlength="8"
                    maxlength="75"
                />
                <label for="confirm_password" id="labelConfirmPassword" class="fs-5">Confirmar contraseña</label>
                <button 
                    type="button" 
                    class="password-toggle" 
                    onclick="togglePassword(\'confirm_password\')"
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
                <p class="strength-text" id="strengthText">Ingresa una contraseña</p>
            </div>

            <!-- Términos y condiciones -->
            <div class="terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    Acepto los <a href="#" target="_blank">términos y condiciones</a> y la <a href="#" target="_blank">política de privacidad</a>
                </label>
            </div>

            <!-- Input para enviar como remplazo del boton de envío al index.
             Esto sucede porque new FormData(form) solo recopila los datos de 
             los campos (input, select, textarea), pero no incluye el botón de submit que inició el evento. -->
            <input type="hidden" name="create_user" value="1">

            <button type="submit" class="btn btn-register" id="registerButton">
                <span class="btn-text">Crear Cuenta</span>
                <div class="spinner" id="registerSpinner"></div>
            </button>

            <!-- Mensajes -->';
            
            if (isset($message) && !empty($message)):
                $childrenAuth .= '<div class="message ' . htmlspecialchars($message_type ?? 'info') . '">
                    <i class="fa-solid ' . getMessageIcon($message_type ?? 'info') . '"></i>'
                    . htmlspecialchars($message) . '
                </div>';
            endif;

            $childrenAuth .= '<div class="text-center p-4">
                <p>¿Ya tienes cuenta? <a href="index.php" class="link_general" >Iniciar sesión</a></p>
            </div>
        </form>

        <script>
            // Toggle password visibility
            function togglePassword(fieldId) {
                const passwordInput = document.getElementById(fieldId);
                const eyeIcon = document.getElementById(fieldId === \'password\' ? \'eyeIcon\' : \'eyeIconConfirm\');
                
                if (passwordInput.type === \'text\') {
                    passwordInput.type = \'password\';
                    eyeIcon.classList.remove(\'fa-eye\');
                    eyeIcon.classList.add(\'fa-eye-slash\');
                } else {
                    passwordInput.type = \'text\';
                    eyeIcon.classList.remove(\'fa-eye-slash\');
                    eyeIcon.classList.add(\'fa-eye\');
                }
            }

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = [];

                // Longitud
                if (password.length >= 8) strength += 1;
                else feedback.push("al menos 8 caracteres");

                // Mayúsculas
                if (/[A-Z]/.test(password)) strength += 1;
                else feedback.push("una mayúscula");

                // Minúsculas
                if (/[a-z]/.test(password)) strength += 1;
                else feedback.push("una minúscula");

                // Números
                if (/[0-9]/.test(password)) strength += 1;
                else feedback.push("un número");

                // Caracteres especiales
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                else feedback.push("un carácter especial");

                return { strength, feedback };
            }

            // Update password strength indicator
            function updatePasswordStrength() {
                const password = document.getElementById(\'password\').value;
                const strengthFill = document.getElementById(\'strengthFill\');
                const strengthText = document.getElementById(\'strengthText\');
                
                if (!password) {
                    strengthFill.style.width = \'0%\';
                    strengthFill.className = \'strength-fill\';
                    strengthText.textContent = \'Ingresa una contraseña\';
                    return;
                }

                const { strength, feedback } = checkPasswordStrength(password);
                const percentage = (strength / 5) * 100;
                
                strengthFill.style.width = percentage + \'%\';
                
                // Remove all strength classes
                strengthFill.classList.remove(\'weak\', \'fair\', \'good\', \'strong\');
                
                if (strength <= 2) {
                    strengthFill.classList.add(\'weak\');
                    strengthText.textContent = \'Débil - Necesitas: \' + feedback.slice(0, 2).join(\', \');
                } else if (strength === 3) {
                    strengthFill.classList.add(\'fair\');
                    strengthText.textContent = \'Regular - Necesitas: \' + feedback.join(\', \');
                } else if (strength === 4) {
                    strengthFill.classList.add(\'good\');
                    strengthText.textContent = \'Buena - Necesitas: \' + feedback.join(\', \');
                } else {
                    strengthFill.classList.add(\'strong\');
                    strengthText.textContent = \'Excelente - Contraseña segura\';
                }
            }

            // Form validation and loading state
            document.getElementById(\'registerForm\').addEventListener(\'submit\', function(e) {
                const email = document.getElementById(\'email\').value.trim();
                const username = document.getElementById(\'username\').value.trim();
                const password = document.getElementById(\'password\').value;
                const confirmPassword = document.getElementById(\'confirm_password\').value;
                const terms = document.getElementById(\'terms\').checked;
                const button = document.getElementById(\'registerButton\');
                const spinner = document.getElementById(\'registerSpinner\');
                const btnText = button.querySelector(\'.btn-text\');

                // Basic validation
                if (!email || !username || !password || !confirmPassword) {
                    e.preventDefault();
                    showMessage(\'Por favor completa todos los campos\', \'error\');
                    return;
                }

                // Email validation
                const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    showMessage(\'Por favor ingresa un email válido\', \'error\');
                    return;
                }

                // Username validation
                // Está comentado porque impide los nombre con \'ñ\'
                /* const usernameRegex = /^[a-zA-Z0-9_-]+$/;
                if (!usernameRegex.test(username)) {
                    e.preventDefault();
                    showMessage(\'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos\', \'error\');
                    return;
                } */

                if (username.length < 3) {
                    e.preventDefault();
                    showMessage(\'El nombre de usuario debe tener al menos 3 caracteres\', \'error\');
                    return;
                }

                // Password validation
                if (password.length < 8) {
                    e.preventDefault();
                    showMessage(\'La contraseña debe tener al menos 8 caracteres\', \'error\');
                    return;
                }

                if (password !== confirmPassword) {
                    e.preventDefault();
                    showMessage(\'Las contraseñas no coinciden\', \'error\');
                    return;
                }

                if (!terms) {
                    e.preventDefault();
                    showMessage(\'Debes aceptar los términos y condiciones\', \'error\');
                    return;
                }

                // Check password strength
                /* const { strength } = checkPasswordStrength(password);
                if (strength < 3) {
                    e.preventDefault();
                    showMessage(\'La contraseña es muy débil. Por favor usa una contraseña más segura\', \'warning\');
                    return;
                } */

                // Show loading state
                button.disabled = true;
                btnText.style.opacity = \'0\';
                spinner.style.display = \'inline-block\';
            });

            // Real-time password strength checking
            document.getElementById(\'password\').addEventListener(\'input\', updatePasswordStrength);

            // Real-time password confirmation checking
            document.getElementById(\'confirm_password\').addEventListener(\'input\', function() {
                const password = document.getElementById(\'password\').value;
                const confirmPassword = this.value;
                
                this.classList.remove(\'match\', \'no-match\');
                
                if (confirmPassword && password !== confirmPassword) {
                    this.classList.add(\'no-match\');
                } else if (confirmPassword && password === confirmPassword) {
                    this.classList.add(\'match\');
                }
            });

            // Show message function
            function showMessage(text, type) {
                // Remove existing messages
                const existingMessage = document.querySelector(\'.message\');
                if (existingMessage) {
                    existingMessage.remove();
                }

                // Create new message
                const message = document.createElement(\'div\');
                message.className = `message ${type}`;
                message.innerHTML = `<i class="fa-solid ${getMessageIconJs(type)}"></i>${text}`;
                
                // Add to form
                document.getElementById(\'registerForm\').appendChild(message);

                // Auto-hide after 7 seconds
                setTimeout(() => {
                    if (message && message.parentNode) {
                        message.style.animation = \'slideIn 0.3s ease-out reverse\';
                        setTimeout(() => message.remove(), 300);
                    }
                }, 7000);
            }

            // Get message icon for JavaScript
            function getMessageIconJs(type) {
                const icons = {
                    \'error\': \'fa-exclamation-triangle\',
                    \'success\': \'fa-check-circle\',
                    \'warning\': \'fa-exclamation-circle\',
                    \'info\': \'fa-info-circle\'
                };
                return icons[type] || icons[\'info\'];
            }

            // Clear loading state on page unload (back button)
            window.addEventListener(\'pageshow\', function(e) { //\'pageshow\' sirve para agregar callbacks cuando una página web ha sido mostrada en la ventana del navegador, ya sea: La primera vez que se carga o Cuando el usuario vuelve a ella desde el historial o caché (bfcache), por ejemplo, al usar el botón Atrás/Adelante del navegador
                if (e.persisted) { // persisted indica que la página se cargó desde el caché (bfcache).
                    const button = document.getElementById(\'registerButton\');
                    const spinner = document.getElementById(\'registerSpinner\');
                    const btnText = button.querySelector(\'.btn-text\');
                    
                    button.disabled = false;
                    btnText.style.opacity = \'1\';
                    spinner.style.display = \'none\';
                }
            });
        </script>';

    require_once 'public/views/form.php';
?>