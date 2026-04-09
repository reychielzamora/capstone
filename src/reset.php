<div id="reset-otp-modal" class="relative flex justify-center hidden">
    <div class="fixed inset-0 z-10 overflow-y-auto bg-amber-50/70"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl rtl:text-right dark:bg-gray-900 sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>

                    <div class="mt-2 text-center">
                        <h2 class="text-2xl font-bold text-center mb-4 text-gray-800">Verify OTP</h2>
                        <p class="text-center text-gray-600 mb-6 text-sm">
                            A 6-digit verification code has been sent to your email.
                        </p>
                        <form id="" action="#" method="POST">
                            <input type="text" name="" id="email-see">
                            <div class="flex justify-center space-x-2 mb-6">
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
                                id="resetOtpForm"
                                    type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                                    Verify Code
                                </button>
                            </div>
                            <div class="text-center text-sm">
                                <a href="#" id="resend-otp" class="font-bold text-blue-500 hover:text-blue-800" onclick="alert('Resending OTP...')">
                                    Resend Code
                                </a>
                            </div>
                        </form>
                        <div class="text-center mt-6">
                            <a href="" id="cancel-submit" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
