document.addEventListener("DOMContentLoaded", function () {
  const parentAccordionKec = document.getElementById("accordionAnomaliKec");
  const parentAccordionDes = document.getElementById("listAnomIndividu");

  if (parentAccordionKec) {
    // --- A. Logic saat Accordion DIBUKA (Uncollapse) ---
    parentAccordionKec.addEventListener("show.bs.collapse", function (event) {
      const targetElement = event.target;
      console.log(targetElement);
      const anomaliId = targetElement.getAttribute("id");
      console.log(anomaliId);
      const loadContainer = targetElement.querySelector(".data-load-container");

      // 1. Periksa apakah data sudah dimuat sebelumnya
      if (targetElement.getAttribute("data-loaded") === "true") {
        return; // Jika sudah dimuat, jangan panggil AJAX lagi
      }

      // 2. Tampilkan pesan loading
      loadContainer.innerHTML =
        '<p class="text-center">⏳ Sedang memuat detail anomali...</p>';

      // 3. Panggil Endpoint CI4 via AJAX
      fetch(`/anomali/getListWilAnom/${anomaliId}`)
        // fetch(`/anomali/getListKec`)
        .then((response) => response.text())
        .then((htmlContent) => {
          // 4. Render konten accordion bersarang
          loadContainer.innerHTML = htmlContent;
          targetElement.setAttribute("data-loaded", "true");
        })
        .catch((error) => {
          loadContainer.innerHTML =
            '<p class="text-danger">❌ Gagal memuat data.</p>';
        });

      // C. Logic ketika sudah dapat accordion-anomali
      const anomContainer = targetElement.querySelector(".container-Ruta");
      if (anomContainer) {
        // --- C. Logic saat Pencet Submit ---
        anomContainer.addEventListener("submit", function (event) {
          const forms = document.querySelectorAll(".anomali-form-submit");
          console.log(forms);
          event.preventDefault(); // Mencegah submit form tradisional (refresh halaman)

          forms.forEach((form) => {
            form.addEventListener("submit", function (event) {
              event.preventDefault(); // Mencegah submit form tradisional (refresh halaman)

              const formData = new FormData(form);
              const anomaliId = formData.get("id"); // Ambil ID dari hidden input
              console.log(anomaliId);
              const anomaliKonfirmasi = formData.get("konfirmasi"); // Ambil ID dari hidden input
              console.log(anomaliKonfirmasi);
              const saveButton = form.querySelector("#submit-button");
              const feedbackElement = document.getElementById(`feedback-${anomaliId}`);

              // Tampilkan status loading
              saveButton.disabled = true;
              saveButton.textContent = "Saving...";
              feedbackElement.innerHTML = ""; // Kosongkan feedback sebelumnya

              // 2. Kirim data menggunakan Fetch API
              fetch("/anomali/updateKonfirmasi", {
                method: "POST",
                body: formData, // Langsung kirim FormData
                // Anda mungkin perlu menambahkan headers jika menggunakan CSRF protection
              })
                .then((response) => response.json()) // Asumsikan Controller mengembalikan JSON
                .then((data) => {
                  // 3. Handle Feedback Berhasil
                  console.log(data)
                  if (data.status === "success") {
                    feedbackElement.innerHTML =
                      '<i class="text-success bi bi-check-circle-fill"></i> Saved!';
                    // Optional: Highlight baris yang sukses
                    form.classList.add("bg-success", "bg-opacity-10");
                    setTimeout(
                      () => form.classList.remove("bg-success", "bg-opacity-10"),
                      2000
                    );
                  } else {
                    // 4. Handle Feedback Gagal (Validasi)
                    feedbackElement.innerHTML = `<span class="text-danger">Error: ${data.message}</span>`;
                  }
                })
                .catch((error) => {
                  // Handle kesalahan koneksi
                  feedbackElement.innerHTML =
                    '<span class="text-danger">Koneksi Error.</span>';
                  console.error("Error:", error);
                })
                .finally(() => {
                  // 5. Kembalikan tombol ke keadaan normal
                  saveButton.disabled = false;
                  saveButton.textContent = "Save";
                });
            });
  });
        });
      }
    });

    // --- B. Logic saat Accordion DITUTUP (Collapse) ---
    parentAccordionKec.addEventListener("hide.bs.collapse", function (event) {
      const targetElement = event.target;
      const loadContainer = targetElement.querySelector(".data-load-container");

      // 1. Hapus isi (Clear Content)
      loadContainer.innerHTML =
        '<p class="text-center">Data telah dihapus.</p>';

      // 2. Reset status loaded
      targetElement.setAttribute("data-loaded", "false");
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // 1. Ambil semua form yang perlu di-submit via AJAX
  const forms = document.querySelectorAll(".anomali-form-submit");
  console.log(forms);

  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      event.preventDefault(); // Mencegah submit form tradisional (refresh halaman)

      const formData = new FormData(form);
      const anomaliId = formData.get("id"); // Ambil ID dari hidden input
      const saveButton = form.querySelector(".btn-save-konfirmasi");
      const feedbackElement = document.getElementById(`feedback-${anomaliId}`);

      // Tampilkan status loading
      saveButton.disabled = true;
      saveButton.textContent = "Saving...";
      feedbackElement.innerHTML = ""; // Kosongkan feedback sebelumnya

      // 2. Kirim data menggunakan Fetch API
      fetch("/anomali/updateKonfirmasi", {
        method: "POST",
        body: formData, // Langsung kirim FormData
        // Anda mungkin perlu menambahkan headers jika menggunakan CSRF protection
      })
        .then((response) => response.json()) // Asumsikan Controller mengembalikan JSON
        .then((data) => {
          // 3. Handle Feedback Berhasil
          if (data.status === "success") {
            feedbackElement.innerHTML =
              '<i class="text-success bi bi-check-circle-fill"></i> Saved!';
            // Optional: Highlight baris yang sukses
            form.classList.add("bg-success", "bg-opacity-10");
            setTimeout(
              () => form.classList.remove("bg-success", "bg-opacity-10"),
              2000
            );
          } else {
            // 4. Handle Feedback Gagal (Validasi)
            feedbackElement.innerHTML = `<span class="text-danger">Error: ${data.message}</span>`;
          }
        })
        .catch((error) => {
          // Handle kesalahan koneksi
          feedbackElement.innerHTML =
            '<span class="text-danger">Koneksi Error.</span>';
          console.error("Error:", error);
        })
        .finally(() => {
          // 5. Kembalikan tombol ke keadaan normal
          saveButton.disabled = false;
          saveButton.textContent = "Save";
        });
    });
  });
});
