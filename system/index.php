<?php
session_start();
require_once ".././library/konfigurasi.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiRupiah.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiTanggal.php";
checkUserSession($db);
// var_dump(BASE_URL_PHP);
//CEK USER

// $currentMonth = date('m');
// $currentYear = date('Y');
// // DATA RESERVATION BULAN INI
// $reservasiData = query("
//     SELECT 
//         COUNT(*) AS totalReservations, 
//         SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
//     FROM reservations
//     INNER JOIN extra ON reservations.extraId = extra.extraId
//     WHERE MONTH(checkInDate) = ? AND YEAR(checkInDate) = ?
// ", [$currentMonth, $currentYear]);
// $totalReservations = $reservasiData[0]['totalReservations'];
// $totalRevenue = $reservasiData[0]['totalRevenue'];



// // PERBANDINGAN TOTAL RESERVASI
// $previousMonth = $currentMonth - 1;
// $previousYear = $currentYear;
// if ($previousMonth == 0) {
//     $previousMonth = 12;
//     $previousYear -= 1;
// }

// // REVENUE BULAN INI
// $currentMonthRevenueData = query("
//     SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
//     FROM reservations
//     INNER JOIN extra ON reservations.extraId = extra.extraId
//     WHERE MONTH(checkInDate) = ? AND YEAR(checkInDate) = ?
// ", [$currentMonth, $currentYear]);

// $currentMonthRevenue = $currentMonthRevenueData[0]['totalRevenue'] ?? 0;

// // REVENUE BULAN LALU
// $previousMonthRevenueData = query("
//     SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
//     FROM reservations
//     INNER JOIN extra ON reservations.extraId = extra.extraId
//     WHERE MONTH(checkInDate) = ? AND YEAR(checkInDate) = ?
// ", [$previousMonth, $previousYear]);

// $previousMonthRevenue = $previousMonthRevenueData[0]['totalRevenue'] ?? 0;

// // CEK PRESENTASE
// if ($previousMonthRevenue > 0) {
//     $changePercentage = (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100;

//     if ($changePercentage > 0) {
//         $status = "naik"; 
//     } elseif ($changePercentage < 0) {
//         $status = "turun"; 
//     } else {
//         $status = "sama"; 
//     }
// } else {
//     $changePercentage = $currentMonthRevenue > 0 ? 100 : 0;
//     $status = $currentMonthRevenue > 0 ? "naik" : "tidak ada pendapatan"; 
// }



// // TOTAL GUEST BULAN INI
// $totalGuest = query("SELECT SUM(adult + child) as totalGuest FROM reservations WHERE MONTH(checkInDate) = ?",[$currentMonth]);
// $guest = $totalGuest[0]['totalGuest'];



// $query = "SELECT MONTH(checkInDate) as month, SUM(totalPrice + IFNULL(extra.price, 0)) as totalRevenue
//           FROM reservations 
//           INNER JOIN extra ON reservations.extraId = extra.extraId
//           WHERE YEAR(checkInDate) = YEAR(CURRENT_DATE())
//           GROUP BY MONTH(checkInDate)
//           ORDER BY MONTH(checkInDate)";

// $result = query($query); 

// $months = [];
// $revenues = [];

// foreach ($result as $row) {
//     $months[] = namaBulan($row['month']);
//     $revenues[] = $row['totalRevenue'];
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SION</title>

    <!-- Custom fonts for this template-->
    <link href="<?= BASE_URL_HTML ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= BASE_URL_HTML ?>/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once "./sidebar.php" ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once "./navbar.php" ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <!-- <a href="<?= BASE_URL_HTML ?>/#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- OVERVIEW -->
                  

                    <!-- CHART -->
                    <!-- <div class="row">
                    <canvas id="revenueChart" width="300" height="100"></canvas>
                    </div> -->

                

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require_once "{$constant('BASE_URL_PHP')}/system/footer.php" ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="<?= BASE_URL_HTML ?>/#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= BASE_URL_HTML ?>/login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= BASE_URL_HTML ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= BASE_URL_HTML ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= BASE_URL_HTML ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= BASE_URL_HTML ?>/assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="<?= BASE_URL_HTML ?>/vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="<?= BASE_URL_HTML ?>/js/demo/chart-area-demo.js"></script>
    <script src="<?= BASE_URL_HTML ?>/js/demo/chart-pie-demo.js"></script> -->

    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    


</body>

</html>