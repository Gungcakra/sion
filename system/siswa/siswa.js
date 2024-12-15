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

function editSiswaModal(siswa) {
  document.getElementById('idSiswa').value = siswa.idSiswa;
  document.getElementById('nama').value = siswa.nama;

  const roleSelect = document.getElementById('roleId');
  roleSelect.value = siswa.roleId;
  document.getElementById('phoneNumber').value = siswa.phoneNumber;
  document.getElementById('email').value = siswa.email;
  document.getElementById('address').value = siswa.address;

  document.getElementById('flagSiswa').value = 'update';
}





function cariDaftarSiswa() {
	const searchQuery = $("#searchQuery").val();
  const idKelas = $("#idKelasSearch").val();
  const idAngkatan = $("#idAngkatanSearch").val();
  const limit = $("#limit").val();
	if (searchQuery || idKelas || limit) {
		$.ajax({
			url: "daftarSiswa.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				idKelas: idKelas,
				idAngkatan: idAngkatan,
				limit: limit,
				flagSiswa: "cari",
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
