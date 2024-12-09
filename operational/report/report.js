document.addEventListener("DOMContentLoaded", function (event) {
  detailRevenue();
  daftarRevenue();
});

function exportExcel() {
  const action = "excel/revenueReport.php";

  const form = $("#revenueForm");

  form.attr("action", action);
  form.attr("method", "POST");
  form.attr("enctype", "multipart/form-data");
  form.submit();
}

function detailRevenue() {
  $.ajax({
    url: "detailRevenue.php",
    type: "post",
    data: {
      flag: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#detailReport").html(data);
      $(".overlay").hide();
      document.addEventListener("DOMContentLoaded", function (event) {
        daftarRevenue();
      });
    },
  });
}
function daftarRevenue() {
  $.ajax({
    url: "detail/daftarRevenue.php",
    type: "post",
    data: {
      flag: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarRevenue").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesReservation() {
  const formReservation = document.getElementById("formReservation");
  const dataForm = new FormData(formReservation);
  dataForm.append("flag", "add");

  $.ajax({
    url: "prosesReport.php",
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
      daftarReservation();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
}

function deleteReservation(id) {
  Swal.fire({
    title: "Are You Sure?",
    text: "Setelah dibatalkan, proses tidak dapat diulangi!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes!",
    cancelButtonText: "Cancel!",
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        url: "prosesReport.php",
        type: "post",
        data: {
          employeeId: id,
          flag: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarReservation();
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
    url: "daftarReport.php",
    data: {
      flag: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarReservation").html(data);
    },
  });
}

function editModalReservation(employee) {
  document.getElementById("employeeId").value = employee.employeeId;
  document.getElementById("name").value = employee.name;

  const roleSelect = document.getElementById("roleId");
  roleSelect.value = employee.roleId;
  document.getElementById("phoneNumber").value = employee.phoneNumber;
  document.getElementById("email").value = employee.email;
  document.getElementById("address").value = employee.address;

  document.getElementById("flag").value = "update";
}

function cariDaftarReservation() {
  const searchQuery = $("#searchQuery").val();
  const roleId = $("#roleIdSearch").val();
  const limit = $("#limit").val();
  if (searchQuery || roleId || limit) {
    $.ajax({
      url: "daftarReport.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        roleId: roleId,
        limit: limit,
        flag: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarReservation").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarReport.php",
      type: "post",
      data: {
        flag: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarReservation").html(data);
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
