<style>
    #--box {
        -webkit-box-shadow: -2.5px 0 14px 3px #dddddd;
        -moz-box-shadow: -2.5px 0 14px 3px #dddddd;
        box-shadow: -2.5px 0 14px 3px #dddddd;
    }
</style>


<div class="w-full p-5 sm:p-8 md:p-16 dark:bg-gray-900 bg-gray-200">

    <h1 class="text-5xl font-extrabold text-center lg:text-7xl ">
        <span class="text-transparent bg-gradient-to-br bg-clip-text from-teal-500 via-indigo-500 to-sky-500 dark:from-teal-200 dark:via-indigo-300 dark:to-sky-500">
            Coming
        </span>

        <span class="text-transparent bg-gradient-to-tr bg-clip-text from-blue-500 via-pink-500 to-red-500 dark:from-sky-300 dark:via-pink-300 dark:to-red-500">
            Soon
        </span>
    </h1>

    <br>
    <br>

    <section id="--box" class="bg-white dark:bg-gray-900 border-2 rounded-sm border-gray-200 dark:border-gray-600">
        <div class="container px-6 py-12 mx-auto">
            <div>
                <p class="font-medium text-blue-500 dark:text-blue-400">Tracking</p>

                <h1 class="mt-2 text-2xl font-semibold text-gray-800 md:text-3xl dark:text-white">Track your requests</h1>

                <p class="mt-3 text-gray-500 dark:text-gray-400">Input your control number to see the status of your requests.</p>
            </div>

            <div class="grid grid-cols-1 gap-12 mt-10 lg:grid-cols-2">
                <div class="p-4 py-6 rounded-lg bg-gray-50 dark:bg-gray-800 md:p-8">
                    <form>
                        <div class="mt-4">
                            <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Control number</label>
                            <input type="text" class="block w-full px-5 py-2.5 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                        </div>
                        <button class="w-full px-6 py-3 mt-4 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                            Track
                        </button>
                    </form>
                </div>
                <div class="grid grid-cols-1 gap-12 md:grid-cols-1">
                    <img class="object-cover object-center w-full rounded-xl" src="https://images.unsplash.com/photo-1499470932971-a90681ce8530?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="">

                </div>

            </div>
        </div>
    </section>
    <br>
    <section class=" ">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-8 mt-8 xl:mt-12 xl:gap-12 md:grid-cols-1 xl:grid-cols-1">
                <?php if (!empty($act)): ?>
                    <?php foreach ($act as $row): ?>

                        <?php
                        $allFiles = json_decode($row['FILES'], true) ?? [];
                        $imageFiles = [];
                        $docFiles = [];

                        foreach ($allFiles as $file) {
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                $imageFiles[] = $file;
                            } else {
                                $docFiles[] = $file;
                            }
                        }

                        $imageTotal = count($imageFiles);
                        $displayImages = array_slice($imageFiles, 0, 4);
                        ?>

                        <div class="p-8 dark:bg-gray-800 bg-white space-y-4 border-2 border-blue-400 dark:border-blue-300 rounded-xl hover:shadow-lg transition-all duration-300">

                            <h1 class="text-xl font-semibold text-gray-700 capitalize dark:text-white leading-tight">
                                <?= e($row['TITLE']) ?>
                            </h1>

                            <p class="text-gray-500 dark:text-gray-300 leading-relaxed">
                                <?= nl2br(e($row['DESCRIPTION'])) ?>
                            </p>

                            <?php if (!empty($imageFiles)): ?>
                                <div class="grid grid-cols-2 gap-2 mt-4 cursor-pointer">
                                    <?php foreach ($displayImages as $index => $img): ?>
                                        <div class="relative group" onclick="openGallery(<?= htmlspecialchars(json_encode($imageFiles)) ?>)">
                                            <img src="../uploads/activities/<?= e(basename($img)) ?>"
                                                class="w-full h-40 object-cover rounded-lg group-hover:opacity-90 transition-all duration-200"
                                                alt="Activity image <?= $index + 1 ?>"
                                                loading="lazy">

                                            <?php if ($index === 3 && $imageTotal > 4): ?>
                                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center rounded-lg opacity-0 opacity-100 transition-opacity">
                                                    <span class="text-white text-xl font-bold">
                                                        +<?= $imageTotal - 4 ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($docFiles)): ?>
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        📎 Attachments (<?= count($docFiles) ?>)
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($docFiles as $doc): ?>
                                            <?php
                                            $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION));
                                            $icon = match ($ext) {
                                                'pdf' => '📄',
                                                'docx' => '📝',
                                                'doc' => '📄',
                                                default => '📎'
                                            };
                                            $fileName = basename($doc);
                                            ?>
                                            <a href="../uploads/activities/<?= e($doc) ?>"
                                                download="<?= e($fileName) ?>"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs bg-gradient-to-r from-gray-100 to-gray-200 hover:from-blue-50 hover:to-blue-100 dark:from-gray-800 dark:to-gray-700 dark:hover:from-blue-900/20 dark:hover:to-blue-800/20 text-gray-800 dark:text-gray-200 rounded-full transition-all duration-200 hover:scale-105 shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-600">
                                                <?= $icon ?>
                                                <span class="truncate max-w-24"><?= e(substr($fileName, 0, 20)) ?><?= strlen($fileName) > 20 ? '...' : '' ?></span>
                                                <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10l-5.5 5.5m0 0L8 18l5.5-5.5M8 18l4.5-4.5M12 10l5.5 5.5" />
                                                </svg>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-20">
                        <iconify-icon icon="mdi:inbox-outline" class="w-24 h-24 text-gray-300 mx-auto mb-4"></iconify-icon>
                        <h3 class="text-xl font-semibold text-gray-500 dark:text-gray-400 mb-2">No activities yet</h3>
                        <p class="text-gray-400 dark:text-gray-500 mb-6">Get started by creating your first activity.</p>
                    </div>
                <?php endif; ?>
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



        function openGallery(images) {
            if (!images || images.length === 0) return;

            const modal = `
        <div id="imageGalleryModal" class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
            <button onclick="closeGallery()" class="absolute top-6 right-6 text-white text-3xl hover:text-gray-300">&times;</button>
            <div class="relative max-w-4xl max-h-screen overflow-auto">
                <div id="galleryImages" class="flex gap-4"></div>
                <button onclick="prevImage()" class="fixed left-16 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 p-2 rounded-full text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button onclick="nextImage()" class="fixed right-16 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 p-2 rounded-full text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    `;

            document.body.insertAdjacentHTML('beforeend', modal);
            renderGallery(images);
        }

        let currentImageIndex = 0;
        let galleryImages = [];

        function renderGallery(images) {
            galleryImages = images;
            const container = document.getElementById('galleryImages');
            container.innerHTML = images.map(img =>
                `<img src="../uploads/activities/${img}" class="max-h-screen max-w-full object-contain mx-auto rounded-lg shadow-2xl">`
            ).join('');
            showImage(0);
        }

        function showImage(index) {
            currentImageIndex = index;
            const images = document.querySelectorAll('#galleryImages img');
            images.forEach((img, i) => {
                img.style.display = i === index ? 'block' : 'none';
            });
        }

        function nextImage() {
            showImage((currentImageIndex + 1) % galleryImages.length);
        }

        function prevImage() {
            showImage((currentImageIndex - 1 + galleryImages.length) % galleryImages.length);
        }

        function closeGallery() {
            const modal = document.getElementById('imageGalleryModal');
            if (modal) modal.remove();
        }

        function deletePost(id) {
            const post = id;

            console.log(post)
        }
    </script>
</div>