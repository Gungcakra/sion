<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagMapel is set
if (isset($_POST['flagMapel']) && $_POST['flagMapel'] === 'add') {

    $nama = $_POST['nama'];
    $kode = $_POST['kode'];
    $query = "INSERT INTO mapel (nama,kode) VALUES (?,?)";
    $result = query($query, [$nama,$kode]);
    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Mapel berhasil di input!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Mapel gagal di input."
        ]);
    }
} else if (isset($_POST['flagMapel']) && $_POST['flagMapel'] === 'delete') {
    $idMapel = $_POST['idMapel'];

    $query = "DELETE FROM mapel WHERE idMapel = ?";
    $result = query($query, [$idMapel]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Mapel berhasil di hapus!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Mapel gagal di hapus!: "
        ]);
    }
} else if ($_POST['flagMapel'] && $_POST['flagMapel'] === 'update') {
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
 
    $idMapel = $_POST['idMapel'];
    $nama = $_POST['nama'];
    $kode = $_POST['kode'];
    $query = "UPDATE mapel 
      SET nama = ?,
          kode = ?
      WHERE idMapel = ?";
    $result = query($query, [$nama, $kode, $idMapel]);

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Update Mapel berhasil!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Update Mapel gagal: "
        ]);
    }
}
