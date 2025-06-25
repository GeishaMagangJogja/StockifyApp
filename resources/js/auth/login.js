import axios from "../axios";

document.addEventListener('DOMContentLoaded', () => {
  const loginForm      = document.getElementById('loginForm');
  const loginButton    = document.getElementById('loginButton');
  const loginText      = document.getElementById('loginText');
  const loginIcon      = document.getElementById('loginIcon');
  const loadingIcon    = document.getElementById('loadingIcon');
  const alertContainer = document.getElementById('alertContainer');

  loginForm.addEventListener('submit', async e => {
    e.preventDefault();
    clearErrors();
    setLoading(true);

    const email    = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
      // Ambil CSRF cookie terlebih dulu
      await axios.get('/sanctum/csrf-cookie');

      // Kirim kredensial ke route /login (AuthController@login)
      const { data } = await axios.post('/login', { email, password });

      // Tampilkan pesan sukses
      showAlert('success', data.message || 'Login berhasil!');

      // Redirect sesuai role (dikirim server)
      setTimeout(() => {
        window.location.href = data.redirect;
      }, 1000);

    } catch (err) {
      console.error(err);
      if (err.response && err.response.status === 422) {
        // Validasi gagal
        const errors = err.response.data.errors || {};
        if (errors.email)    showFieldError('email', errors.email[0]);
        if (errors.password) showFieldError('password', errors.password[0]);
      } else {
        // Gagal kredensial atau server error
        showAlert('error', err.response?.data.message || 'Login gagal. Cek email/password.');
      }
    } finally {
      setLoading(false);
    }
  });

  function setLoading(on) {
    loginButton.disabled        = on;
    loginText.textContent       = on ? 'Memproses…' : 'Masuk';
    loginIcon.classList.toggle('hidden', on);
    loadingIcon.classList.toggle('hidden', !on);
  }

  function showAlert(type, msg) {
    const cls = type === 'success'
      ? 'bg-green-50 border-green-200 text-green-800'
      : 'bg-red-50 border-red-200 text-red-800';

    alertContainer.innerHTML = `
      <div class="border rounded-lg p-4 ${cls}">
        <p class="text-sm font-medium">${msg}</p>
      </div>`;
    alertContainer.classList.remove('hidden');
  }

  function clearErrors() {
    ['email','password'].forEach(id => {
      document.getElementById(`${id}-error`).classList.add('hidden');
    });
    alertContainer.classList.add('hidden');
  }

  function showFieldError(field, msg) {
    const el = document.getElementById(`${field}-error`);
    el.textContent = msg;
    el.classList.remove('hidden');
  }
});
