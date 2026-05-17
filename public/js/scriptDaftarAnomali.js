document.addEventListener("DOMContentLoaded", function () {
  // untuk halaman daftar anomali
  const parentAccordionKec = document.getElementById("accordionAnomaliKec");
  const isEdit = document.getElementById("isEdit");
  const filterLevel = document.getElementById("filter-level");
  // const filterWilayah = document.getElementsById("filter-wilayah");
  const filterKode = document.getElementById("filter-kategori");
  const filterFlag = document.getElementById("filter-flag");

  const loadingContent =
    '<div class="d-flex justify-content-center"><h1>Loading<span class="animated-dots"></span></h1></div></div>';
  const savedContent =
    '<i class="text-success bi bi-check-circle-fill"></i> Saved!';

  //untuk halaman manajemen anomali
  // cont;

  // untuk hamanan daftar anomali
  if (parentAccordionKec) {
    // --- A. Logic saat Accordion DIBUKA (Uncollapse) ---
    // Gunakan event delegation atau listen langsung ke event Bootstrap
    parentAccordionKec.addEventListener("show.bs.collapse", function (event) {
      event.stopPropagation();
      // target elemen adalah, elemen yg di klik
      // target elemen adalah accordion-collaps yg dibuka.
      const targetElement = event.target;
      if (!targetElement.classList.contains("accordion-collapse")) {
        return;
      }
      // mendapatkan id elemen yg di klik
      // const anomaliId = targetElement.getAttribute("id");
      // console.log(anomaliId);
      // Ambil elemen pembungkus (accordion-item) untuk mendapatkan data-id
      const item = targetElement.closest(".accordion-item");
      if (!item) return;
      const id = item.getAttribute("data-id");
      const isCompleted = item.getAttribute("data-is-completed");

      // const anomaliId = targetElement.getAttribute("id");
      // console.log(anomaliId);

      // mendapatkan container untuk mengisi isian
      const loadContainer = targetElement.querySelector(".data-load-container");
      const anomContainer = "";
      console.log(id);

      // 1. Periksa apakah data sudah dimuat sebelumnya
      if (targetElement.getAttribute("data-loaded") === "true") {
        return; // Jika sudah dimuat, jangan panggil AJAX lagi
      }

      // 2. Tampilkan pesan loading
      loadContainer.innerHTML = loadingContent;

      // 3. Panggil Endpoint CI4 via AJAX
      param = new URLSearchParams({
        "fil-level": filterLevel.value,
        "id-anomali": id,
        "fil-kategori": filterKode.value,
        "fil-flag": filterFlag.value,
        "is-edit": isEdit.value,
        "is-completed": isCompleted,
      });
      fetch(`${BASE_URL}/anomali/listDetil?${param}`)
        // fetch(`/anomali/getListKec`)
        .then((response) => response.text())
        .then((htmlContent) => {
          // 4. Render konten accordion bersarang
          loadContainer.innerHTML = htmlContent;
          targetElement.setAttribute("data-loaded", "true");
        })
        .then(() => {
          const anomContainer = targetElement.querySelector(".container-Anom");
          if (anomContainer) {
            setupFormListeners(anomContainer);
          }
        })
        .catch((error) => {
          loadContainer.innerHTML = loadingContent;
        });
    });

    // --- B. Logic saat Accordion DITUTUP (Collapse) ---
    parentAccordionKec.addEventListener("hide.bs.collapse", function (event) {
      event.stopPropagation();
      const targetElement = event.target;
      const loadContainer = targetElement.querySelector(".data-load-container");

      // 1. Hapus isi (Clear Content)
      loadContainer.innerHTML = loadingContent;
      if (loadContainer) {
        loadContainer.innerHTML = loadingContent;
        targetElement.setAttribute("data-loaded", "false");
      }

      // 2. Reset status loaded
      targetElement.setAttribute("data-loaded", "false");
    });
  }

  function setupFormListeners(container) {
    // 1. Cari form HANYA di dalam container yang diberikan
    // console.log(container);
    const forms = container.querySelectorAll(".anomali-form-submit");

    console.log(`Menemukan ${forms.length} form untuk dipasangi listener.`);

    forms.forEach((form) => {
      form.addEventListener("submit", function (event) {
        event.preventDefault(); // Mencegah submit form tradisional (refresh halaman)

        const formData = new FormData(form);
        const anomaliId = formData.get("id");
        const anomaliKonfirmasi = formData.get("konfirmasi");
        const kondisiLapangan = formData.get("kondisi_lapangan");

        // Perhatikan: Anda mungkin perlu ID unik lain jika ada beberapa tombol save
        // Jika ID tombol sama untuk semua form, gunakan form.querySelector
        const saveButton = form.querySelector("#submit-button");

        // Pastikan feedbackElement juga dicari secara dinamis,
        // karena ID-nya bergantung pada anomaliId
        const feedbackElement = document.getElementById(
          `feedback-${anomaliId}`,
        );

        // Periksa apakah saveButton dan feedbackElement ditemukan sebelum melanjutkan
        if (!saveButton || !feedbackElement) {
          console.error("Elemen tombol atau feedback tidak ditemukan!");
          return; // Hentikan fungsi jika elemen vital tidak ditemukan
        }

        // Tampilkan status loading
        saveButton.disabled = true;
        saveButton.textContent = "Saving...";
        feedbackElement.innerHTML = ""; // Kosongkan feedback sebelumnya
        feedbackElement.innerHTML = "Mencoba Mengirim"; // Kosongkan feedback sebelumnya

        // 2. Kirim data menggunakan Fetch API
        fetch(`${BASE_URL}/anomali/updateKonfirmasi`, {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            // 3. Handle Feedback Berhasil/Gagal
            if (data.status === "success") {
              feedbackElement.innerHTML = savedContent;
              // form.classList.add("bg-success", "bg-opacity-10");
              // setTimeout(
              //   () => form.classList.remove("bg-success", "bg-opacity-10"),
              //   2000,
              // );
            } else {
              feedbackElement.innerHTML = `<span class="text-danger">Error: ${data.message}</span>`;
            }
          })
          .catch((error) => {
            feedbackElement.innerHTML =
              '<span class="text-danger">Koneksi Error.</span>';
            console.error("Error:", error);
          })
          .finally(() => {
            // 4. Kembalikan tombol ke keadaan normal
            saveButton.disabled = false;
            saveButton.textContent = "Saved";
          });
      });
    });
  }
});
