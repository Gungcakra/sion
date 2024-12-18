document.addEventListener("DOMContentLoaded", function (event) {
  daftarPegawai();
});
function daftarPegawai() {
  $.ajax({
    url: "daftarPegawai.php",
    type: "post",
    data: {
      flagPegawai: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarPegawai").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesPegawai() {
const prosesPegawai = document.getElementById("formPegawai");
const dataForm = new FormData(prosesPegawai);

  $("#pegawaiModal").modal("hide");

  $("#pegawaiModal").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesPegawai.php",
      type: "post",
      enctype: "multipart/form-data",
      processData: false,
      contentType: false,
      data: dataForm,
      dataType: "json",
      success: function (data) {
        console.log(data);
        const { status, pesan } = data;
        notifikasi(status, pesan);
        daftarPegawai();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deletePegawai(id) {
  Swal.fire({
    title: "Apakah Anda Yakin?",
    text: "Setelah dibatalkan, proses tidak dapat diulangi!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes!",
    cancelButtonText: "Cancel!",
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        url: "prosesPegawai.php",
        type: "post",
        data: {
          idPegawai: id,
          flagPegawai: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarPegawai();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error:", textStatus, errorThrown);
          Swal.fire("Error", "Something went wrong!", "error");
        },
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Canceled", "Proses Canceled!", "error");
    }
  });
}

function loadPage(pageNumber) {
  const limit = $("#limit").val();
  $.ajax({
    type: "POST",
    url: "daftarPegawai.php",
    data: {
      flagEmployee: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarPegawai").html(data);
    },
  });
}

// $(document).ready(function () {
//   $('#idKelas').select2();
// });
function editPegawaiModal(pegawai) {
  document.getElementById("idPegawai").value = pegawai.idPegawai;
  document.getElementById("nis").value = pegawai.nis;
  document.getElementById("nisn").value = pegawai.nisn;
  document.getElementById("nama").value = pegawai.nama;
  document.getElementById("namaAyah").value = pegawai.namaAyah;
  document.getElementById("namaIbu").value = pegawai.namaIbu;
  document.getElementById("noTelp").value = pegawai.noTelp;
  document.getElementById("tglLahir").value = pegawai.tglLahir;

  const selectKelas = document.getElementById("idKelas");
  selectKelas.value = pegawai.idKelas;
  const selectAngkatan = document.getElementById("idAngkatan");
  selectAngkatan.value = pegawai.idAngkatan;
  document.getElementById("alamat").value = pegawai.alamat;

  document.getElementById("flagPegawai").value = "update";
}

function cariDaftarPegawai() {
  const searchQuery = $("#searchQuery").val();
  const idJabatan = $("#idJabatanSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || idJabatan || limit) {
    $.ajax({
      url: "daftarPegawai.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        idJabatan: idJabatan,
        limit: limit,
        flagPegawai: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarPegawai").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarPegawai.php",
      type: "post",
      data: {
        flagEmployee: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarPegawai").html(data);
      },
    });
  }
}
function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
