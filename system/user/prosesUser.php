<?php
session_start();
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagUser is set
if (isset($_POST['flagUser']) && $_POST['flagUser'] === 'add') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $employeeId = $_POST['employeeId'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO user (username, password, employeeId) VALUES (?, ?, ?)";

    $result = query($query, [$username, $hashedPassword, $employeeId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "User added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add User."
        ]);
    }
} else if (isset($_POST['flagUser']) && $_POST['flagUser'] === 'delete') {
    $userId = $_POST['userId'];

    $query = "DELETE FROM user WHERE userId = ?";
    $result = query($query, [$userId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "User deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete User: " . mysqli_error($db)
        ]);
    }
} else if ($_POST['flagUser'] && $_POST['flagUser'] === 'update') {
    $userId = $_POST['userId'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $employeeId = $_POST['employeeId'];

    if ($password) {
        $query = "UPDATE user 
              SET username = ?, 
                  password = ?, 
                  employeeId = ?
              WHERE userId = ?";
        $result = query($query, [$username, $hashedPassword, $employeeId, $userId]);
    } else {
        $query = "UPDATE user 
              SET username = ?, 
                  employeeId = ?
              WHERE userId = ?";
        $result = query($query, [$username, $employeeId, $userId]);
    }


    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "User updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update User: " . mysqli_error($db)
        ]);
    }
}
