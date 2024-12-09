document.addEventListener("DOMContentLoaded", function (event) {
  daftarEmployee(); 
});
function daftarEmployee() {
  $.ajax({
    url: "daftarEmployee.php",
    type: "post",
    data: {
      flagEmployee: "daftar"
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarEmployee").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesEmployee() {
  const formEmployee = document.getElementById("formEmployee");
  const dataForm = new FormData(formEmployee);
  
  $("#employeeModal").modal("hide");

  $("#employeeModal").on('hidden.bs.modal', function () {
    $.ajax({
      url: "prosesEmployee.php",
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
        daftarEmployee();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}


function deleteEmployee(id) {
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
        url: "prosesEmployee.php",
        type: "post",
        data: {
          employeeId: id,
          flagEmployee: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarEmployee();
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
  const limit = $('#limit').val();
  $.ajax({
      type: "POST",
      url: "daftarEmployee.php",
      data: {
          flagEmployee: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          limit: limit 
      },
      success: function (data) {
          $('#daftarEmployee').html(data);
      }
  });
}

function editEmployeeModal(employee) {
  document.getElementById('employeeId').value = employee.employeeId;
  document.getElementById('name').value = employee.name;

  const roleSelect = document.getElementById('roleId');
  roleSelect.value = employee.roleId;
  document.getElementById('phoneNumber').value = employee.phoneNumber;
  document.getElementById('email').value = employee.email;
  document.getElementById('address').value = employee.address;

  document.getElementById('flagEmployee').value = 'update';
}





function cariDaftarEmployee() {
	const searchQuery = $("#searchQuery").val();
  const roleId = $("#roleIdSearch").val();
  const limit = $("#limit").val();
	if (searchQuery || roleId || limit) {
		$.ajax({
			url: "daftarEmployee.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				roleId: roleId,
				limit: limit,
				flagEmployee: "cari",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarEmployee").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarEmployee.php",
			type: "post",
			data: {
				flagEmployee: "daftar",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarEmployee").html(data);
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
