<?php

$propsAuth = array(
    'title' => 'Iniciar Sesión',
    'subtitle' => 'Ingresa tus credenciales para acceder a tu cuenta'
);

$childrenAuth = '
        <!-- Formulario -->
        <form action="" method="post" id="loginForm">
            <div class="form-floating">
                <input
                    type="email"
                    name="emailLogin"
                    id="email"
                    class="form-control border border-warning"
                    placeholder=""
                    required
                    autofocus
                    maxlength="75"
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
                    maxlength="75"
                />
                <label for="password" id="labelPassword" class="fs-5">Contraseña</label>
                <button 
                    type="button" 
                    class="password-toggle" 
                    onclick="togglePassword()"
                    aria-label="Mostrar u ocultar contraseña"
                    tabindex="-1"
                >
                    <i class="fa-solid fa-eye-slash" id="eyeIcon"></i>
                </button>
            </div>

            <button type="submit" class="btn btn-login" id="loginButton">
                <span class="btn-text fw-bold">Iniciar Sesión</span>
                <div class="spinner" id="loginSpinner"></div>
            </button>

            <!-- Mensajes -->';

            if (isset($message) && !empty($message)):
                $childrenAuth .= '<div class="message ' . htmlspecialchars($message_type ?? 'info') . '">
                    <i class="fa-solid ' . $this->getMessageIcon($message_type ?? 'info') . '"></i>'
                    . htmlspecialchars($message) . '
                </div>';
            endif;

            $childrenAuth .= '<div class="text-center p-5">
                            <p><a href="'.$_ENV['DOMAIN'].'/password" target="_blank" rel="noopener noreferrer" class="link_general">¿Olvidaste la contraseña?</a></p>
                            <br>
                            <p><!-- <div class="link_register px-3 py-2 rounded-1"> --><a href="'.$_ENV['DOMAIN'].'/auth" class="link_register px-3">Crear cuenta nueva</a><!-- </div> --></p>
                        </div>
                    </form>

            <script>
                // Toggle password visibility
                function togglePassword() {
                    const passwordInput = document.getElementById(\'password\');
                    const eyeIcon = document.getElementById(\'eyeIcon\');
                    
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

                // Form validation and loading state
                document.getElementById(\'loginForm\').addEventListener(\'submit\', function(e) {
                    const email = document.getElementById(\'email\').value.trim();
                    const password = document.getElementById(\'password\').value;
                    const button = document.getElementById(\'loginButton\');
                    const spinner = document.getElementById(\'loginSpinner\');
                    const btnText = button.querySelector(\'.btn-text\');

                    // Basic validation
                    if (!email || !password) {
                        e.preventDefault();
                        showMessage(\'Por favor completa todos los campos\', \'error\');
                        return;
                    }

                    if (email.length > 75) {
                        e.preventDefault();
                        showMessage(\'El email no puede superar los 75 caracteres\', \'error\');
                        return;
                    }

                    if (password.length > 75) {
                        e.preventDefault();
                        showMessage(\'La contraseña no puede superar los 75 caracteres\', \'error\');
                        return;
                    }

                    // Show loading state
                    button.disabled = true;
                    btnText.style.opacity = \'0\';
                    spinner.style.display = \'inline-block\';
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
                    message.innerHTML = `<i class="fa-solid ${$this->getMessageIconJs(type)}"></i>${text}`;
                    
                    // Add to form
                    document.getElementById(\'loginForm\').appendChild(message);

                    // Auto-hide after 5 seconds
                    setTimeout(() => {
                        if (message && message.parentNode) {
                            message.style.animation = \'slideIn 0.3s ease-out reverse\';
                            setTimeout(() => message.remove(), 300);
                        }
                    }, 5000);
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

                // Auto-focus management
                window.addEventListener(\'load\', function() {
                    const emailField = document.getElementById(\'email\');
                    if (emailField && !emailField.value) {
                        setTimeout(() => emailField.focus(), 100);
                    }
                });

                // Clear loading state on page unload (back button)
                window.addEventListener(\'pageshow\', function(e) {
                    if (e.persisted) {
                        const button = document.getElementById(\'loginButton\');
                        const spinner = document.getElementById(\'loginSpinner\');
                        const btnText = button.querySelector(\'.btn-text\');
                        
                        button.disabled = false;
                        btnText.style.opacity = \'1\';
                        spinner.style.display = \'none\';
                    }
                });
            </script>';

    require_once 'public/views/form.php';
?>