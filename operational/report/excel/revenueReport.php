<?php
session_start();

require_once "../../../library/konfigurasi.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiRupiah.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiTanggal.php";

//CEK USER
checkUserSession($db);

$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$rentang = isset($_POST['rentang']) ? $_POST['rentang'] : '';
$roleId = isset($_POST['roleId']) ? $_POST['roleId'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$conditions = '';
$params = [];

$date = date(format: "d-m-Y");
$currentDay = date("d", strtotime($date));
$currentMonth = date("m", strtotime($date));
$currentYear = date("Y", strtotime($date));
$previousMonth = $currentMonth - 1;
$previousDay = $currentDay - 1;
$previousYear = $currentYear - 1;
if ($previousMonth == 0) {
  $previousMonth = 12;
  $previousYear -= 1;
  $previousDay -= 1;
}


// REVENUE TAHUN INI
$currentYearRevenueData = query("
    SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
    FROM reservations
    INNER JOIN extra ON reservations.extraId = extra.extraId
    WHERE YEAR(checkInDate) = ?
", [$currentYear]);

$currentYearReveue = $currentYearRevenueData[0]['totalRevenue'] ?? 0;

// REVENUE TAHUN LALU
$previousYearRevenueData = query("
    SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
    FROM reservations
    INNER JOIN extra ON reservations.extraId = extra.extraId
    WHERE YEAR(checkInDate) = ?
", [$previousYear]);

$previousYearRevenue = $previousYearRevenueData[0]['totalRevenue'] ?? 0;

// CEK PRESENTASE
if ($previousYearRevenue > 0) {
  $yearPrecentange = (($currentYearReveue - $previousYearRevenue) / $previousYearRevenue) * 100;

  if ($yearPrecentange > 0) {
    $statusYear = "naik";
  } elseif ($yearPrecentange < 0) {
    $statusYear = "turun";
  } else {
    $statusYear = "sama";
  }
} else {
  $yearPrecentange = $currentYearReveue > 0 ? 100 : 0;
  $statusYear = $currentYearReveue > 0 ? "naik" : "tidak ada pendapatan";
}

// REVENUE BULAN INI
$currentMonthRevenueData = query("
    SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
    FROM reservations
    INNER JOIN extra ON reservations.extraId = extra.extraId
    WHERE MONTH(checkInDate) = ? AND YEAR(checkInDate) = ?
", [$currentMonth, $currentYear]);

$currentMonthRevenue = $currentMonthRevenueData[0]['totalRevenue'] ?? 0;

// REVENUE BULAN LALU
$previousMonthRevenueData = query("
    SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
    FROM reservations
    INNER JOIN extra ON reservations.extraId = extra.extraId
    WHERE MONTH(checkInDate) = ? AND YEAR(checkInDate) = ?
", [$previousMonth, $currentYear]);

$previousMonthRevenue = $previousMonthRevenueData[0]['totalRevenue'] ?? 0;

// CEK PRESENTASE
if ($previousMonthRevenue > 0) {
  $changePercentage = (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100;

  if ($changePercentage > 0) {
    $status = "naik";
  } elseif ($changePercentage < 0) {
    $status = "turun";
  } else {
    $status = "sama";
  }
} else {
  $changePercentage = $currentMonthRevenue > 0 ? 100 : 0;
  $status = $currentMonthRevenue > 0 ? "naik" : "tidak ada pendapatan";
}


// HARIAN
$currentDayRevenueData = query("
SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
FROM reservations
INNER JOIN extra ON reservations.extraId = extra.extraId
WHERE DAY(checkInDate) = ? AND MONTH(checkInDate) = ?
", [$currentDay, $currentMonth]);
$currentDayRevenue = $currentDayRevenueData[0]['totalRevenue'] ?? 0;

// KEMARIN
$previousDayRevenueData = query("
    SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
    FROM reservations
    INNER JOIN extra ON reservations.extraId = extra.extraId
    WHERE DAY(checkInDate) = ? AND MONTH(checkInDate) = ?
", [$previousDay, $currentMonth]);
$previousDayRevenue = $previousDayRevenueData[0]['totalRevenue'] ?? 0;

// CEK PRESENTASE
if ($previousDayRevenue > 0) {
  $percentange = (($currentDayRevenue - $previousDayRevenue) / $previousDayRevenue) * 100;

  if ($percentange > 0) {
    $statusDay = "naik";
  } elseif ($percentange < 0) {
    $statusDay = "turun";
  } else {
    $statusDay = "sama";
  }
} else {
  $percentange = $currentDayRevenue > 0 ? 100 : 0;
  $statusDay = $currentDayRevenue > 0 ? "naik" : "tidak ada pendapatan";
}
?>

<div class="card shadow mb-2 w-100">
  <?php
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=Revenue_Report.xls");
  ?>
  <style>
    h3 {
      font-weight: bold;
      text-align: center;
      text-transform: uppercase;
    }

    table {
      border: 1px solid black;
      text-align: left;
      border-collapse: collapse;
      width: 100%
    }

    table thead th {
      border: 1px solid black;
      text-align: left;
    }

    table tbody td {
      border: 1px solid black;
      text-align: left;
    }

    strong {
      text-transform: uppercase;
    }
  </style>

  <h3 class="text-center">Revenue Report</h3>
  <table class="table table-striped" border="1" id="tableRoom">
    <thead>
      <tr>
        <th scope="col">Period</th>
        <th scope="col">Rate</th>
        <th scope="col">Previous Revenue</th>
        <th scope="col">Current Revenue</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>This Year</td>
        <td><?= ($statusYear == 'naik') ? '+' : '' ?><?= ubahKePersen($yearPrecentange, 100) ?></td>
        <td><?= rupiah($previousYearRevenue) ?></td>
        <td><?= rupiah($currentYearReveue) ?></td>
      </tr>
      <tr>
        <td>This Month</td>
        <td><?= ($status == 'naik') ? '+' : '' ?><?= ubahKePersen($changePercentage, 100) ?></td>
        <td><?= rupiah($previousMonthRevenue) ?></td>
        <td><?= rupiah($currentMonthRevenue) ?></td>
      </tr>
      <tr>
        <td>Today</td>
        <td><?= ($statusDay == 'naik') ? '+' : '' ?><?= ubahKePersen($percentange, 100) ?></td>
        <td><?= rupiah($previousDayRevenue) ?></td>
        <td><?= rupiah($currentDayRevenue) ?></td>
      </tr>
    </tbody>
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Month</th>
        <th scope="col">Revenue</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $currentYear = date('Y');

      for ($month = 1; $month <= 12; $month++) {
        $perMonthRevenueData = query("
      SELECT SUM(totalPrice + IFNULL(extra.price, 0)) AS totalRevenue
      FROM reservations
      INNER JOIN extra ON reservations.extraId = extra.extraId
      WHERE MONTH(checkInDate) = ? AND YEAR(checkInDate) = ?
  ", [$month, $currentYear]);

        $perMonthRevenue = $perMonthRevenueData[0]['totalRevenue'] ?? 0;
      ?>
        <tr>
          <td><?= $no; ?></td>
          <td><?= namaBulan($month); ?></td>
          <td><?= rupiah($perMonthRevenue); ?></td>
        </tr>
      <?php $no++;
      } ?>
    </tbody>
  </table>
</div>