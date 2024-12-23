document.addEventListener("DOMContentLoaded", function (event) {
  daftarKelas();
});
function daftarKelas() {
  $.ajax({
    url: "daftarKelas.php",
    type: "post",
    data: {
      flagKelas: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarKelas").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesKelas() {
  const formKelas = document.getElementById("formKelas");
  const dataForm = new FormData(formKelas);

  $("#kelasModal").modal("hide");

  $("#kelasModal").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesKelas.php",
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
        daftarKelas();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deleteKelas(id) {
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
        url: "prosesKelas.php",
        type: "post",
        data: {
          idKelas: id,
          flagKelas: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarKelas();
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
    url: "daftarKelas.php",
    data: {
      flagKelas: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarKelas").html(data);
    },
  });
}

// $(document).ready(function () {
//   $('#idKelas').select2();
// });
function editKelasModal(kelas) {
  console.log(kelas.idJurusan);
  document.getElementById("idKelas").value = kelas.idKelas;
  document.getElementById("nama").value = kelas.nama;
  const selectJurusan = document.getElementById("idJurusan");
  selectJurusan.value = kelas.idJurusan;
  document.getElementById("flagKelas").value = "update";
}

function cariDaftarKelas() {
  const searchQuery = $("#searchQuery").val();
  const idJurusan = $("#idKelasSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || idJurusan || limit) {
    $.ajax({
      url: "daftarKelas.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        idJurusan: idJurusan,
        limit: limit,
        flagKelas: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarKelas").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarKelas.php",
      type: "post",
      data: {
        flagKelas: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarKelas").html(data);
      },
    });
  }
}

function loadDetailKelas(kelas) {
  const idKelas = kelas.idKelas;
  const tingkat = kelas.tingkat;
  const idJurusanDetail = kelas.idJurusan;
  console.log("ID Kelas:", idKelas);
  console.log("Tingkat:", tingkat);
  console.log("ID Jurusan:", idJurusanDetail);
  $.ajax({
    url: "daftarKelas.php",
    type: "POST",
    data: {
      idKelas: idKelas,
      tingkat: tingkat,
      idJurusanDetail: idJurusanDetail,
    },
    success: function (response) {
      console.log("Response:", response);
      // $("#detailKelasTableBody").html(response);
    },
    error: function (xhr, status, error) {
      console.error("Error:", status, error);
      console.error("Response Text:", xhr.responseText);
    },
    complete: function () {
      console.log("AJAX request completed.");
    },
  });

  $("#detailKelasModal").modal("show");
}


function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
