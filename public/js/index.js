// Email validation
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showMessage('Por favor ingresa un email válido', 'error');
        return;
    }
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
    document.getElementById('sendForm').appendChild(message);
}

// Clear loading state on page unload (back button)
window.addEventListener('pageshow', function(e) { //pageshow: indica un evento que se dispara cuando una ventana o documento se hace visible
    if (e.persisted) { // e.persisted es true solo si la página se está mostrando porque fue restaurada desde la BFCache (es decir, el usuario volvió desde otra página). Si la página se carga por primera vez, e.persisted es false.
        const button = document.getElementById('sendButton');
        const spinner = document.getElementById('sendSpinner');
        const btnText = button.querySelector('.btn-text');
        
        button.disabled = false;
        btnText.style.opacity = '1';
        spinner.style.display = 'none';
    }
});