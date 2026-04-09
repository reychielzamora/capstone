    <?php
    include('header.php');
    require_once 'require-modal.php';
    ?>

    <section class="bg-white dark:bg-gray-900">
        <div class="container px-6 py-10 mx-auto">
            <h1 class="text-2xl font-semibold text-center text-gray-800 capitalize lg:text-3xl dark:text-white">
                Available <br> <span class="text-blue-500">Certificates</span>
            </h1>

            <div class="grid grid-cols-1 gap-8 mt-8 xl:mt-12 xl:gap-16 md:grid-cols-2 xl:grid-cols-3">
                <?php if (!$certificates): ?>
                    <p class="text-center text-gray-500">No data</p>
                <?php else: ?>
                    <?php foreach ($certificates as $cert):
                        $certId = $cert['CERT_ID'];
                        $key = 'certificate-id';
                        $method = 'AES-256-CBC';

                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
                        $encrypted = openssl_encrypt($certId, $method, $key, 0, $iv);
                        $encryptedCertId = base64_encode($iv . $encrypted);
                    ?>
                        <div class="flex flex-col items-center p-6 space-y-3 text-center bg-gray-100 rounded-xl dark:bg-gray-800">
                            <span class="inline-block p-3 text-blue-500 bg-blue-100 rounded-full dark:text-white dark:bg-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </span>

                            <h1 class="text-xl font-semibold text-gray-700 capitalize dark:text-white">
                                <?= htmlspecialchars($cert['CERT_NAME']) ?>
                            </h1>

                            <a data-modal-target="popup-modal" data-modal-toggle="popup-modal" href="#"
                                class="flex items-center -mx-1 text-sm text-blue-500 capitalize transition-colors duration-300 transform dark:text-blue-400 hover:underline hover:text-blue-600 dark:hover:text-blue-500">
                                <span class="mx-1">Get</span>
                                <svg class="w-4 h-4 mx-1 rtl:-scale-x-100" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include('footer.php') ?>