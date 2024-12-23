document.addEventListener("DOMContentLoaded", function (event) {
  daftarSiswa();
});
function daftarSiswa() {
  $.ajax({
    url: "daftarSiswa.php",
    type: "post",
    data: {
      flagSiswa: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarSiswa").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesSiswa() {
const prosesSiswa = document.getElementById("formSiswa");
const dataForm = new FormData(prosesSiswa);

  $("#siswaModal").modal("hide");

  $("#siswaModal").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesSiswa.php",
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
        daftarSiswa();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deleteSiswa(id) {
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
        url: "prosesSiswa.php",
        type: "post",
        data: {
          idSiswa: id,
          flagSiswa: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarSiswa();
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
    url: "daftarSiswa.php",
    data: {
      flagEmployee: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarSiswa").html(data);
    },
  });
}

// $(document).ready(function () {
//   $('#idJurusan').select2();
// });
function editSiswaModal(siswa) {
  document.getElementById("idSiswa").value = siswa.idSiswa;
  document.getElementById("nis").value = siswa.nis;
  document.getElementById("nisn").value = siswa.nisn;
  document.getElementById("nama").value = siswa.nama;
  document.getElementById("namaAyah").value = siswa.namaAyah;
  document.getElementById("namaIbu").value = siswa.namaIbu;
  document.getElementById("noTelp").value = siswa.noTelp;
  document.getElementById("tglLahir").value = siswa.tglLahir;

  const selectKelas = document.getElementById("idJurusan");
  selectKelas.value = siswa.idJurusan;
  const selectAngkatan = document.getElementById("idAngkatan");
  selectAngkatan.value = siswa.idAngkatan;
  document.getElementById("alamat").value = siswa.alamat;

  document.getElementById("flagSiswa").value = "update";
}

function cariDaftarSiswa() {
  const searchQuery = $("#searchQuery").val();
  const idJurusan = $("#idJurusanSearch").val();
  const idAngkatan = $("#idAngkatanSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || idJurusan || limit) {
    $.ajax({
      url: "daftarSiswa.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        idJurusan: idJurusan,
        idAngkatan: idAngkatan,
        limit: limit,
        flagSiswa: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarSiswa").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarSiswa.php",
      type: "post",
      data: {
        flagEmployee: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarSiswa").html(data);
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
