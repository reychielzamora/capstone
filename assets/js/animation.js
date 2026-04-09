
const login = document.getElementById("signin");
const signup = document.getElementById("signup");

function changetoSignup() {

    login.classList.remove("opacity-100", "translate-y-0");
    login.classList.add("opacity-0", "translate-y-4", "pointer-events-none");

    signup.classList.remove("opacity-0", "translate-y-4", "pointer-events-none");
    signup.classList.add("opacity-100", "translate-y-0");
}
function changetoSignin() {

    signup.classList.remove("opacity-100", "translate-y-0");
    signup.classList.add("opacity-0", "translate-y-4", "pointer-events-none");

    login.classList.remove("opacity-0", "translate-y-4", "pointer-events-none");
    login.classList.add("opacity-100", "translate-y-0");
}

document.addEventListener('DOMContentLoaded', () => {
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpForm = document.getElementById('otpForm');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Move to next input if a digit is entered
            if (e.data && /^\d$/.test(e.data)) { // Check if input data is a single digit
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            }
        });

        input.addEventListener('keydown', (e) => {
            // Move to previous input on backspace if current is empty
            if (e.key === 'Backspace' && input.value === '') {
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
        });
    });
});
