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

