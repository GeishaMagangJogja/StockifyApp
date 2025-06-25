import axios from "../axios";

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginText = document.getElementById('loginText');
    const loginIcon = document.getElementById('loginIcon');
    const loadingIcon = document.getElementById('loadingIcon');
    const alertContainer = document.getElementById('alertContainer');

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        clearErrors();

        // Show loading state
        setLoadingState(true);

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await axios.post('/auth/login', {
                email,
                password
            });

            // Store token in localStorage
            localStorage.setItem('token', response.data.token);

            // Show success message
            showAlert('success', 'Login berhasil!');

            // Redirect to dashboard
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 1500);

        } catch (error) {
            console.error('Login error:', error);

            if (error.response) {
                // Server responded with a status code outside 2xx
                showAlert('error', 'Login gagal! Periksa email/password.');
            } else if (error.request) {
                // Request was made but no response received
                showAlert('error', 'Tidak ada respon dari server. Silakan coba lagi.');
            } else {
                // Something happened in setting up the request
                showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
            }
        } finally {
            setLoadingState(false);
        }
    });

    function setLoadingState(loading) {
        loginButton.disabled = loading;
        loginText.textContent = loading ? 'Memproses...' : 'Masuk';
        loginIcon.classList.toggle('hidden', loading);
        loadingIcon.classList.toggle('hidden', !loading);
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
        const iconPath = type === 'success'
            ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
            : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';

        alertContainer.innerHTML = `
            <div class="border rounded-lg p-4 ${alertClass}">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                </div>
            </div>
        `;
        alertContainer.classList.remove('hidden');
    }

    function clearErrors() {
        document.getElementById('email-error').classList.add('hidden');
        document.getElementById('password-error').classList.add('hidden');
        alertContainer.classList.add('hidden');
    }
});

function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

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
