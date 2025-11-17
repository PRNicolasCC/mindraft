// Form validation and loading state
document.getElementById('sendForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const button = document.getElementById('sendButton');
    const spinner = document.getElementById('sendSpinner');
    const btnText = button.querySelector('.btn-text');

    // Basic validation
    if (!email || !password) {
        e.preventDefault();
        showMessage('Por favor completa todos los campos', 'error');
        return;
    }

    if (!validateEmail(email, e)) return;

    // Show loading state
    showLoadingState(button, btnText, spinner)
});