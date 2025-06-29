import axios from "../axios";

document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
    const registerButton = document.getElementById("registerButton");
    const registerText = document.getElementById("registerText");
    const registerIcon = document.getElementById("registerIcon");
    const registerLoadingIcon = document.getElementById("registerLoadingIcon");
    const alertContainer = document.getElementById("alertContainer");

    // Pastikan semua elemen ditemukan
    if (
        !registerForm ||
        !registerButton ||
        !registerText ||
        !registerIcon ||
        !registerLoadingIcon ||
        !alertContainer
    ) {
        console.error("Beberapa elemen tidak ditemukan di DOM");
        return;
    }

    registerForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        // Clear previous errors
        clearErrors();

        // Validasi form sebelum submit
        if (!validateForm()) {
            return;
        }

        // Show loading state
        setLoadingState(true);

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        try {
            const response = await axios.post("/api/auth/register", {
                name,
                email,
                password,
            });

            // Show success message
            showAlert(
                "success",
                "Registrasi berhasil! Mengalihkan ke halaman login..."
            );

            // Redirect to login page
            setTimeout(() => {
                window.location.href = "/login";
            }, 2000);
        } catch (error) {
            console.error("Register error:", error);

            if (error.response && error.response.data) {
                // Server responded with error
                if (error.response.data.errors) {
                    // Show validation errors
                    Object.keys(error.response.data.errors).forEach((field) => {
                        const errorMessage = Array.isArray(
                            error.response.data.errors[field]
                        )
                            ? error.response.data.errors[field][0]
                            : error.response.data.errors[field];
                        showFieldError(field, errorMessage);
                    });
                } else {
                    const errorMessage =
                        error.response.data.message || "Registrasi gagal!";
                    showAlert("error", errorMessage);
                }
            } else if (error.request) {
                // Request was made but no response received
                showAlert(
                    "error",
                    "Tidak ada respon dari server. Silakan periksa koneksi internet Anda dan coba lagi."
                );
            } else {
                // Something happened in setting up the request
                showAlert(
                    "error",
                    "Terjadi kesalahan sistem. Silakan coba lagi."
                );
            }
        } finally {
            setLoadingState(false);
        }
    });

    function validateForm() {
        let isValid = true;

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        // Validasi nama
        if (!name) {
            showFieldError("name", "Nama lengkap wajib diisi");
            isValid = false;
        } else if (name.length < 2) {
            showFieldError("name", "Nama minimal 2 karakter");
            isValid = false;
        }

        // Validasi email
        if (!email) {
            showFieldError("email", "Email wajib diisi");
            isValid = false;
        } else if (!isValidEmail(email)) {
            showFieldError("email", "Format email tidak valid");
            isValid = false;
        }

        // Validasi password
        if (!password) {
            showFieldError("password", "Password wajib diisi");
            isValid = false;
        } else if (password.length < 6) {
            showFieldError("password", "Password minimal 6 karakter");
            isValid = false;
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function setLoadingState(loading) {
        registerButton.disabled = loading;

        if (registerText) {
            registerText.textContent = loading
                ? "Memproses..."
                : "Daftar Sekarang";
        }

        if (registerIcon) {
            registerIcon.classList.toggle("hidden", loading);
        }

        if (registerLoadingIcon) {
            registerLoadingIcon.classList.toggle("hidden", !loading);
        }
    }

    function showFieldError(field, message) {
        const fieldElement = document.getElementById(`${field}-error`);
        const inputElement = document.getElementById(field);

        if (fieldElement) {
            fieldElement.textContent = message;
            fieldElement.classList.remove("hidden");
        }

        // Tambahkan styling error pada input
        if (inputElement) {
            inputElement.classList.add(
                "border-red-500",
                "focus:ring-red-500",
                "focus:border-red-500"
            );
            inputElement.classList.remove(
                "border-gray-300",
                "focus:ring-blue-500",
                "focus:border-transparent"
            );
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach((element) => {
            element.textContent = "";
            element.classList.add("hidden");
        });

        // Reset styling input
        const inputElements = document.querySelectorAll(
            'input[type="text"], input[type="email"], input[type="password"]'
        );
        inputElements.forEach((input) => {
            input.classList.remove(
                "border-red-500",
                "focus:ring-red-500",
                "focus:border-red-500"
            );
            input.classList.add(
                "border-gray-300",
                "focus:ring-blue-500",
                "focus:border-transparent"
            );
        });

        if (alertContainer) {
            alertContainer.classList.add("hidden");
            alertContainer.innerHTML = "";
        }
    }

    function showAlert(type, message) {
        if (!alertContainer) return;

        const alertClass =
            type === "success"
                ? "bg-green-50 border-green-200 text-green-800"
                : "bg-red-50 border-red-200 text-red-800";

        const iconPath =
            type === "success"
                ? "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                : "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z";

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
        alertContainer.classList.remove("hidden");
    }

    // Event listener untuk clear error saat user mulai mengetik
    const inputs = ["name", "email", "password"];
    inputs.forEach((inputId) => {
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.addEventListener("input", function () {
                const errorElement = document.getElementById(
                    `${inputId}-error`
                );
                if (
                    errorElement &&
                    !errorElement.classList.contains("hidden")
                ) {
                    errorElement.classList.add("hidden");
                    errorElement.textContent = "";

                    // Reset styling
                    this.classList.remove(
                        "border-red-500",
                        "focus:ring-red-500",
                        "focus:border-red-500"
                    );
                    this.classList.add(
                        "border-gray-300",
                        "focus:ring-blue-500",
                        "focus:border-transparent"
                    );
                }
            });
        }
    });
});

// Setup password toggle functionality
const passwordToggleBtn = document.querySelector(
    'button[onclick*="togglePassword"]'
);
if (passwordToggleBtn) {
    // Remove onclick attribute dan tambahkan event listener
    passwordToggleBtn.removeAttribute("onclick");
    passwordToggleBtn.addEventListener("click", function () {
        togglePassword("password");
    });
}

// Function untuk toggle password visibility
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(`${fieldId}-eye-open`);
    const eyeClosed = document.getElementById(`${fieldId}-eye-closed`);

    if (!passwordField || !eyeOpen || !eyeClosed) {
        console.error("Elemen password toggle tidak ditemukan");
        return;
    }

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeOpen.classList.add("hidden");
        eyeClosed.classList.remove("hidden");
    } else {
        passwordField.type = "password";
        eyeOpen.classList.remove("hidden");
        eyeClosed.classList.add("hidden");
    }
}

// Alternatif: Buat function global jika ingin tetap menggunakan onclick di HTML
window.togglePassword = function (fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(`${fieldId}-eye-open`);
    const eyeClosed = document.getElementById(`${fieldId}-eye-closed`);

    if (!passwordField || !eyeOpen || !eyeClosed) {
        console.error("Elemen password toggle tidak ditemukan");
        return;
    }

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeOpen.classList.add("hidden");
        eyeClosed.classList.remove("hidden");
    } else {
        passwordField.type = "password";
        eyeOpen.classList.remove("hidden");
        eyeClosed.classList.add("hidden");
    }
};
