<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagSiswa is set
if (isset($_POST['flagSiswa']) && $_POST['flagSiswa'] === 'add') {
    $name = $_POST['name'];
    $roleId = $_POST['roleId'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $query = "INSERT INTO employees (name, roleId, phoneNumber, email, address) VALUES (?, ?, ?, ?, ?)";

    $result = query($query, [$name, $roleId, $phoneNumber, $email, $address]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Employee added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Employee."
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
    $employeeId = $_POST['employeeId'];
    $name = $_POST['name'];
    $roleId = $_POST['roleId'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    $checkQuery = "SELECT COUNT(*) as count FROM employees WHERE email = ? AND employeeId != ?";
    $checkResult = query($checkQuery, [$email, $employeeId]);
    
    if ($checkResult[0]['count'] > 0) {
        echo json_encode([
            "status" => false,
            "pesan" => "The email is already used by another employee."
        ]);
        exit;
    }
    $query = "UPDATE employees 
              SET name = ?, 
                  roleId = ?, 
                  phoneNumber = ?, 
                  email = ?, 
                  address = ? 
              WHERE employeeId = ?";
    $result = query($query, [$name, $roleId, $phoneNumber, $email, $address, $employeeId]);
    
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
