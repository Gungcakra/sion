<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagJurusan is set
if (isset($_POST['flagJurusan']) && $_POST['flagJurusan'] === 'add') {

    $namaJurusan = $_POST['namaJurusan'];
    $query = "INSERT INTO jurusan (namaJurusan) VALUES (?)";
    $result = query($query, [$namaJurusan]);
    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Jurusan berhasil di input!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Jurusan gagal di input."
        ]);
    }
} else if (isset($_POST['flagJurusan']) && $_POST['flagJurusan'] === 'delete') {
    $idJurusan = $_POST['idJurusan'];

    $query = "DELETE FROM jurusan WHERE idJurusan = ?";
    $result = query($query, [$idJurusan]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Jurusan berhasil di hapus!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Jurusan gagal di hapus!: "
        ]);
    }
} else if ($_POST['flagJurusan'] && $_POST['flagJurusan'] === 'update') {
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
 
    $idJurusan = $_POST['idJurusan'];
    $namaJurusan = $_POST['namaJurusan'];
    $query = "UPDATE jurusan 
      SET namaJurusan = ?
      WHERE idJurusan = ?";
    $result = query($query, [$namaJurusan, $idJurusan]);

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Update Jurusan berhasil!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Update Jurusan gagal: "
        ]);
    }
}
