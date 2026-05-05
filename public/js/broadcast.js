document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".btn-edit-broadcast");
  const hapusButtons = document.querySelectorAll(".btn-hapus-broadcast");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Ambil data dari atribut tombol
      const id = this.getAttribute("data-id");
      const judul = this.getAttribute("data-judul");
      const isi = this.getAttribute("data-isi");
      const kategori = this.getAttribute("data-kategori");

      // Ubah Menjadi Edit Broadcast
      document.getElementById("judul-broadcast").innerText = "Edit Broadcast";

      // Set value dropdown ke nilai saat ini
      document.getElementById("br-id").value = id;
      document.getElementById("br-judul").value = judul;
      document.getElementById("br-isi").value = isi;
      document.getElementById("br-kategori").value = kategori;
    });
  });

  hapusButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Ambil data dari atribut tombol
      const id = this.getAttribute("data-id");
      const judul = this.getAttribute("data-judul");

      // Ubah Menjadi Edit Broadcast
      document.getElementById("display-judul").innerText = judul;

      // Set value dropdown ke nilai saat ini
      document.getElementById("br-id").value = id;
    });
  });
});
