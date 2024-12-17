<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagRuangan is set
if (isset($_POST['flagRuangan']) && $_POST['flagRuangan'] === 'add') {

    $nama = $_POST['nama'];
    $kode = $_POST['kode'];

    $query = "INSERT INTO ruangan (nama, kode) VALUES (?, ?)";

    $result = query($query, [$nama, $kode]);
    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Ruangan berhasil di input!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Ruangan gagal di input."
        ]);
    }
} else if (isset($_POST['flagRuangan']) && $_POST['flagRuangan'] === 'delete') {
    $idRuangan = $_POST['idRuangan'];

    $query = "DELETE FROM ruangan WHERE idRuangan = ?";
    $result = query($query, [$idRuangan]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data ruangan berhasil di hapus!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data ruangan gagal di hapus!: "
        ]);
    }
} else if ($_POST['flagRuangan'] && $_POST['flagRuangan'] === 'update') {
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
    $nama = $_POST['nama'];
    $kode = $_POST['kode'];
    $idRuangan = $_POST['idRuangan'];
    $query = "UPDATE ruangan 
      SET nama = ?, 
          kode = ?
      WHERE idRuangan = ?";
    $result = query($query, [$nama, $kode, $idRuangan]);

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Update Ruangan berhasil!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Update ruangan gagal: "
        ]);
    }
}
