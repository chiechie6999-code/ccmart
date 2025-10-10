document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        // Age calculation
        const birthdateInput = document.getElementById('birthdate');
        const ageInput = document.getElementById('age');
        birthdateInput.addEventListener('change', function() {
            const birthdate = new Date(this.value);
            if (!isNaN(birthdate)) {
                const today = new Date();
                let age = today.getFullYear() - birthdate.getFullYear();
                const m = today.getMonth() - birthdate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }
                ageInput.value = age;
                validateAge(age);
            } else {
                ageInput.value = '';
            }
        });

        // Username availability check
        const usernameInput = document.getElementById('username');
        const usernameError = document.getElementById('username_error');
        usernameInput.addEventListener('blur', function() {
            const username = this.value;
            if (username.length > 0) {
                fetch('php/check_username.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'username=' + encodeURIComponent(username)
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'taken') {
                        usernameError.textContent = 'Username is already taken.';
                    } else {
                        usernameError.textContent = '';
                    }
                });
            }
        });

        // Password strength meter
        const passwordInput = document.getElementById('password');
        const passwordStrengthDiv = document.getElementById('password-strength');
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            let strengthText = 'Weak';
            let color = 'red';
            if (strength >= 5) {
                strengthText = 'Strong';
                color = 'green';
            } else if (strength >= 3) {
                strengthText = 'Medium';
                color = 'orange';
            }
            passwordStrengthDiv.textContent = 'Strength: ' + strengthText;
            passwordStrengthDiv.style.color = color;
        });

        registerForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.error').forEach(el => el.textContent = '');

            // ID Number Validation
            const idNumber = document.getElementById('id_number').value;
            if (!/^\d{4}-\d{4}$/.test(idNumber)) {
                document.getElementById('id_number_error').textContent = 'ID Number must be in the format xxxx-xxxx.';
                isValid = false;
            }

            // Name Fields Validation
            ['first_name', 'middle_name', 'family_name'].forEach(fieldName => {
                const input = document.getElementById(fieldName);
                const errorEl = document.getElementById(fieldName + '_error');
                if (input.value && !validateName(input.value)) {
                    errorEl.textContent = 'Invalid name format. Check requirements.';
                    isValid = false;
                }
            });

            // Age Validation
            if (!validateAge(parseInt(ageInput.value, 10))) {
                isValid = false;
            }

            // Password Match
            const rePasswordInput = document.getElementById('re_password');
            if (passwordInput.value !== rePasswordInput.value) {
                document.getElementById('re_password_error').textContent = 'Passwords do not match.';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Login page logic
    const loginContainer = document.querySelector('.container[data-login-attempts]');
    if (loginContainer) {
        const loginForm = document.getElementById('login-form');
        const showPasswordCheckbox = document.getElementById('show-password');
        const passwordInput = document.getElementById('password');
        const forgotPasswordContainer = document.getElementById('forgot-password-container');
        const loginButton = document.getElementById('login-button');
        const registerLink = document.getElementById('register-link');
        const errorMessageDiv = document.getElementById('login-error-message');

        // Show/hide password
        showPasswordCheckbox.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        // Show "Forgot Password?" link
        const loginAttempts = parseInt(loginContainer.dataset.loginAttempts, 10);
        if (loginAttempts >= 2) {
            forgotPasswordContainer.style.display = 'block';
        }

        // Handle lockout timer
        const lockoutTime = parseInt(loginContainer.dataset.lockoutTime, 10);
        const currentTime = parseInt(loginContainer.dataset.currentTime, 10);

        if (lockoutTime > currentTime) {
            let remainingTime = lockoutTime - currentTime;

            // Disable form elements
            loginButton.disabled = true;
            registerLink.style.pointerEvents = 'none';
            registerLink.style.color = 'grey';

            const timerInterval = setInterval(() => {
                if (remainingTime > 0) {
                    errorMessageDiv.textContent = `Too many failed login attempts. Please try again in ${remainingTime} seconds.`;
                    remainingTime--;
                } else {
                    clearInterval(timerInterval);
                    errorMessageDiv.textContent = 'You can now try to log in again.';
                    loginButton.disabled = false;
                    registerLink.style.pointerEvents = 'auto';
                    registerLink.style.color = ''; // Revert to default color
                }
            }, 1000);
        }
    }
});

function validateName(name) {
    // This function provides client-side feedback. The server-side validation is the source of truth.
    if (!name) return true; // for optional fields

    // Rule: Allow letters, spaces, dots, apostrophes
    if (!/^[a-zA-Z\s\.\']*$/.test(name)) {
        return false;
    }
    // Rule: No numbers
    if (/\d/.test(name)) {
        return false;
    }
    // Rule: No double spaces
    if (/\s\s/.test(name)) {
        return false;
    }
    // Rule: Not all capital letters (if longer than a single initial)
    if (name.length > 1 && name === name.toUpperCase()) {
        return false;
    }
    // Rule: No three consecutive same letters (case-insensitive)
    if (/([a-zA-Z])\1\1/i.test(name)) {
        return false;
    }

    // The strict capitalization rule is handled by the server, as it has more complex exceptions.
    // This prevents the client from incorrectly flagging valid names like "O'Malley" or "John M.".
    return true;
}

function validateAge(age) {
    const birthdateError = document.getElementById('birthdate_error');
    if (isNaN(age) || age < 18) {
        birthdateError.textContent = 'You must be at least 18 years old to register.';
        return false;
    } else {
        birthdateError.textContent = '';
        return true;
    }
}