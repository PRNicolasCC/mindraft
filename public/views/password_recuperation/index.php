<?php

$propsAuth = array(
    'title' => '¿Olvidaste tu contraseña?',
    'subtitle' => 'No te preocupes, te ayudamos a recuperarla'
);

$childrenAuth = '
        <!-- Formulario -->
<form action="" method="post" id="forgotPasswordForm">
    <div class="form-floating">
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
    </div>

    <button type="submit" class="btn btn-forgot-password" id="forgotPasswordButton">
        <span class="btn-text">Enviar enlace de recuperación</span>
        <div class="spinner" id="forgotPasswordSpinner"></div>
    </button>

    <!-- Mensajes -->';
    
    if (isset($message) && !empty($message)):
        $childrenAuth .= '<div class="message ' . htmlspecialchars($message_type ?? 'info') . '">
            <i class="fa-solid ' . getMessageIcon($message_type ?? 'info') . '"></i>'
            . htmlspecialchars($message) . '
        </div>';
    endif;

    $childrenAuth .= '
        <div class="navigation-links">
            <a href="index.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Volver al inicio de sesión
            </a>
            <div class="divider">•</div>
            <a href="'.$_ENV['DOMAIN'].'/auth" class="register-link">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>
    </form>

    <script>
        let resendCooldown = 0;
        let countdownInterval;

        // Form validation and loading state
        document.getElementById(\'forgotPasswordForm\').addEventListener(\'submit\', function(e) {
            const email = document.getElementById(\'email\').value.trim();
            const button = document.getElementById(\'forgotPasswordButton\');
            const spinner = document.getElementById(\'forgotPasswordSpinner\');
            const btnText = button.querySelector(\'.btn-text\');

            // Basic validation
            if (!email) {
                e.preventDefault();
                showMessage(\'Por favor ingresa tu email\', \'error\');
                return;
            }

            // Email validation
            const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                showMessage(\'Por favor ingresa un email válido\', \'error\');
                return;
            }

            // Show loading state
            button.disabled = true;
            btnText.style.opacity = \'0\';
            spinner.style.display = \'inline-block\';

            // Simulate API call
            /* setTimeout(() => {
                // Hide form and show success state
                document.querySelector(\'.header-section\').style.display = \'none\';
                document.querySelector(\'.form-floating\').style.display = \'none\';
                document.querySelector(\'.info-box\').style.display = \'none\';
                document.getElementById(\'forgotPasswordButton\').style.display = \'none\';
                document.getElementById(\'successState\').style.display = \'block\';
                
                // Start resend cooldown
                startResendCooldown();
                
                // Reset button state (in case user goes back)
                button.disabled = false;
                btnText.style.opacity = \'1\';
                spinner.style.display = \'none\';
            }, 2000); */
        });

        // Resend functionality
        /* document.getElementById(\'resendButton\').addEventListener(\'click\', function() {
            if (resendCooldown > 0) return;
            
            const button = this;
            button.disabled = true;
            
            // Simulate resend
            setTimeout(() => {
                showMessage(\'Enlace reenviado correctamente\', \'success\');
                startResendCooldown();
            }, 1000);
        });

        // Start resend cooldown
        function startResendCooldown() {
            resendCooldown = 60; // 60 seconds
            const resendButton = document.getElementById(\'resendButton\');
            const countdown = document.getElementById(\'countdown\');
            
            resendButton.disabled = true;
            countdown.style.display = \'inline\';
            
            countdownInterval = setInterval(() => {
                countdown.textContent = `(${resendCooldown}s)`;
                resendCooldown--;
                
                if (resendCooldown < 0) {
                    clearInterval(countdownInterval);
                    resendButton.disabled = false;
                    countdown.style.display = \'none\';
                }
            }, 1000);
        }

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
            const form = document.getElementById(\'forgotPasswordForm\');
            const successState = document.getElementById(\'successState\');
            
            if (successState.style.display !== \'none\') {
                successState.appendChild(message);
            } else {
                form.appendChild(message);
            }

            // Auto-hide after 6 seconds
            setTimeout(() => {
                if (message && message.parentNode) {
                    message.style.animation = \'slideIn 0.3s ease-out reverse\';
                    setTimeout(() => message.remove(), 300);
                }
            }, 6000);
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
                const button = document.getElementById(\'forgotPasswordButton\');
                const spinner = document.getElementById(\'forgotPasswordSpinner\');
                const btnText = button.querySelector(\'.btn-text\');
                
                button.disabled = false;
                btnText.style.opacity = \'1\';
                spinner.style.display = \'none\';
                
                // Clear countdown if active
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }
            }
        });

        // Back button functionality for success state
        function goBackToForm() {
            document.querySelector(\'.header-section\').style.display = \'block\';
            document.querySelector(\'.form-floating\').style.display = \'block\';
            document.querySelector(\'.info-box\').style.display = \'block\';
            document.getElementById(\'forgotPasswordButton\').style.display = \'block\';
            document.getElementById(\'successState\').style.display = \'none\';
            
            // Clear countdown
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            resendCooldown = 0;
        }

        // Add back functionality to navigation when in success state
        document.addEventListener(\'click\', function(e) {
            if (e.target.classList.contains(\'back-link\') && 
                document.getElementById(\'successState\').style.display !== \'none\') {
                e.preventDefault();
                goBackToForm();
            }
        }); */
    </script>';

    require_once 'public/views/form.php';
?>