<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagKelas is set
if (isset($_POST['flagKelas']) && $_POST['flagKelas'] === 'addDetailGuru') {

    $idKelas = $_POST['idKelas'];
    $idPegawai = $_POST['idPegawaiSelect'];

    $query = "INSERT INTO detail_kelas (idKelas, idPegawai) VALUES (?, ?)";

    $result = query($query, [$idKelas, $idPegawai]);
    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Berhasil menginput Wali Kelas!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Gagal Menginput Wali Kelas!."
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
    $tingkat = $_POST['tingkat'];
    $idJurusan = $_POST['idJurusan'];
    $idKelas = $_POST['idKelas'];
    $query = "UPDATE kelas 
      SET nama = ?, 
          tingkat = ?, 
          idJurusan = ? 
      WHERE idKelas = ?";
    $result = query($query, [$nama, $tingkat, $idJurusan, $idKelas]);

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
