document.addEventListener("DOMContentLoaded", function (event) {
  daftarJabatan();
});
function daftarJabatan() {
  $.ajax({
    url: "daftarJabatan.php",
    type: "post",
    data: {
      flagJabatan: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarJabatan").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesJabatan() {
  const formJabatan = document.getElementById("formJabatan");
  const dataForm = new FormData(formJabatan);

  $("#jabatanModal").modal("hide");

  $("#jabatanModal").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesJabatan.php",
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
        daftarJabatan();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deleteJabatan(id) {
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
        url: "prosesJabatan.php",
        type: "post",
        data: {
          idJabatan: id,
          flagJabatan: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarJabatan();
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
    url: "daftarJabatan.php",
    data: {
      flagJabatan: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarJabatan").html(data);
    },
  });
}

// $(document).ready(function () {
//   $('#idJabatan').select2();
// });
function editJabatanModal(jabatan) {
  document.getElementById("idJabatan").value = jabatan.idJabatan;
  document.getElementById("nama").value = jabatan.nama;
  document.getElementById("gaji").value = jabatan.gaji;
  document.getElementById("flagJabatan").value = "update";
}

function cariDaftarJabatan() {
  const searchQuery = $("#searchQuery").val();
  const idJabatan = $("#idJabatanSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || idJabatan || limit) {
    $.ajax({
      url: "daftarJabatan.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        idJabatan: idJabatan,
        limit: limit,
        flagJabatan: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarJabatan").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarJabatan.php",
      type: "post",
      data: {
        flagJabatan: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarJabatan").html(data);
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
