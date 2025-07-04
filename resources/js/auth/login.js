import axios from "../axios";

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    if (!passwordInput || !eyeOpen || !eyeClosed) return;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}

// Make toggle function global so it can be called from HTML onclick
window.togglePassword = togglePassword;

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return; // Stop if form not found

    const loginButton = document.getElementById('loginButton');
    const loginText = document.getElementById('loginText');
    const loadingIcon = document.getElementById('loadingIcon');
    const alertContainer = document.getElementById('alertContainer');

    loginForm.addEventListener('submit', async e => {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const remember = document.getElementById('remember').checked;

        try {
            // CSRF protection for Laravel Sanctum
            try {
                await axios.get('/sanctum/csrf-cookie');
            } catch (csrfError) {
                console.log('CSRF cookie endpoint not available, proceeding without it.');
            }

            // Send login request
            const response = await axios.post('/login', {
                email,
                password,
                remember
            });

            const data = response.data;
            
            // Periksa jika respons dari server tidak memiliki properti 'success'
            // Ini untuk menangani redirect dari middleware Laravel yang mungkin terjadi.
            if (typeof data !== 'object' || !data.hasOwnProperty('success')) {
                 // Jika server melakukan redirect (bukan mengembalikan JSON),
                 // muat ulang halaman untuk mengikuti redirect tersebut.
                 window.location.reload();
                 return;
            }

            showAlert('success', data.message || 'Login berhasil! Mengarahkan...');

            // Redirect based on the response
            setTimeout(() => {
                // Utamakan redirect_url dari backend
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    // Fallback jika tidak ada URL redirect spesifik
                    window.location.href = '/dashboard';
                }
            }, 1000);

        } catch (err) {
            setLoading(false); // Pastikan loading state dihentikan saat error
            console.error('Login error:', err);

            if (err.response) {
                const status = err.response.status;
                const errorData = err.response.data;

                if (status === 422) {
                    const errors = errorData.errors || {};
                    if (errors.email) showFieldError('email', errors.email[0]);
                    if (errors.password) showFieldError('password', errors.password[0]);
                    // Tampilkan pesan error umum jika ada
                    if(errorData.message) showAlert('error', errorData.message);
                    
                } else {
                    showAlert('error', errorData.message || 'Terjadi kesalahan pada server.');
                }
            } else if (err.request) {
                showAlert('error', 'Koneksi ke server gagal. Periksa koneksi internet Anda.');
            } else {
                showAlert('error', 'Terjadi kesalahan tidak terduga.');
            }
        } 
        // Jangan panggil setLoading(false) di sini lagi karena sudah ada di blok catch
    });

    function setLoading(isLoading) {
        if (!loginButton || !loadingIcon || !loginText) return;

        loginButton.disabled = isLoading;

        if (isLoading) {
            loginText.textContent = 'Memproses...';
            loadingIcon.classList.remove('hidden');
        } else {
            loginText.textContent = 'Masuk';
            loadingIcon.classList.add('hidden');
        }
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
        ['email', 'password'].forEach(fieldId => {
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