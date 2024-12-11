<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagEmployee is set
if (isset($_POST['flagEmployee']) && $_POST['flagEmployee'] === 'add') {
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
} else if (isset($_POST['flagEmployee']) && $_POST['flagEmployee'] === 'delete') {
    $employeeId = $_POST['employeeId'];

    $query = "DELETE FROM employees WHERE employeeId = ?";
    $result = query($query, [$employeeId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Employee deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Employee: " 
        ]);
    }
} else if ($_POST['flagEmployee'] && $_POST['flagEmployee'] === 'update') {
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
