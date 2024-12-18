<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagSiswa is set
if (isset($_POST['flagSiswa']) && $_POST['flagSiswa'] === 'add') {

    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $tglLahir = $_POST['tglLahir'];
    $noTelp = $_POST['noTelp'];
    $namaAyah = $_POST['namaAyah'];
    $namaIbu = $_POST['namaIbu'];
    $idAngkatan = $_POST['idAngkatan'];
    $idKelas = $_POST['idKelas'];

    $query = "INSERT INTO siswa (nis, nisn, nama, alamat, tglLahir, noTelp, namaAyah, namaIbu, status, idAngkatan, idKelas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $result = query($query, [$nis, $nisn, $nama, $alamat, $tglLahir, $noTelp, $namaAyah, $namaIbu, 'Aktif', $idAngkatan, $idKelas]);
    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Siswa berhasil di input!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Siswa gagal di input."
        ]);
    }
} else if (isset($_POST['flagSiswa']) && $_POST['flagSiswa'] === 'delete') {
    $idSiswa = $_POST['idSiswa'];

    $query = "DELETE FROM siswa WHERE idSiswa = ?";
    $result = query($query, [$idSiswa]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Data Siswa berhasil di hapus!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Data Siswa gagal di hapus!: "
        ]);
    }
} else if ($_POST['flagSiswa'] && $_POST['flagSiswa'] === 'update') {
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
    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $tglLahir = $_POST['tglLahir'];
    $noTelp = $_POST['noTelp'];
    $namaAyah = $_POST['namaAyah'];
    $namaIbu = $_POST['namaIbu'];
    $idAngkatan = $_POST['idAngkatan'];
    $idKelas = $_POST['idKelas'];
    $idSiswa = $_POST['idSiswa'];
    $query = "UPDATE siswa 
          SET nis = ?, 
              nisn = ?, 
              nama = ?, 
              alamat = ?, 
              tglLahir = ?, 
              noTelp = ?, 
              namaAyah = ?, 
              namaIbu = ?, 
              idAngkatan = ?, 
              idKelas = ? 
          WHERE idSiswa = ?";
    $result = query($query, [$nis, $nisn, $nama, $alamat, $tglLahir, $noTelp, $namaAyah, $namaIbu, $idAngkatan, $idKelas, $idSiswa]);

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Employee updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update employee: "
        ]);
    }
}
