// Real-time strength password checking
document.getElementById('password').addEventListener('input', updatePasswordStrength);

// Form validation and loading state
document.getElementById('sendForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    //const terms = document.getElementById('terms').checked;
    const button = document.getElementById('sendButton');
    const spinner = document.getElementById('sendSpinner');
    const btnText = button.querySelector('.btn-text');
    const btnEnvio = document.getElementsByName('create_user')[0];

    // Basic validation
    if (!email || !username || !password || !confirmPassword || !btnEnvio) {
        e.preventDefault();
        showMessage('Por favor completa todos los campos', 'error');
        return;
    }

    if (!validateEmail(email, e)) return;

    if (password !== confirmPassword) {
        e.preventDefault();
        showMessage('Las contraseñas no coinciden', 'error');
        return;
    }

    /* if (!terms) {
        e.preventDefault();
        showMessage('Debes aceptar los términos y condiciones', 'error');
        return;
    } */

    const { strength, feedback } = checkPasswordStrength(password);
    if (strength < 5) {
        e.preventDefault();
        showMessage('La contraseña es muy débil. Por favor usa ' + feedback.join(', '), 'warning');
        return;
    }

    // Show loading state
    showLoadingState(button, btnText, spinner)
});

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

