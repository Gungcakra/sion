document.addEventListener("DOMContentLoaded", function (event) {
  daftarRuangan();
});
function daftarRuangan() {
  $.ajax({
    url: "daftarRuangan.php",
    type: "post",
    data: {
      flagRuangan: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarRuangan").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesRuangan() {
  const formRuangan = document.getElementById("formRuangan");
  const dataForm = new FormData(formRuangan);

  $("#ruanganModal").modal("hide");

  $("#ruanganModal").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesRuangan.php",
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
        daftarRuangan();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deleteRuangan(id) {
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
        url: "prosesRuangan.php",
        type: "post",
        data: {
          idRuangan: id,
          flagRuangan: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarRuangan();
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
    url: "daftarRuangan.php",
    data: {
      flagRuangan: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarRuangan").html(data);
    },
  });
}

// $(document).ready(function () {
//   $('#idRuangan').select2();
// });
function editRuanganModal(ruangan) {
  document.getElementById("idRuangan").value = ruangan.idRuangan;
  document.getElementById("nama").value = ruangan.nama;
  document.getElementById("kode").value = ruangan.kode;
  document.getElementById("flagRuangan").value = "update";
}

function cariDaftarRuangan() {
  const searchQuery = $("#searchQuery").val();
  const idJurusan = $("#idRuanganSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || idJurusan || limit) {
    $.ajax({
      url: "daftarRuangan.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        idJurusan: idJurusan,
        limit: limit,
        flagRuangan: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarRuangan").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarRuangan.php",
      type: "post",
      data: {
        flagRuangan: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarRuangan").html(data);
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
