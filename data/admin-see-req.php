<?php
include(__DIR__ . '/../model/Staff.php');
$admin = new AdminModel();
session_start();
if (isset($_POST['view'])) {
    $rid = $_POST['req_id'];
    $uid = $_POST['user_id'];

    $req = $admin->getRequests($rid, $uid);

    $_SESSION['USER_ID'] = $req['USER_ID'];
    $_SESSION['PI_ID'] = $req['PI_ID'];
    $_SESSION['REQ_ID'] = $req['REQ_ID'];
    $_SESSION['CERT_ID'] = $req['CERT_ID'];
    if ($req) { ?>

        <h1 class="text-2xl font-bold mb-4 dark:text-white text-gray-800"><?= htmlspecialchars($req['CTRL_NUM']) ?></h1>

        <section class="bg-white dark:bg-gray-700">
            <div class="max-w-2xl px-4 py-2 mx-auto ">
                <h2 class="mb-4 text-xl font-bold text-gray-700 dark:text-white">Request Information</h2>
                <form action="#">
                    <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full name</label>
                            <input disabled type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?= htmlspecialchars($req['FNAME'] . " " . $req['MNAME'] . " " . $req['LNAME']) ?>">
                        </div>
                        <div class="w-full">
                            <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Document</label>
                            <input type="text" name="brand" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Product brand" value="<?= htmlspecialchars($req['CERT_NAME']) ?>">
                        </div>
                        <div class="w-full">
                            <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date Requests</label>
                            <input type="text" name="brand" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Product brand" value="<?= htmlspecialchars($req['REQ_DATE']) ?>">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <input type="TEXT" name="price" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?= htmlspecialchars($req['BARANGAY'] . ", " . $req['STREET'] . " " . $req['CITY']) ?>">
                        </div>
                        <div class="sm:col-span-2">
                            <figure class="max-w-lg">
                                <h3>Letter</h3>
                                <img class="h-auto max-w-full rounded-base" src="/bais-documents/uploads/<?= htmlspecialchars($req['LETTER']); ?>" alt="image description">
                            </figure>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="../staff/verify"
                        target="_blank"
                        id="accept-req"
                        type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Proceed
                        </a>
                        <button type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                            Decline
                        </button>
                    </div>
                </form>
            </div>
        </section>
    <?php } else { ?>
        <p>No data found</p>
<?php }
}
?>