document.getElementById('sendForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const button = document.getElementById('sendButton');
    const spinner = document.getElementById('sendSpinner');
    const btnText = button.querySelector('.btn-text');

    // Basic validation
    if (!password || !confirmPassword) {
        e.preventDefault();
        showMessage('Por favor completa todos los campos', 'error');
        return;
    }

    if (password !== confirmPassword) {
        e.preventDefault();
        showMessage('Las contraseñas no coinciden', 'error');
        return;
    }

    const { strength, feedback } = checkPasswordStrength(password);
    if (strength < 5) {
        e.preventDefault();
        showMessage('La contraseña es muy débil. Por favor usa ' + feedback.join(', '), 'warning');
        return;
    }

    showLoadingState(button, btnText, spinner)
});
