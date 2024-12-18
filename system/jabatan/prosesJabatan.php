<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagJabatan is set
if (isset($_POST['flagJabatan']) && $_POST['flagJabatan'] === 'add') {

    $nama = $_POST['nama'];
    $gaji = $_POST['gaji'];
    $query = "INSERT INTO jabatan (nama,gaji) VALUES (?,?)";
    $result = query($query, [$nama,$gaji]);
    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Jabatan berhasil di input!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Jabatan gagal di input."
        ]);
    }
} else if (isset($_POST['flagJabatan']) && $_POST['flagJabatan'] === 'delete') {
    $idJabatan = $_POST['idJabatan'];

    $query = "DELETE FROM jabatan WHERE idJabatan = ?";
    $result = query($query, [$idJabatan]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Jabatan berhasil di hapus!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Jabatan gagal di hapus!: "
        ]);
    }
} else if ($_POST['flagJabatan'] && $_POST['flagJabatan'] === 'update') {
    // $employeeId = $_POST['employeeId'];
    // $name = $_POST['name'];
    // $roleId = $_POST['roleId'];
    // $phoneNumber = $_POST['phoneNumber'];
    // $email = $_POST['email'];
    // $address = $_POST['address'];

    // $checkQuery = "SELECT COUNT(*) as count FROM employees WHERE email = ? AND employeeId != ?";
    // $checkResult = query($checkQuery, [$email, $employeeId]);

    // if ($checkResult[0]['count'] > 0) {
    //     echo json_encode([
    //         "status" => false,
    //         "pesan" => "The email is already used by another employee."
    //     ]);
    //     exit;
    // }
 
    $idJabatan = $_POST['idJabatan'];
    $nama = $_POST['nama'];
    $gaji = $_POST['gaji'];
    $query = "UPDATE jabatan 
      SET nama = ?,
          gaji = ?
      WHERE idJabatan = ?";
    $result = query($query, [$nama, $gaji, $idJabatan]);

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Update Jabatan berhasil!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Update Jabatan gagal: "
        ]);
    }
}
