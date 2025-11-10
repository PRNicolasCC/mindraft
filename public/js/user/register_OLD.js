// Toggle password visibility
/* function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId === 'password' ? 'eyeIcon' : 'eyeIconConfirm');
    
    if (passwordInput.type === 'text') {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
} */

// Password strength checker
/* function checkPasswordStrength(password) {
    let strength = 0;
    let feedback = [];

    // Longitud
    if (password.length >= 8) strength += 1;
    else feedback.push("al menos 8 caracteres");

    if (password.length <= 30) strength += 1;
    else feedback.push("máximo 30 caracteres");

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
} */

// Update password strength indicator
/* function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    if (!password) {
        strengthFill.style.width = '0%';
        strengthFill.className = 'strength-fill';
        strengthText.textContent = 'Ingresa una contraseña';
        return;
    }

    const { strength, feedback } = checkPasswordStrength(password);
    const percentage = (strength / 5) * 100;
    
    strengthFill.style.width = percentage + '%';
    
    // Remove all strength classes
    strengthFill.classList.remove('weak', 'fair', 'good', 'strong');
    
    if (strength <= 2) {
        strengthFill.classList.add('weak');
        strengthText.textContent = 'Débil - Necesitas: ' + feedback.slice(0, 2).join(', ');
    } else if (strength === 3) {
        strengthFill.classList.add('fair');
        strengthText.textContent = 'Regular - Necesitas: ' + feedback.join(', ');
    } else if (strength === 4) {
        strengthFill.classList.add('good');
        strengthText.textContent = 'Buena - Necesitas: ' + feedback.join(', ');
    } else {
        strengthFill.classList.add('strong');
        strengthText.textContent = 'Excelente - Contraseña segura';
    }
} */

// Form validation and loading state
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const terms = document.getElementById('terms').checked;
    const button = document.getElementById('registerButton');
    const spinner = document.getElementById('registerSpinner');
    const btnText = button.querySelector('.btn-text');
    const btnEnvio = document.getElementsByName('create_user')[0];

    // Basic validation
    if (!email || !username || !password || !confirmPassword || !btnEnvio) {
        e.preventDefault();
        showMessage('Por favor completa todos los campos', 'error');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showMessage('Por favor ingresa un email válido', 'error');
        return;
    }

    // Username validation
    // Está comentado porque impide los nombre con 'ñ'
    /* const usernameRegex = /^[a-zA-Z0-9_-]+$/;
    if (!usernameRegex.test(username)) {
        e.preventDefault();
        showMessage('El nombre de usuario solo puede contener letras, números, guiones y guiones bajos', 'error');
        return;
    } */

    /* if (username.length < 3) {
        e.preventDefault();
        showMessage('El nombre de usuario debe tener al menos 3 caracteres', 'error');
        return;
    } */

    // Password validation
    if (password.length < 8) {
        e.preventDefault();
        showMessage('La contraseña debe tener al menos 8 caracteres', 'error');
        return;
    }

    if (password.length > 30) {
        e.preventDefault();
        showMessage('La contraseña debe tener máximo 30 caracteres', 'error');
        return;
    }

    if (password !== confirmPassword) {
        e.preventDefault();
        showMessage('Las contraseñas no coinciden', 'error');
        return;
    }

    if (!terms) {
        e.preventDefault();
        showMessage('Debes aceptar los términos y condiciones', 'error');
        return;
    }

    // Check password strength (opcional)
    /* const { strength } = checkPasswordStrength(password);
    if (strength < 3) {
        e.preventDefault();
        showMessage('La contraseña es muy débil. Por favor usa una contraseña más segura', 'warning');
        return;
    } */

    // Show loading state
    button.disabled = true;
    btnText.style.opacity = '0';
    spinner.style.display = 'inline-block';
});

// Real-time password strength checking
// document.getElementById('password').addEventListener('input', updatePasswordStrength);

// Real-time password confirmation checking
/* document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    this.classList.remove('match', 'no-match');
    
    if (confirmPassword && password !== confirmPassword) {
        this.classList.add('no-match');
    } else if (confirmPassword && password === confirmPassword) {
        this.classList.add('match');
    }
}); */

// Show message function
function showMessage(text, type) {
    // Remove existing messages
    const existingMessage = document.querySelector('.message');
    if (existingMessage) {
        existingMessage.remove();
    }

    // Create new message
    const message = document.createElement('div');
    message.className = `message ${type}`;
    message.innerHTML = `<i class="fa-solid ${getMessageIconJs(type)}"></i>${text}`;
    
    // Add to form
    document.getElementById('registerForm').appendChild(message);

    // Auto-hide after 7 seconds
    /* setTimeout(() => {
        if (message && message.parentNode) {
            message.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => message.remove(), 300);
        }
    }, 7000); */
}

// Get message icon for JavaScript
function getMessageIconJs(type) {
    const icons = {
        'error': 'fa-exclamation-triangle',
        'success': 'fa-check-circle',
        'warning': 'fa-exclamation-circle',
        'info': 'fa-info-circle'
    };
    return icons[type] || icons['info'];
}

// Clear loading state on page unload (back button)
window.addEventListener('pageshow', function(e) { //pageshow: indica un evento que se dispara cuando una ventana o documento se hace visible
    if (e.persisted) { // e.persisted es true solo si la página se está mostrando porque fue restaurada desde la BFCache (es decir, el usuario volvió desde otra página). Si la página se carga por primera vez, e.persisted es false.
        const button = document.getElementById('registerButton');
        const spinner = document.getElementById('registerSpinner');
        const btnText = button.querySelector('.btn-text');
        
        button.disabled = false;
        btnText.style.opacity = '1';
        spinner.style.display = 'none';
    }
});
