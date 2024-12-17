<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagKelas is set
if (isset($_POST['flagKelas']) && $_POST['flagKelas'] === 'add') {

    $nama = $_POST['nama'];
    $kode = $_POST['kode'];
    $idJurusan = $_POST['idJurusan'];

    $query = "INSERT INTO kelas (nama, kode, idJurusan) VALUES (?, ?, ?)";

    $result = query($query, [$nama, $kode, $idJurusan]);
    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Kelas berhasil di input!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Kelas gagal di input."
        ]);
    }
} else if (isset($_POST['flagKelas']) && $_POST['flagKelas'] === 'delete') {
    $idKelas = $_POST['idKelas'];

    $query = "DELETE FROM kelas WHERE idKelas = ?";
    $result = query($query, [$idKelas]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data kelas berhasil di hapus!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data kelas gagal di hapus!: "
        ]);
    }
} else if ($_POST['flagKelas'] && $_POST['flagKelas'] === 'update') {
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
    $idJurusan = $_POST['idJurusan'];
    $idKelas = $_POST['idKelas'];
    $query = "UPDATE kelas 
      SET nama = ?, 
          kode = ?, 
          idJurusan = ? 
      WHERE idKelas = ?";
    $result = query($query, [$nama, $kode, $idJurusan, $idKelas]);

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Update Kelas berhasil!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Update kelas gagal: "
        ]);
    }
}
