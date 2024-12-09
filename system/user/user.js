// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarUser.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarUser").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarUser:", error));
//   if (document.readyState === "complete") {
//     daftarUser();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarUser(); 
});


function daftarUser() {
  $.ajax({
    url: "daftarUser.php",
    type: "post",
    data: {
      flagUser: "daftar"
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarUser").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesUser() {
  const formUser = document.getElementById("formUser");
  const dataForm = new FormData(formUser);

  $("#userModal").modal("hide");

  $("#userModal").on('hidden.bs.modal', function () {
    $.ajax({
      url: "prosesUser.php",
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
        daftarUser();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}


function deleteUser(id) {
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
        url: "prosesUser.php",
        type: "post",
        data: {
          userId: id,
          flagUser: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarUser();
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
      url: "daftarUser.php",
      data: {
          flagUser: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          limit: limit 
      },
      success: function (data) {
          $('#daftarUser').html(data);
      }
  });
}

function editUserModal(user) {
  document.getElementById('userId').value = user.userId;
  document.getElementById('username').value = user.username;

  // const roleSelect = document.getElementById('roleId');

  // Array.from(roleSelect.options).forEach(option => {
  //   option.removeAttribute('selected');
  // });

  // // Set the 'selected' attribute on the matching option
  // Array.from(roleSelect.options).forEach(option => {
  //   if (option.value == User.roleId) {
  //     option.setAttribute('selected', 'selected');
  //   }

  //   console.log(option)
  // });
  const roleSelect = document.getElementById('employeeId');
  roleSelect.value = user.employeeId;

  document.getElementById('flagUser').value = 'update';
}





function cariDaftarUser() {
	const searchQuery = $("#searchQuery").val();
  const roleId = $("#roleIdSearch").val();
  const limit = $("#limit").val();
	if (searchQuery || roleId || limit) {
		$.ajax({
			url: "daftarUser.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				roleId: roleId,
				limit: limit,
				flagUser: "cari",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarUser").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarUser.php",
			type: "post",
			data: {
				flagUser: "daftar",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarUser").html(data);
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
