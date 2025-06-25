import axios from "../axios";

document
    .getElementById("registerForm")
    .addEventListener("submit", async (e) => {
        e.preventDefault();

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const role = document.getElementById("role").value;

        try {
            await axios.post("/auth/register", { name, email, password, role });
            alert("Register berhasil!");
            window.location.href = "/login";
        } catch (err) {
            alert("Register gagal!");
            console.error(err);
        }
    });
