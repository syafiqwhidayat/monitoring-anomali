document.addEventListener("DOMContentLoaded", function () {
  // untuk halaman daftar anomali
  const parentAccordionKec = document.getElementById("accordionAnomaliKec");
  const deleteModal = document.getElementById("konfirHapus");
  const isEdit = window.location.href.includes("listEdit") ? "1" : "0";

  //untuk halaman manajemen anomali
  // cont;

  // untuk hamanan daftar anomali
  // if (parentAccordionKec) {
  //   // --- A. Logic saat Accordion DIBUKA (Uncollapse) ---
  //   parentAccordionKec.addEventListener("show.bs.collapse", function (event) {
  //     const targetElement = event.target;
  //     console.log(targetElement);
  //     const anomaliId = targetElement.getAttribute("id");
  //     console.log(anomaliId);
  //     const loadContainer = targetElement.querySelector(".data-load-container");
  //     const anomContainer = "";

  //     // 1. Periksa apakah data sudah dimuat sebelumnya
  //     if (targetElement.getAttribute("data-loaded") === "true") {
  //       return; // Jika sudah dimuat, jangan panggil AJAX lagi
  //     }

  //     // 2. Tampilkan pesan loading
  //     loadContainer.innerHTML =
  //       '<p class="text-center">⏳ Sedang memuat detail anomali...</p>';

  //     // 3. Panggil Endpoint CI4 via AJAX
  //     fetch(`/anomali/getListWilAnom/${anomaliId}/${isEdit}`)
  //       // fetch(`/anomali/getListKec`)
  //       .then((response) => response.text())
  //       .then((htmlContent) => {
  //         // 4. Render konten accordion bersarang
  //         loadContainer.innerHTML = htmlContent;
  //         targetElement.setAttribute("data-loaded", "true");
  //       })
  //       .then(() => {
  //         const anomContainer = targetElement.querySelector(".container-Art");
  //         if (anomContainer) {
  //           setupFormListeners(anomContainer);
  //         }
  //       })
  //       .catch((error) => {
  //         loadContainer.innerHTML =
  //           '<p class="text-danger">❌ Gagal memuat data.</p>';
  //       });

  //     // // C. Logic ketika sudah dapat accordion-anomali
  //     // this.anomContainer = targetElement.querySelector(".container-Ruta");
  //     // // console.log(anomContainer);
  //     // if (anomContainer) {
  //     //   // --- C. Logic saat Pencet Submit ---
  //     //   const forms = document.querySelectorAll(".anomali-form-submit");
  //     //   // anomContainer.addEventListener("submit", function (event) {
  //     //   console.log(forms);
  //     //   //   event.preventDefault(); // Mencegah submit form tradisional (refresh halaman)

  //     //   forms.forEach((form) => {
  //     //     form.addEventListener("submit", function (event) {
  //     //       event.preventDefault(); // Mencegah submit form tradisional (refresh halaman)

  //     //       const formData = new FormData(form);
  //     //       const anomaliId = formData.get("id"); // Ambil ID dari hidden input
  //     //       console.log(anomaliId);
  //     //       const anomaliKonfirmasi = formData.get("konfirmasi"); // Ambil ID dari hidden input
  //     //       console.log(anomaliKonfirmasi);
  //     //       const saveButton = form.querySelector("#submit-button");
  //     //       const feedbackElement = document.getElementById(
  //     //         `feedback-${anomaliId}`
  //     //       );

  //     //       // Tampilkan status loading
  //     //       saveButton.disabled = true;
  //     //       saveButton.textContent = "Saving...";
  //     //       feedbackElement.innerHTML = ""; // Kosongkan feedback sebelumnya

  //     //       // 2. Kirim data menggunakan Fetch API
  //     //       fetch("/anomali/updateKonfirmasi", {
  //     //         method: "POST",
  //     //         body: formData, // Langsung kirim FormData
  //     //         // Anda mungkin perlu menambahkan headers jika menggunakan CSRF protection
  //     //       })
  //     //         .then((response) => response.json()) // Asumsikan Controller mengembalikan JSON
  //     //         .then((data) => {
  //     //           // 3. Handle Feedback Berhasil
  //     //           console.log(data);
  //     //           if (data.status === "success") {
  //     //             feedbackElement.innerHTML =
  //     //               '<i class="text-success bi bi-check-circle-fill"></i> Saved!';
  //     //             // Optional: Highlight baris yang sukses
  //     //             form.classList.add("bg-success", "bg-opacity-10");
  //     //             setTimeout(
  //     //               () => form.classList.remove("bg-success", "bg-opacity-10"),
  //     //               2000
  //     //             );
  //     //           } else {
  //     //             // 4. Handle Feedback Gagal (Validasi)
  //     //             feedbackElement.innerHTML = `<span class="text-danger">Error: ${data.message}</span>`;
  //     //           }
  //     //         })
  //     //         .catch((error) => {
  //     //           // Handle kesalahan koneksi
  //     //           feedbackElement.innerHTML =
  //     //             '<span class="text-danger">Koneksi Error.</span>';
  //     //           console.error("Error:", error);
  //     //         })
  //     //         .finally(() => {
  //     //           // 5. Kembalikan tombol ke keadaan normal
  //     //           saveButton.disabled = false;
  //     //           saveButton.textContent = "Save";
  //     //         });
  //     //     });
  //     //     // });
  //     //   });
  //     // }
  //   });

  //   // --- B. Logic saat Accordion DITUTUP (Collapse) ---
  //   parentAccordionKec.addEventListener("hide.bs.collapse", function (event) {
  //     const targetElement = event.target;
  //     const loadContainer = targetElement.querySelector(".data-load-container");

  //     // 1. Hapus isi (Clear Content)
  //     loadContainer.innerHTML =
  //       '<p class="text-center">Data telah dihapus.</p>';

  //     // 2. Reset status loaded
  //     targetElement.setAttribute("data-loaded", "false");
  //   });
  // }

  // function setupFormListeners(container) {
  //   // 1. Cari form HANYA di dalam container yang diberikan
  //   console.log(container);
  //   const forms = container.querySelectorAll(".anomali-form-submit");

  //   console.log(`Menemukan ${forms.length} form untuk dipasangi listener.`);

  //   forms.forEach((form) => {
  //     form.addEventListener("submit", function (event) {
  //       event.preventDefault(); // Mencegah submit form tradisional (refresh halaman)

  //       const formData = new FormData(form);
  //       const anomaliId = formData.get("id");
  //       const anomaliKonfirmasi = formData.get("konfirmasi");

  //       // Perhatikan: Anda mungkin perlu ID unik lain jika ada beberapa tombol save
  //       // Jika ID tombol sama untuk semua form, gunakan form.querySelector
  //       const saveButton = form.querySelector("#submit-button");

  //       // Pastikan feedbackElement juga dicari secara dinamis,
  //       // karena ID-nya bergantung pada anomaliId
  //       const feedbackElement = document.getElementById(
  //         `feedback-${anomaliId}`
  //       );

  //       // Periksa apakah saveButton dan feedbackElement ditemukan sebelum melanjutkan
  //       if (!saveButton || !feedbackElement) {
  //         console.error("Elemen tombol atau feedback tidak ditemukan!");
  //         return; // Hentikan fungsi jika elemen vital tidak ditemukan
  //       }

  //       // Tampilkan status loading
  //       saveButton.disabled = true;
  //       saveButton.textContent = "Saving...";
  //       feedbackElement.innerHTML = ""; // Kosongkan feedback sebelumnya

  //       // 2. Kirim data menggunakan Fetch API
  //       fetch("/anomali/updateKonfirmasi", {
  //         method: "POST",
  //         body: formData,
  //       })
  //         .then((response) => response.json())
  //         .then((data) => {
  //           // 3. Handle Feedback Berhasil/Gagal
  //           if (data.status === "success") {
  //             feedbackElement.innerHTML =
  //               '<i class="text-success bi bi-check-circle-fill"></i> Saved!';
  //             form.classList.add("bg-success", "bg-opacity-10");
  //             setTimeout(
  //               () => form.classList.remove("bg-success", "bg-opacity-10"),
  //               2000
  //             );
  //           } else {
  //             feedbackElement.innerHTML = `<span class="text-danger">Error: ${data.message}</span>`;
  //           }
  //         })
  //         .catch((error) => {
  //           feedbackElement.innerHTML =
  //             '<span class="text-danger">Koneksi Error.</span>';
  //           console.error("Error:", error);
  //         })
  //         .finally(() => {
  //           // 4. Kembalikan tombol ke keadaan normal
  //           saveButton.disabled = false;
  //           saveButton.textContent = "Save";
  //         });
  //     });
  //   });
  // }

  // untuk halaman manajemen anomali
  if (deleteModal) {
    deleteModal.addEventListener("show.bs.modal", function (event) {
      // 1. Ambil elemen yang memicu modal (pastikan mengarah ke element yang punya data-id)
      let button = event.relatedTarget;
      
      // Jika user tidak sengaja klik gambar ikon trash di dalam button, naikkan ke element button-nya
      if (button.tagName !== 'BUTTON') {
        button = button.closest('button');
      }

      console.log("Tombol Pemicu:", button); // Debugging info

      // 2. Ekstrak data atributnya
      const anomaliId = button.getAttribute("data-id");
      const anomaliKode = button.getAttribute("data-kode");
      
      console.log("ID didapat:", anomaliId); // Debugging info
      console.log("Kode didapat:", anomaliKode); // Debugging info

      // 3. Masukkan ID ke hidden input di form Modal
      // Gunakan document.getElementById agar pencariannya global dan absolut mendeteksi ID tersebut
      const modalInput = document.getElementById("delete_id_input");
      if (modalInput) {
        modalInput.value = anomaliId;
        console.log("Value input modal sekarang:", modalInput.value); // Pastikan ini terisi angka id
      } else {
        console.error("Elemen #delete_id_input tidak ditemukan di HTML!");
      }

      // 4. Tampilkan teks Kode di badan modal
      const modalDisplay = document.getElementById("anomali-id-display");
      if (modalDisplay) {
        modalDisplay.textContent = anomaliKode || anomaliId;
      }
    });
  }
});
