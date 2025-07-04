import axios from "../axios";

// Universal toggle password function
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    // Asumsi ID ikon mata adalah [fieldId]-eye-open dan [fieldId]-eye-closed
    const eyeOpen = document.getElementById(`${fieldId}-eye-open`);
    const eyeClosed = document.getElementById(`${fieldId}-eye-closed`);

    if (!passwordField || !eyeOpen || !eyeClosed) return;

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordField.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}

// Make it globally accessible for HTML onclick
window.togglePassword = togglePassword;

document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    if (!registerForm) return;

    const registerButton = document.getElementById('registerButton');
    const registerText = document.getElementById('registerText');
    const registerLoadingIcon = document.getElementById('registerLoadingIcon');
    const alertContainer = document.getElementById('alertContainer');

    registerForm.addEventListener('submit', async e => {
        e.preventDefault();
        clearErrors();
        
        // Validasi frontend sederhana sebelum mengirim
        if (!validateForm()) {
            return;
        }

        setLoadingState(true);

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            // Endpoint registrasi standar Laravel (web routes)
            // Jika Anda menggunakan API, ubah ke /api/register
            const response = await axios.post('/register', {
                name,
                email,
                password,
                password_confirmation: password // Laravel web routes butuh ini
            });

            showAlert('success', response.data.message || 'Registrasi berhasil! Mengarahkan ke halaman login...');

            setTimeout(() => {
                // Arahkan ke halaman login setelah registrasi berhasil
                window.location.href = '/login';
            }, 2000);

        } catch (err) {
            setLoadingState(false);
            console.error('Register error:', err);

            if (err.response) {
                const status = err.response.status;
                const errorData = err.response.data;

                if (status === 422) { // Validation error
                    const errors = errorData.errors || {};
                    Object.keys(errors).forEach(field => {
                        showFieldError(field, errors[field][0]);
                    });
                } else {
                    showAlert('error', errorData.message || 'Terjadi kesalahan saat registrasi.');
                }
            } else {
                showAlert('error', 'Koneksi ke server gagal. Periksa jaringan Anda.');
            }
        }
    });

    function setLoadingState(isLoading) {
        if (!registerButton || !registerLoadingIcon || !registerText) return;

        registerButton.disabled = isLoading;

        if (isLoading) {
            registerText.textContent = 'Memproses...';
            registerLoadingIcon.classList.remove('hidden');
        } else {
            registerText.textContent = 'Daftar Sekarang';
            registerLoadingIcon.classList.add('hidden');
        }
    }

    function validateForm() {
        let isValid = true;
        clearErrors();

        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        if (name.value.trim().length < 3) {
            showFieldError('name', 'Nama minimal 3 karakter.');
            isValid = false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            showFieldError('email', 'Format email tidak valid.');
            isValid = false;
        }
        
        if (password.value.length < 6) {
            showFieldError('password', 'Password minimal 6 karakter.');
            isValid = false;
        }

        return isValid;
    }
    
    function showAlert(type, message) {
        if (!alertContainer) return;

        const alertClass = type === 'success'
            ? 'bg-green-100 border-green-400 text-green-700 dark:bg-green-900/50 dark:border-green-700 dark:text-green-300'
            : 'bg-red-100 border-red-400 text-red-700 dark:bg-red-900/50 dark:border-red-700 dark:text-red-300';
        
        const iconSvg = type === 'success'
            ? `<i class="fa-solid fa-check-circle"></i>`
            : `<i class="fa-solid fa-exclamation-triangle"></i>`;

        alertContainer.innerHTML = `
            <div class="border rounded-xl p-4 ${alertClass}">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-xl">
                        ${iconSvg}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                </div>
            </div>`;
        alertContainer.classList.remove('hidden');
    }

    function clearErrors() {
        if (alertContainer) {
            alertContainer.classList.add('hidden');
            alertContainer.innerHTML = '';
        }
        ['name', 'email', 'password'].forEach(fieldId => {
            const errorElement = document.getElementById(`${fieldId}-error`);
            const inputElement = document.getElementById(fieldId);
            if (errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
            if (inputElement) {
                inputElement.classList.remove('border-red-500', 'focus:ring-red-500');
            }
        });
    }

    function showFieldError(fieldName, message) {
        const errorElement = document.getElementById(`${fieldName}-error`);
        const inputElement = document.getElementById(fieldName);
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
        if (inputElement) {
            inputElement.classList.add('border-red-500', 'focus:ring-red-500');
        }
    }
});