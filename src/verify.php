<?php include('header.php') ?>
<div class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm animate-fade-in">
        <h2 class="text-2xl font-bold text-center mb-4 text-gray-800">Verify OTP</h2>
        <p class="text-center text-gray-600 mb-6 text-sm">
            A 6-digit verification code has been sent to your email (or phone number).
        </p>
        <form id="otpForm" action="#" method="POST">
            <div class="flex justify-center space-x-2 mb-6">
                <!-- OTP Input Fields -->
                <input type="text" id="otp-1" name="otp-1" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-2xl font-bold border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" id="otp-2" name="otp-2" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-2xl font-bold border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" id="otp-3" name="otp-3" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-2xl font-bold border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" id="otp-4" name="otp-4" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-2xl font-bold border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" id="otp-5" name="otp-5" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-2xl font-bold border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" id="otp-6" name="otp-6" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-2xl font-bold border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    inputmode="numeric" pattern="[0-9]*" required>
            </div>

            <div class="flex items-center justify-between mb-4">
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Verify Code
                </button>
            </div>
            <div class="text-center text-sm">
                <a href="#" class="font-bold text-blue-500 hover:text-blue-800" onclick="alert('Resending OTP...')">
                    Resend Code
                </a>
            </div>
        </form>
        <div class="text-center mt-6">
            <a href="login.html" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                Back to Login
            </a>
        </div>
    </div>
</div>


<?php include('footer.php') ?>