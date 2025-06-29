// Tunggu hingga seluruh struktur halaman (DOM) selesai dimuat
document.addEventListener('DOMContentLoaded', function() {

    // Sekarang aman untuk mencari elemen karena semuanya sudah ada
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    const themeToggleBtn = document.getElementById('theme-toggle');

    // Periksa apakah tombol-tombol tersebut benar-benar ada sebelum melanjutkan
    if (themeToggleBtn && themeToggleDarkIcon && themeToggleLightIcon) {
        
        // Fungsi untuk memeriksa dan mengatur ikon saat halaman pertama kali dimuat
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        // Tambahkan event listener ke tombol utama
        themeToggleBtn.addEventListener('click', function() {

            // Toggle ikon bulan dan matahari
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // Jika tema sudah pernah disimpan di localStorage
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            // Jika belum pernah disimpan
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    }
});
