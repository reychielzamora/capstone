<?php
session_start();
require_once __DIR__ . '/../model/AdminModel.php';

$admin = new AdminModel();

if (isset($_POST['see'])) {
    $id = $_POST['id'];
    if (!$id) {
        echo "Id invalid";
        exit;
    }
    $user = $admin->seeUserInfo($id);
    function e($user)
    {
        return htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
    }
?>
    <style>
        .input {
            @apply bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5;
        }
    </style>


    <form>
        <div class="grid gap-6 mb-6 md:grid-cols-3">
            <div>
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First name</label>
                <input type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" value="<?= e($user['FNAME']) ?>" />
            </div>
            <div>
                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last name</label>
                <input type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Doe" value="<?= e($user['LNAME']) ?>" />
            </div>
            <div>
                <label for="company" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Middle name</label>
                <input type="text" id="company" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Flowbite" value="<?= e($user['MNAME']) ?>" />
            </div>
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-4">
            <div>
                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                <input type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Doe" value="<?= e($user['SEX']) ?>" />
            </div>
            <div>
                <label for="company" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                <input type="text" id="company" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Flowbite" value="<?= e($user['CIVIL']) ?>" />
            </div>
            <div>
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Age</label>
                <input type="tel" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" value="<?= e($user['AGE']) ?>" />
            </div>
            <div>
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Citizenship</label>
                <input type="tel" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" value="<?= e($user['CITIZEN']) ?>" />
            </div>
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact</label>
                <input type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" value="<?= e($user['CONTACT']) ?>" />
            </div>
            <div>
                <label for="website" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="url" id="website" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="flowbite.com" value="<?= e($user['u_email']) ?>" />
            </div>
        </div>

        <div class="mb-6">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
            <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="john.doe@company.com" value="<?= e($user['STREET'] . ', ' . $user['BARANGAY_NAME'] . ', ' . $user['CITY']) ?>" />
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Profile</label>

                <?php
                $profilePic = $user['PP'] === NULL
                    ? "/bais-documents/uploads/" . $user['PP']
                    : "/bais-documents/assets/images/Default.png"; 
                ?>
                <img src="<?= e($profilePic) ?>" alt="Profile Picture" class="w-12 h-12 rounded-full">
            </div>
            <div>
                <label for="website" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Signature</label>
                <img class="h-auto max-w-xs" src="/bais-documents/uploads/<?= e($user['SIGNATURE'] ?? 'N/A') ?>" alt="image description">
            </div>
        </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>



    <section class="bg-white dark:bg-gray-700">
        <div class="max-w-2xl px-4 py-2 mx-auto">
            <div class="grid gap-4 sm:grid-cols-2">

                <!-- REQUEST INFO -->
                <div class="sm:col-span-2 mt-4">
                    <h3 class="font-semibold text-gray-700">Request Info</h3>
                </div>

                <div class="sm:col-span-2">
                    <label class="text-sm font-medium">Purpose</label>
                    <span class="underline"></span>
                    <input disabled class="input" value="<?= e($user['PURPOSE']) ?>">
                </div>

                <div>
                    <label class="text-sm font-medium">Control #</label>
                    <span class="underline"></span>
                    <input disabled class="input" value="<?= e($user['CTRL_NUM']) ?>">
                </div>

                <div>
                    <label class="text-sm font-medium">Request Status</label>
                    <span class="underline"></span>
                    <input disabled class="input" value="<?= e($user['REQ_STATUS']) ?>">
                </div>

                <!-- LETTER -->
                <div class="sm:col-span-2">
                    <h3 class="font-medium">Letter</h3>
                    <img class="rounded-lg mt-2"
                        src="/bais-documents/uploads/<?= e($user['LETTER']) ?? 'N/A' ?>">
                </div>

                <!-- PHOTO -->
                <div>
                    <h3 class="font-medium">Photo</h3>
                    <img class="rounded-lg mt-2">
                </div>

                <!-- SIGNATURE -->
                <div>
                    <h3 class="font-medium">Signature</h3>
                    <img class="rounded-lg mt-2">
                </div>

            </div>
        </div>
    </section>

    <script>
        function showToast(msg) {
            Toastify({
                text: msg,
                className: "info",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        }


        $(function() {
            $('.update-info-off').on('click', function(e) {
                e.preventDefault();
                const formData = new FormData();

                formData.append('update_off', true);
                formData.append('fname', $('#fname').val());
                formData.append('mname', $('#mname').val());
                formData.append('lname', $('#lname').val());
                formData.append('dob', $('#dob').val());
                formData.append('pob', $('#pob').val());
                formData.append('cs', $('#cs').val());
                formData.append('email', $('#email').val());
                formData.append('contact', $('#contact').val());
                formData.append('position', $('#position').val());
                formData.append('brgy', $('#brgy').val());
                formData.append('otitle', $('#otitle').val());
                formData.append('emp_id', $('#emp_id').val());
                formData.append('old_photo', $('#old_photo').val());
                formData.append('id', $(this).data('off_id'));

                const fileInput = document.getElementById('photo_profile');
                if (fileInput.files.length > 0) {
                    formData.append('photo', fileInput.files[0]);
                }

                if (confirm('Update official?')) {
                    $.ajax({
                        url: '../../data/staff-update-info.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,

                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                showToast(response.success);
                                window.updateModalInstance.hide();
                            } else if (response.error) {
                                showToast(response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                            showToast("Error: " + error);
                        }
                    });
                }
            })
        })
    </script>
<?php } ?>