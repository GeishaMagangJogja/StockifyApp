import axios from "../axios";

document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
        const res = await axios.post("/auth/login", { email, password });
        localStorage.setItem("token", res.data.token);
        alert("Login berhasil!");
        window.location.href = "/dashboard";
    } catch (err) {
        alert("Login gagal! Periksa email/password.");
        console.error(err);
    }
});
