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

    validateEmail(email);
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

// Resend functionality
/*
document.getElementById('resendButton').addEventListener('click', function() {
    if (resendCooldown > 0) return;
    
    const button = this;
    button.disabled = true;
    
    // Simulate resend
    setTimeout(() => {
        showMessage('Enlace reenviado correctamente', 'success');
        startResendCooldown();
    }, 1000);
});
*/

// Start resend cooldown
/* function startResendCooldown() {
    resendCooldown = 60; // 60 seconds
    const resendButton = document.getElementById('resendButton');
    const countdown = document.getElementById('countdown');
    
    resendButton.disabled = true;
    countdown.style.display = 'inline';
    
    countdownInterval = setInterval(() => {
        countdown.textContent = `(${resendCooldown}s)`;
        resendCooldown--;
        
        if (resendCooldown < 0) {
            clearInterval(countdownInterval);
            resendButton.disabled = false;
            countdown.style.display = 'none';
        }
    }, 1000);
} */

// Back button functionality for success state
/* function goBackToForm() {
    document.querySelector('.header-section').style.display = 'block';
    document.querySelector('.form-floating').style.display = 'block';
    document.querySelector('.info-box').style.display = 'block';
    document.getElementById('forgotPasswordButton').style.display = 'block';
    document.getElementById('successState').style.display = 'none';
    
    // Clear countdown
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
    resendCooldown = 0;
} */

// Add back functionality to navigation when in success state
/* document.addEventListener('click', function(e) {
    if (e.target.classList.contains('back-link') && 
        document.getElementById('successState').style.display !== 'none') {
        e.preventDefault();
        goBackToForm();
    }
}); */
