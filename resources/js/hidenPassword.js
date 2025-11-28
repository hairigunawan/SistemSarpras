// Toggle password visibility
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const eyeIcon = document.getElementById('eye-icon');
const eyeSlashIcon = document.getElementById('eye-slash-icon');

togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Toggle eye icons
    if (type === 'text') {
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
    } else {
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
    }
});

// Toggle confirm password visibility
const confirmPasswordInput = document.getElementById('password_confirmation');
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const eyeConfirmIcon = document.getElementById('eye-confirm-icon');
const eyeConfirmSlashIcon = document.getElementById('eye-confirm-slash-icon');

toggleConfirmPassword.addEventListener('click', function() {
    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', type);

    // Toggle eye icons
    if (type === 'text') {
        eyeConfirmIcon.classList.add('hidden');
        eyeConfirmSlashIcon.classList.remove('hidden');
    } else {
        eyeConfirmIcon.classList.remove('hidden');
        eyeConfirmSlashIcon.classList.add('hidden');
    }
});