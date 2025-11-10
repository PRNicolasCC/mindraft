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