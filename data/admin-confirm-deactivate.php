<?php
session_start();
require_once __DIR__ . '/../model/AdminModel.php';

$admin = new AdminModel();

if (isset($_POST['see'])) {
    $id = $_POST['id'] ?? '';
    if (!$id) {
        echo "Id invalid";
        exit;
    }
?>

    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>
    <input type="hidden" name="" id="user_id" value="<?php echo $id ?>">
    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to deactivate this account?</h3>
    <button type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center confirm-deactivate">
        Yes, I'm sure
    </button>
    <button onclick="window.deactivateInstance.hide()" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>


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
            $('.confirm-deactivate').on('click', function() {
                const id = $('#user_id').val();

                $.ajax({
                    url: '../../data/admin-deactivate.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            showToast('User is now ' + res.success);
                            users();
                            window.deactivateInstance.hide();
                        } else {
                            showToast(res.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast('Something went wrong!');
                    }
                });
            })
        })
    </script>

<?php } ?>