document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggle-sidebar");
  const body = document.body;
  const navLinks = document.querySelectorAll(
    ".navbar-vertical .nav-link:not(.dropdown-toggle)",
  );
  const isMobile = () => window.innerWidth < 992;

  // Ambil status terakhir dari localStorage (Hanya berlaku untuk desktop)
  if (!isMobile()) {
    const savedStatus = localStorage.getItem("sidebarStatus");
    if (savedStatus === "hidden") {
      body.classList.add("sidebar-collapsed");
    }
  }

  if (toggleBtn) {
    toggleBtn.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("tombol dijalankan");
      // Menambah/menghapus class sidebar-collapsed pada tag body
      body.classList.toggle("sidebar-collapsed");

      // Simpan status HANYA jika di desktop
      if (!isMobile()) {
        const isCollapsed = body.classList.contains("sidebar-collapsed");
        localStorage.setItem(
          "sidebarStatus",
          isCollapsed ? "hidden" : "visible",
        );
      }
    });

    // 2. Fungsi Auto-Close untuk HP
    // Jika link di dalam sidebar diklik, sembunyikan sidebar (hapus class)
    navLinks.forEach((link) => {
      link.addEventListener("click", function () {
        if (isMobile()) {
          body.classList.remove("sidebar-collapsed");
        }
      });
    });
  }

  // Cek status terakhir saat halaman dimuat
  if (localStorage.getItem("sidebarStatus") === "hidden") {
    body.classList.add("sidebar-collapsed");
  }

  // Mencari semua elemen dengan class .form-select
  // document.querySelectorAll(".form-select:not(.no-tom)").forEach((el) => {
  //   new TomSelect(el, {
  //     copyClassesToDropdown: false,
  //     dropdownParent: "body",
  //     controlInput: null, // Sembunyikan search box jika tidak ingin fitur cari
  //     render: {
  //       option: function (data, escape) {
  //         return '<div class="py-1 px-2">' + escape(data.text) + "</div>";
  //       },
  //     },
  //     // Tambahkan baris ini agar tingginya terkontrol
  //     maxOptions: null,
  //   });
  // });

  const selects = document.querySelectorAll(".form-select:not(.no-tom)");

  selects.forEach((el) => {
    new TomSelect(el, {
      copyClassesToDropdown: false, // Ubah ke true agar styling Bootstrap terbawa ke dropdown
      dropdownParent: "body",
      // controlInput: el.getAttribute("data-search") ? null : undefined, // Dinamis: tambah data-search="false" di HTML jika tak mau search
      placeholder: el.getAttribute("placeholder") || "Pilih data...",
      allowEmptyOption: true,
      maxOptions: null,
      render: {
        no_results: function (data, escape) {
          return '<div class="no-results">Data tidak ditemukan...</div>';
        },
      },
      // Perbaikan agar ukuran dropdown sesuai dengan Tabler
      onDropdownOpen: function () {
        const dropdown = this.dropdown;
        dropdown.style.zIndex = "2000"; // Pastikan di atas elemen Tabler lain
      },
    });
  });
});
