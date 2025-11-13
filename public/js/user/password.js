let resendCooldown = 0;
let countdownInterval;

// Form validation and loading state
document.getElementById('sendForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const button = document.getElementById('sendButton');
    const spinner = document.getElementById('sendSpinner');
    const btnText = button.querySelector('.btn-text');

    // Basic validation
    if (!email) {
        e.preventDefault();
        showMessage('Por favor ingresa tu email', 'error');
        return;
    }

    if (!validateEmail(email, e)) return;
    showLoadingState(button, btnText, spinner);

    // Simulate API call
    /*
    setTimeout(() => {
        // Hide form and show success state
        document.querySelector('.header-section').style.display = 'none';
        document.querySelector('.form-floating').style.display = 'none';
        document.querySelector('.info-box').style.display = 'none';
        document.getElementById('forgotPasswordButton').style.display = 'none';
        document.getElementById('successState').style.display = 'block';
        
        // Start resend cooldown
        startResendCooldown();
        
        // Reset button state (in case user goes back)
        button.disabled = false;
        btnText.style.opacity = '1';
        spinner.style.display = 'none';
    }, 2000);
    */
});
