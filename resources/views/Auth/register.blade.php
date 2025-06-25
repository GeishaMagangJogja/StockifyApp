<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Stockify</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Daftar Akun Baru</h2>
    <form id="registerForm" class="space-y-4">
      <input type="text" id="name" placeholder="Nama Lengkap" class="w-full border p-2 rounded" required />
      <input type="email" id="email" placeholder="Email" class="w-full border p-2 rounded" required />
      <input type="password" id="password" placeholder="Password" class="w-full border p-2 rounded" required />

      <select id="role" class="w-full border p-2 rounded" required>
        <option value="" disabled selected>Pilih Role</option>
        <option value="Admin">Admin</option>
        <option value="Manajer Gudang">Manajer Gudang</option>
        <option value="Staff Gudang">Staff Gudang</option>
      </select>

      <button type="submit" class="w-full bg-primary-700 text-white py-2 rounded hover:bg-primary-800">Daftar</button>
    </form>
    <p class="text-sm text-center mt-4 text-gray-600">
      Sudah punya akun? <a href="/login" class="text-primary-700 hover:underline">Login</a>
    </p>
  </div>

  @vite('resources/js/auth/register.js')
</body>
</html>
