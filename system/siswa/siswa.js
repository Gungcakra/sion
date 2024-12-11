document.addEventListener("DOMContentLoaded", function (event) {
  daftarSiswa(); 
});
function daftarSiswa() {
  $.ajax({
    url: "daftarSiswa.php",
    type: "post",
    data: {
      flagEmployee: "daftar"
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
  const prosesSiswa = document.getElementById("prosesSiswa");
  const dataForm = new FormData(prosesSiswa);
  
  $("#siswaModal").modal("hide");

  $("#siswaModal").on('hidden.bs.modal', function () {
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
    title: "Are You Sure?",
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
          employeeId: id,
          flagEmployee: "delete",
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
  const limit = $('#limit').val();
  $.ajax({
      type: "POST",
      url: "daftarSiswa.php",
      data: {
          flagEmployee: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          limit: limit 
      },
      success: function (data) {
          $('#daftarSiswa').html(data);
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





function caridaftarSiswa() {
	const searchQuery = $("#searchQuery").val();
  const roleId = $("#roleIdSearch").val();
  const limit = $("#limit").val();
	if (searchQuery || roleId || limit) {
		$.ajax({
			url: "daftarSiswa.php",
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
				$("#daftarSiswa").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarSiswa.php",
			type: "post",
			data: {
				flagEmployee: "daftar",
			},
			beforeSend: function () {
			
			},
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
