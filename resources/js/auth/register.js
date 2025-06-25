import axios from "../axios";

document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
    const registerButton = document.getElementById("registerButton");
    const registerText = document.getElementById("registerText");
    const registerIcon = document.getElementById("registerIcon");
    const registerLoadingIcon = document.getElementById("registerLoadingIcon");
    const alertContainer = document.getElementById("alertContainer");

    registerForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        // Clear previous errors
        clearErrors();

        // Show loading state
        setLoadingState(true);

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const response = await axios.post("/auth/register", {
                name,
                email,
                password,
            });

            // Show success message
            showAlert("success", "Register berhasil!");

            // Redirect to login page
            setTimeout(() => {
                window.location.href = "/login";
            }, 1500);
        } catch (error) {
            console.error("Register error:", error);

            if (error.response) {
                // Server responded with a status code outside 2xx
                if (error.response.data.errors) {
                    // Show validation errors
                    Object.keys(error.response.data.errors).forEach((field) => {
                        showFieldError(
                            field,
                            error.response.data.errors[field][0]
                        );
                    });
                } else {
                    showAlert(
                        "error",
                        error.response.data.message || "Register gagal!"
                    );
                }
            } else if (error.request) {
                // Request was made but no response received
                showAlert(
                    "error",
                    "Tidak ada respon dari server. Silakan coba lagi."
                );
            } else {
                // Something happened in setting up the request
                showAlert("error", "Terjadi kesalahan. Silakan coba lagi.");
            }
        } finally {
            setLoadingState(false);
        }
    });

    function setLoadingState(loading) {
        registerButton.disabled = loading;
        registerText.textContent = loading ? "Memproses..." : "Daftar Sekarang";
        registerIcon.classList.toggle("hidden", loading);
        registerLoadingIcon.classList.toggle("hidden", !loading);
    }

    function showFieldError(field, message) {
        const fieldElement = document.getElementById(`${field}-error`);
        if (fieldElement) {
            fieldElement.textContent = message;
            fieldElement.classList.remove("hidden");
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach((element) => {
            element.textContent = "";
            element.classList.add("hidden");
        });
        alertContainer.classList.add("hidden");
    }

    function showAlert(type, message) {
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
});

function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(`${fieldId}-eye-open`);
    const eyeClosed = document.getElementById(`${fieldId}-eye-closed`);

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
