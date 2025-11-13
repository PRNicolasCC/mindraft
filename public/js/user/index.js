// Toggle password visibility
function togglePassword(password, icon) {
    const passwordInput = document.getElementById(password);
    const eyeIcon = document.getElementById(icon);
    
    if (passwordInput.type === 'text') {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

function showLoadingState(button, btnText, spinner){
    button.disabled = true;
    btnText.style.opacity = '0';
    spinner.style.display = 'inline-block';
}

// Email validation
function validateEmail(email, e) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showMessage('Por favor ingresa un email válido', 'error');
        return false;
    }
    return true;
}

function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    if (!password) {
        strengthFill.style.width = '0%';
        strengthFill.className = 'strength-fill';
        strengthText.textContent = '';
        return;
    }

    const { strength, feedback } = checkPasswordStrength(password);
    const percentage = (strength / 5) * 100;
    
    strengthFill.style.width = percentage + '%';
    
    // Remove all strength classes
    strengthFill.classList.remove('weak', 'fair', 'good', 'strong');
    
    if (strength <= 2) {
        strengthFill.classList.add('weak');
        strengthText.textContent = 'Débil - Necesitas: ' + feedback.slice(0, 2).join(', '); // Se agrega el slice para únicamente obtener dos máximo tres valores del feedback y que el mensaje no se vea tan largo
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
}

function checkPasswordStrength(password){
    let strength = 0;
    let feedback = [];

    if (password.length >= 8) strength += 1;
    else feedback.push("al menos 8 caracteres");

    if (password.length <= 30) strength += 1;
    else feedback.push("máximo 30 caracteres");

    if (/[A-Z]/.test(password)) strength += 1;
    else feedback.push("una mayúscula");

    if (/[a-z]/.test(password)) strength += 1;
    else feedback.push("una minúscula");

    if (/[0-9]/.test(password)) strength += 1;
    else feedback.push("un número");

    return { strength, feedback };
}

// Real-time strength password checking
document.getElementById('password').addEventListener('input', updatePasswordStrength);