document.addEventListener("DOMContentLoaded", function (event) {
  daftarJurusan();
});
function daftarJurusan() {
  $.ajax({
    url: "daftarJurusan.php",
    type: "post",
    data: {
      flagJurusan: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarJurusan").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesJurusan() {
  const formJurusan = document.getElementById("formJurusan");
  const dataForm = new FormData(formJurusan);

  $("#jurusanModal").modal("hide");

  $("#jurusanModal").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesJurusan.php",
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
        daftarJurusan();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deleteJurusan(id) {
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
        url: "prosesJurusan.php",
        type: "post",
        data: {
          idJurusan: id,
          flagJurusan: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarJurusan();
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
    url: "daftarJurusan.php",
    data: {
      flagJurusan: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarJurusan").html(data);
    },
  });
}

// $(document).ready(function () {
//   $('#idJurusan').select2();
// });
function editJurusanModal(jurusan) {
  document.getElementById("idJurusan").value = jurusan.idJurusan;
  document.getElementById("namaJurusan").value = jurusan.namaJurusan;
  document.getElementById("flagJurusan").value = "update";
}

function cariDaftarJurusan() {
  const searchQuery = $("#searchQuery").val();
  const idJurusan = $("#idJurusanSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || idJurusan || limit) {
    $.ajax({
      url: "daftarJurusan.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        idJurusan: idJurusan,
        limit: limit,
        flagJurusan: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarJurusan").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarJurusan.php",
      type: "post",
      data: {
        flagJurusan: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarJurusan").html(data);
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
