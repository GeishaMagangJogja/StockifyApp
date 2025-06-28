import axios from "../axios";

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

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

// Make toggle function global
window.togglePassword = togglePassword;

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginText = document.getElementById('loginText');
    const loginIcon = document.getElementById('loginIcon');
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
            // CSRF protection untuk Laravel Sanctum (jika digunakan)
            try {
                await axios.get('/sanctum/csrf-cookie');
            } catch (csrfError) {
                // Jika sanctum tidak ada, lanjutkan saja
                console.log('CSRF cookie not available, continuing...');
            }

            // Kirim data login
            const response = await axios.post('/login', {
                email,
                password,
                remember
            });

            const data = response.data;

            // Tampilkan alert sukses
            showAlert('success', data.message || 'Login berhasil!');

            // Simpan token jika ada (untuk API authentication)
            if (data.data && data.data.access_token) {
                localStorage.setItem('auth_token', data.data.access_token);
                localStorage.setItem('user_data', JSON.stringify(data.data.user));

                // Set default authorization header untuk request selanjutnya
                axios.defaults.headers.common['Authorization'] = `Bearer ${data.data.access_token}`;
            }

            // Redirect berdasarkan role
            setTimeout(() => {
                if (data.data && data.data.redirect_to) {
                    window.location.href = data.data.redirect_to;
                } else {
                    // Fallback redirect berdasarkan role user
                    const user = data.data?.user;
                    if (user?.role === 'Admin') {
                        window.location.href = '/admin/dashboard';
                    } else if (user?.role === 'Manajer Gudang') {
                        window.location.href = '/manajergudang/dashboard';
                    } else if (user?.role === 'Staff Gudang') {
                        window.location.href = '/staff/dashboard';
                    } else {
                        window.location.href = '/';
                    }
                }
            }, 1500);

        } catch (err) {
            console.error('Login error:', err);

            if (err.response) {
                const status = err.response.status;
                const errorData = err.response.data;

                if (status === 422) {
                    // Validation errors
                    const errors = errorData.errors || {};
                    if (errors.email) showFieldError('email', errors.email[0]);
                    if (errors.password) showFieldError('password', errors.password[0]);
                } else if (status === 401) {
                    // Unauthorized - wrong credentials
                    showAlert('error', errorData.message || 'Email atau password salah.');
                } else {
                    // Other server errors
                    showAlert('error', errorData.message || 'Terjadi kesalahan pada server.');
                }
            } else if (err.request) {
                // Network error
                showAlert('error', 'Koneksi ke server gagal. Periksa koneksi internet Anda.');
            } else {
                // Unknown error
                showAlert('error', 'Terjadi kesalahan tidak terduga.');
            }
        } finally {
            setLoading(false);
        }
    });

    function setLoading(isLoading) {
        loginButton.disabled = isLoading;
        loginText.textContent = isLoading ? 'Memproses...' : 'Masuk';

        if (isLoading) {
            loginIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');
        } else {
            loginIcon.classList.remove('hidden');
            loadingIcon.classList.add('hidden');
        }
    }

    function showAlert(type, message) {
        const alertClass = type === 'success'
            ? 'bg-green-50 border-green-200 text-green-800'
            : 'bg-red-50 border-red-200 text-red-800';

        const iconSvg = type === 'success'
            ? `<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
               </svg>`
            : `<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
               </svg>`;

        alertContainer.innerHTML = `
            <div class="border rounded-lg p-4 ${alertClass}">
                <div class="flex">
                    <div class="flex-shrink-0">
                        ${iconSvg}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                </div>
            </div>`;

        alertContainer.classList.remove('hidden');

        // Auto hide success alerts after 3 seconds
        if (type === 'success') {
            setTimeout(() => {
                alertContainer.classList.add('hidden');
            }, 3000);
        }
    }

    function clearErrors() {
        // Clear field errors
        ['email', 'password'].forEach(fieldId => {
            const errorElement = document.getElementById(`${fieldId}-error`);
            if (errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
        });

        // Clear alert container
        alertContainer.classList.add('hidden');
        alertContainer.innerHTML = '';
    }

    function showFieldError(fieldName, message) {
        const errorElement = document.getElementById(`${fieldName}-error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }

        // Add red border to input field
        const inputElement = document.getElementById(fieldName);
        if (inputElement) {
            inputElement.classList.add('border-red-500');
            inputElement.classList.remove('border-gray-300');
        }
    }

    // Remove error styling when user starts typing
    ['email', 'password'].forEach(fieldId => {
        const inputElement = document.getElementById(fieldId);
        if (inputElement) {
            inputElement.addEventListener('input', () => {
                // Remove error styling
                inputElement.classList.remove('border-red-500');
                inputElement.classList.add('border-gray-300');

                // Hide error message
                const errorElement = document.getElementById(`${fieldId}-error`);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                }
            });
        }
    });
});
