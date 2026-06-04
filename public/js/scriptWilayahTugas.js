document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".btn-edit-wilayah");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Ambil data dari atribut tombol
      const kab = this.getAttribute("data-kab");
      const kec = this.getAttribute("data-kec");
      const desa = this.getAttribute("data-desa");
      const sls = this.getAttribute("data-sls");
      const em_ppl = this.getAttribute("data-ppl");
      const em_pml = this.getAttribute("data-pml");
      const em_kos = this.getAttribute("data-kos");
      const id = this.getAttribute("data-id");

      // Masukkan ke dalam modal rincian wilayah
      document.getElementById("display-kab").innerText = kab;
      document.getElementById("display-kec").innerText = kec;
      document.getElementById("display-desa").innerText = desa;
      document.getElementById("display-sls").innerText = sls;

      // Set value dropdown ke nilai saat ini
      document.getElementById("select-id").value = id;
      const selectPPL = document.getElementById("select-ppl");
      const selectPML = document.getElementById("select-pml");
      const selectKOS = document.getElementById("select-kos");

      if (selectPPL && selectPPL.tomselect) {
        if (em_ppl) {
          selectPPL.tomselect.setValue(em_ppl);
        } else {
          selectPPL.tomselect.clear();
        }
      }
      if (selectPML && selectPML.tomselect) {
        if (em_pml) {
          selectPML.tomselect.setValue(em_pml);
        } else {
          selectPML.tomselect.clear();
        }
      }
      if (selectKOS && selectKOS.tomselect) {
        if (em_kos) {
          selectKOS.tomselect.setValue(em_kos);
        } else {
          selectKOS.tomselect.clear();
        }
      }
    });
  });
});
