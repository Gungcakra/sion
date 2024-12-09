<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flag is set
if (isset($_POST['flag']) && $_POST['flag'] === 'add') {
    $userId = $_POST['userId'];
    $guestId = $_POST['guestId'];
    $bookedRoomId = $_POST['roomId'];
    $extraId = $_POST['extraId'];
    $adult = $_POST['adult'];
    $child = $_POST['child'];
    $rentang = $_POST['rentang'];
    $totalPrice = $_POST['totalPrice'];
    list($checkInDate, $checkOutDate) = explode(" - ", $rentang);

    $checkInDate = date("Y-m-d", strtotime($checkInDate));
    $checkOutDate = date("Y-m-d", strtotime($checkOutDate));
    $query = "INSERT INTO reservations (guestId, roomId, extraId, adult, child, checkInDate, checkOutDate, userInputId, totalPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $result = query($query, [$guestId, $bookedRoomId, $extraId, $adult, $child, $checkInDate, $checkOutDate, $userId, $totalPrice]);

    if ($result > 0) {
        $queryRoom = query("UPDATE rooms SET status = ? WHERE roomId = ?", ["Booked", $bookedRoomId]);
        if($queryRoom > 0){
            echo json_encode([
                "status" => true,
                "pesan" => "Reservation added successfully!"
            ]);
        }
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Reservation."
        ]);
    }
}
