document.addEventListener("DOMContentLoaded", function (event) {
  daftarMapel();
});
function daftarMapel() {
  $.ajax({
    url: "daftarMapel.php",
    type: "post",
    data: {
      flagMapel: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarMapel").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesMapel() {
  const formMapel = document.getElementById("formMapel");
  const dataForm = new FormData(formMapel);

  $("#mapelModal").modal("hide");

  $("#mapelModal").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesMapel.php",
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
        daftarMapel();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deleteMapel(id) {
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
        url: "prosesMapel.php",
        type: "post",
        data: {
          idMapel: id,
          flagMapel: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarMapel();
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
    url: "daftarMapel.php",
    data: {
      flagMapel: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarMapel").html(data);
    },
  });
}

// $(document).ready(function () {
//   $('#idMapel').select2();
// });
function editMapelModal(mapel) {
  document.getElementById("idMapel").value = mapel.idMapel;
  document.getElementById("nama").value = mapel.nama;
  document.getElementById("kode").value = mapel.kode;
  document.getElementById("flagMapel").value = "update";
}

function cariDaftarMapel() {
  const searchQuery = $("#searchQuery").val();
  const idMapel = $("#idMapelSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || idMapel || limit) {
    $.ajax({
      url: "daftarMapel.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        idMapel: idMapel,
        limit: limit,
        flagMapel: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarMapel").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarMapel.php",
      type: "post",
      data: {
        flagMapel: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarMapel").html(data);
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
