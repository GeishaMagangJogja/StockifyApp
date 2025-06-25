<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Stockify</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Login ke Stockify</h2>
    <form id="loginForm" class="space-y-4">
      <input type="email" id="email" placeholder="Email" class="w-full border p-2 rounded focus:outline-primary-700" required />
      <input type="password" id="password" placeholder="Password" class="w-full border p-2 rounded focus:outline-primary-700" required />
      <button type="submit" class="w-full bg-primary-700 text-white py-2 rounded hover:bg-primary-800">Login</button>
    </form>
    <p class="text-sm text-center mt-4 text-gray-600">
      Belum punya akun? <a href="/register" class="text-primary-700 hover:underline">Daftar sekarang</a>
    </p>
  </div>

  @vite('resources/js/auth/login.js')
</body>
</html>
