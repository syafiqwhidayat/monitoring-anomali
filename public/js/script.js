document.addEventListener('DOMContentLoaded', function () {
    const parentAccordionKec = document.getElementById('accordionAnomaliKec');
    const parentAccordionDes = document.getElementById('listAnomIndividu');

    if (parentAccordionKec) {
        // --- A. Logic saat Accordion DIBUKA (Uncollapse) ---
        parentAccordionKec.addEventListener('show.bs.collapse', function (event) {
            const targetElement = event.target;
            console.log(targetElement);
            const anomaliId = targetElement.getAttribute('id');
            console.log(anomaliId);
            const loadContainer = targetElement.querySelector('.data-load-container');

            // 1. Periksa apakah data sudah dimuat sebelumnya
            if (targetElement.getAttribute('data-loaded') === 'true') {
                return; // Jika sudah dimuat, jangan panggil AJAX lagi
            }

            // 2. Tampilkan pesan loading
            loadContainer.innerHTML = '<p class="text-center">⏳ Sedang memuat detail anomali...</p>';

            // 3. Panggil Endpoint CI4 via AJAX
            fetch(`/anomali/getListKec/${anomaliId}`)
            // fetch(`/anomali/getListKec`)
                .then(response => response.text())
                .then(htmlContent => {
                    // 4. Render konten accordion bersarang
                    loadContainer.innerHTML = htmlContent;
                    targetElement.setAttribute('data-loaded', 'true');
                })
                .catch(error => {
                    loadContainer.innerHTML = '<p class="text-danger">❌ Gagal memuat data.</p>';
                });
        });

        // --- B. Logic saat Accordion DITUTUP (Collapse) ---
        parentAccordionKec.addEventListener('hide.bs.collapse', function (event) {
            const targetElement = event.target;
            const loadContainer = targetElement.querySelector('.data-load-container');

            // 1. Hapus isi (Clear Content)
            loadContainer.innerHTML = '<p class="text-center">Data telah dihapus.</p>';
            
            // 2. Reset status loaded
            targetElement.setAttribute('data-loaded', 'false');
        });
    }

    // if (parentAccordionDes) {
    //     // --- A. Logic saat Accordion DIBUKA (Uncollapse) ---
    //     parentAccordionDes.addEventListener('show.bs.collapse', function (event) {
    //         const targetElement = event.target;
    //         console.log(targetElement);
    //         const anomaliId = targetElement.getAttribute('id');
    //         console.log(anomaliId);
    //         const loadContainer = targetElement.querySelector('.data-load-container');

    //         // 1. Periksa apakah data sudah dimuat sebelumnya
    //         if (targetElement.getAttribute('data-loaded') === 'true') {
    //             return; // Jika sudah dimuat, jangan panggil AJAX lagi
    //         }

    //         // 2. Tampilkan pesan loading
    //         loadContainer.innerHTML = '<p class="text-center">⏳ Sedang memuat detail anomali...</p>';

    //         // 3. Panggil Endpoint CI4 via AJAX
    //         fetch(`/anomali/getListKec/${anomaliId}`)
    //         // fetch(`/anomali/getListKec`)
    //             .then(response => response.text())
    //             .then(htmlContent => {
    //                 // 4. Render konten accordion bersarang
    //                 loadContainer.innerHTML = htmlContent;
    //                 targetElement.setAttribute('data-loaded', 'true');
    //             })
    //             .catch(error => {
    //                 loadContainer.innerHTML = '<p class="text-danger">❌ Gagal memuat data.</p>';
    //             });
    //     });

    //     // --- B. Logic saat Accordion DITUTUP (Collapse) ---
    //     parentAccordionDes.addEventListener('hide.bs.collapse', function (event) {
    //         const targetElement = event.target;
    //         const loadContainer = targetElement.querySelector('.data-load-container');

    //         // 1. Hapus isi (Clear Content)
    //         loadContainer.innerHTML = '<p class="text-center">Data telah dihapus.</p>';
            
    //         // 2. Reset status loaded
    //         targetElement.setAttribute('data-loaded', 'false');
    //     });
    // }
});