document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".btn-edit-wilayah");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Ambil data dari atribut tombol
      const kab = this.getAttribute("data-kab");
      const kec = this.getAttribute("data-kec");
      const desa = this.getAttribute("data-desa");
      const sls = this.getAttribute("data-sls");
      const ppl = this.getAttribute("data-ppl");
      const pml = this.getAttribute("data-pml");
      const id = this.getAttribute("data-id");

      // Masukkan ke dalam modal rincian wilayah
      document.getElementById("display-kab").innerText = kab;
      document.getElementById("display-kec").innerText = kec;
      document.getElementById("display-desa").innerText = desa;
      document.getElementById("display-sls").innerText = sls;

      // Set value dropdown ke nilai saat ini
      document.getElementById("select-ppl").value = ppl;
      document.getElementById("select-pml").value = pml;
      document.getElementById("select-id").value = id;
    });
  });
});
