document.addEventListener("DOMContentLoaded", function () {
  const parentAccordionKec = document.getElementById("accordionAnomaliKec");
  const isEdit = document.getElementById("isEdit");
  const filterLevel = document.getElementById("filter-level");
  const filterKode = document.getElementById("filter-kategori");
  const filterFlag = document.getElementById("filter-flag");

  const loadingContent =
    '<div class="d-flex justify-content-center py-2 text-muted small"><div class="spinner-border spinner-border-sm me-2"></div><span>Memuat data...</span></div>';

  // Template Variasi Status Feedback (Menggunakan elemen badge biar estetik)
  const statusSending =
    '<span class="text-warning small d-flex align-items-center gap-1"><span class="spinner-border spinner-border-sm" role="status"></span> Mengirim...</span>';
  const statusSaved =
    '<span class="text-success small fw-bold d-flex align-items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check-filled mb-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" fill="currentColor"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.414 0l-3.293 3.293l-1.293 -1.293l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" fill="currentColor" /></svg> Tersimpan!</span>';
  const statusFailed = (msg) =>
    `<span class="text-danger small fw-bold">❌ Gagal: ${msg}</span>`;
  const statusError =
    '<span class="text-danger small fw-bold">⚠️ Gangguan Koneksi / Error!</span>';

  if (parentAccordionKec) {
    // --- A. Logic Ambil Data Sub-Level (AJAX Bawaan) ---
    parentAccordionKec.addEventListener("show.bs.collapse", function (event) {
      event.stopPropagation();
      const targetElement = event.target;
      if (!targetElement.classList.contains("accordion-collapse")) return;

      const item = targetElement.closest(".accordion-item");
      if (!item) return;

      const id = item.getAttribute("data-id");
      const isCompleted = item.getAttribute("data-is-completed");
      const loadContainer = targetElement.querySelector(".data-load-container");

      if (
        !loadContainer ||
        targetElement.getAttribute("data-loaded") === "true"
      )
        return;

      loadContainer.innerHTML = loadingContent;

      const param = new URLSearchParams({
        "fil-level": filterLevel ? filterLevel.value : "",
        "id-anomali": id,
        "fil-kategori": filterKode ? filterKode.value : "",
        "fil-flag": filterFlag ? filterFlag.value : "",
        "is-edit": isEdit ? isEdit.value : "",
        "is-completed": isCompleted || "",
      });

      fetch(`${BASE_URL}/anomali/listDetil?${param}`)
        .then((response) => response.text())
        .then((htmlContent) => {
          loadContainer.innerHTML = htmlContent;
          targetElement.setAttribute("data-loaded", "true");
          // =============== INITIALIZE PAGINASI DAN SEARCH LOKAL PART ===============
          const newWrapper = loadContainer.querySelector(".part-accordion-wrapper");
          if (newWrapper) {
            initPartPagination(newWrapper);
          }
          // =========================================================================
        })
        .catch((error) => {
          console.error("Gagal memuat data:", error);
          loadContainer.innerHTML =
            '<div class="text-danger small p-2">Gagal memuat sub-level data.</div>';
        });
    });

    // --- B. Logic Bersih Konten Saat Ditutup ---
    parentAccordionKec.addEventListener("hide.bs.collapse", function (event) {
      event.stopPropagation();
      const targetElement = event.target;
      const loadContainer = targetElement.querySelector(".data-load-container");

      if (loadContainer) {
        loadContainer.innerHTML = loadingContent;
        targetElement.setAttribute("data-loaded", "false");
      }
    });
  }

  // --- C. EVENT DELEGATION GLOBAL UNTUK SIMPAN FORM & KONTROL COUNTER ---
  document.addEventListener("submit", function (event) {
    const form = event.target.closest(".anomali-form-submit");
    if (!form) return;

    event.preventDefault();

    const formData = new FormData(form);
    const anomaliId = formData.get("id");
    const saveButton = form.querySelector('button[type="submit"]');
    const feedbackElement = document.getElementById(`feedback-${anomaliId}`);

    if (!saveButton || !feedbackElement) return;

    // Simpan tampilan tombol asli
    const originalButtonHTML = saveButton.innerHTML;

    // 1. Set State Awal: Sedang Dikirim
    saveButton.disabled = true;
    saveButton.innerHTML = "<span>Menyimpan...</span>";
    feedbackElement.innerHTML = statusSending;

    // 2. Kirim ke Server via Fetch POST
    fetch(`${BASE_URL}/anomali/updateKonfirmasi`, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) throw new Error("Server bermasalah (HTTP Error)");
        return response.json();
      })
      .then((data) => {
        // 3. Pengecekan Response Sukses
        if (data.status === "success" || data.status === true) {
          feedbackElement.innerHTML = statusSaved;

          // Efek visual sukses pada kartu detail (opsional)
          const detailCard = form.closest(".card");
          if (detailCard) {
            detailCard.style.borderColor = "#2fb344"; // Beri border hijau tipis sementara
            detailCard.style.backgroundColor = "#f2faf4";
          }

          // ========================================================
          // LOGIC BARU: PENGURANGAN OTOMATIS COUNTER 4 LEVEL PARENT
          // ========================================================
          let currentElement = form;

          while (currentElement && currentElement !== document.body) {
            // Cari elemen bapak terdekat tempat form/sub-accordion ini bernaung
            const parentItem = currentElement.closest(".accordion-item");
            if (!parentItem) break;

            // TARGET AMAN: Mengincar spesifik ke class .badge-counter, bukan teks kode terdepan
            const badgeCounter = parentItem.querySelector(
              ".accordion-header .badge-counter",
            );
            if (badgeCounter) {
              // Ambil isi teks angka saja, hilangkan karakter non-angka seperti titik, huruf 'A' atau 'Kasus'
              let rawText = badgeCounter.textContent.trim();
              let currentCount =
                parseInt(rawText.replace(/[^0-9]/g, ""), 10) || 0;

              if (currentCount > 0) {
                let newCount = currentCount - 1;

                // Cek format akhiran teks bawaan aslinya (apakah pakai 'A' atau 'Kasus')
                if (rawText.includes("Kasus")) {
                  badgeCounter.textContent =
                    newCount.toLocaleString("id-ID") + " Kasus";
                } else if (rawText.includes("A")) {
                  badgeCounter.textContent =
                    newCount.toLocaleString("id-ID") + " A";
                } else {
                  badgeCounter.textContent =
                    newCount.toLocaleString("id-ID") + " Entri";
                }

                // Jika angka akumulasi di level ini habis (0), ubah tampilan jadi selesai
                if (newCount === 0) {
                  badgeCounter.className =
                    "badge bg-secondary-lt text-secondary border rounded-pill fw-normal px-2 py-0-5 badge-counter";
                  badgeCounter.textContent = "Selesai";
                }
              }
            }
            // Naik ke satu tingkat bapak di atasnya untuk memotong rantai level berikutnya
            currentElement = parentItem.parentElement;
          }
        } else {
          // 4. Handle Gagal Validasi atau Pesan dari Controller
          feedbackElement.innerHTML = statusFailed(
            data.message || "Validasi gagal",
          );
          saveButton.disabled = false;
          saveButton.innerHTML = originalButtonHTML;
        }
      })
      .catch((error) => {
        // 5. Handle Error Jaringan / Server Down
        feedbackElement.innerHTML = statusError;
        console.error("Kesalahan Sistem Ajax:", error);
        saveButton.disabled = false;
        saveButton.innerHTML = originalButtonHTML;
      })
      .finally(() => {
        // Tombol dibuka kembali
        saveButton.disabled = false;
      });
  });

  // =============== FUNGSI BARU UNTUK MANAJEMEN RUTA (500 DATA) ===============
  function initPartPagination(wrapper) {
    const searchInput = wrapper.querySelector(".search-part-input");
    const btnPrev = wrapper.querySelector(".btn-part-prev");
    const btnNext = wrapper.querySelector(".btn-part-next");
    // VALIDASI BARU: Jika elemen kontrol search tidak ada (Bukan level Ruta/Anom), jangan jalankan paginasi JS
    if (!searchInput && !btnPrev && !btnNext) {
      return; 
    }
    const txtRange = wrapper.querySelector(".txt-range");
    const txtTotal = wrapper.querySelector(".txt-total");
    const allItems = Array.from(wrapper.querySelectorAll(".main-part-accordion > .search-target-item"));
    
    const itemsPerPage = 100;
    let currentPage = 1;
    let filteredItems = [];

    function renderPart() {
      const keyword = searchInput ? searchInput.value.toLowerCase().trim() : "";

      // Filtering multi-match (Kode Anomali atau Nama KRT/ART/Usaha)
      filteredItems = allItems.filter(item => {
        const badgeText = item.querySelector(".match-badge") ? item.querySelector(".match-badge").textContent.toLowerCase() : "";
        const nameText = item.querySelector(".match-name") ? item.querySelector(".match-name").textContent.toLowerCase() : "";
        return badgeText.includes(keyword) || nameText.includes(keyword);
      });

      const totalData = filteredItems.length;
      const totalPages = Math.ceil(totalData / itemsPerPage) || 1;

      if (currentPage > totalPages) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      // Sembunyikan semua item di level part ini
      allItems.forEach(item => item.style.display = "none");

      // Tampilkan hanya yang masuk batasan halaman (max 100)
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = Math.min(startIndex + itemsPerPage, totalData);

      for (let i = startIndex; i < endIndex; i++) {
        if (filteredItems[i]) {
          filteredItems[i].style.display = "block";
        }
      }

      // Perbarui tampilan info teks teks paginasi
      if (txtRange && txtTotal) {
        txtRange.textContent = totalData === 0 ? "0" : `${startIndex + 1}-${endIndex}`;
        txtTotal.textContent = totalData;
      }

      // Atur status tombol kemudi
      if (btnPrev && btnNext) {
        btnPrev.disabled = currentPage === 1;
        btnNext.disabled = currentPage === totalPages || totalData === 0;
      }
    }

    // Listener Ketikan Search Lokal
    if (searchInput) {
      searchInput.addEventListener("input", function () {
        currentPage = 1;
        renderPart();
      });
    }

    // Listener Tombol navigasi internal
    if (btnPrev) {
      btnPrev.addEventListener("click", function (e) {
        e.stopPropagation();
        if (currentPage > 1) {
          currentPage--;
          renderPart();
          wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      });
    }

    if (btnNext) {
      btnNext.addEventListener("click", function (e) {
        e.stopPropagation();
        const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
        if (currentPage < totalPages) {
          currentPage++;
          renderPart();
          wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      });
    }

    // Jalankan render perdana saat komponen ter-load
    renderPart();
  }
});
