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


  <table class="table table-striped">
    <thead>
      <tr>
        <th class="text-dark font-weight-bold" scope="col">Period</th>
        <th class="text-dark font-weight-bold" scope="col">Rate</th>
        <th class="text-dark font-weight-bold" scope="col">Previous Revenue</th>
        <th class="text-dark font-weight-bold" scope="col">Current Revenue</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="font-weight-bold text-dark">This Year</td>
        <td class="font-weight-bold text-dark"><?= ($statusYear == 'naik') ? '+' : '' ?><?= ubahKePersen($yearPrecentange, 100) ?> <i class="fa-solid <?= $statusYear == 'naik' ? 'fa-caret-up text-success' : ($statusYear == 'turun' ? 'fa-caret-down text-danger' : 'fa-equals text-primary') ?>"></i></td>
        <td class="font-weight-bold text-dark"><?= rupiah($previousYearRevenue) ?></td>
        <td class="font-weight-bold text-dark"><?= rupiah($currentYearReveue) ?></td>
      </tr>
      <tr>
        <td class="font-weight-bold text-dark">This Month</td>
        <td class="font-weight-bold text-dark"><?= ($status == 'naik') ? '+' : '' ?><?= ubahKePersen($changePercentage, 100) ?> <i class="fa-solid <?= $status == 'naik' ? 'fa-caret-up text-success' : ($status == 'turun' ? 'fa-caret-down text-danger' : 'fa-equals text-primary') ?>"></i></td>
        <td class="font-weight-bold text-dark"><?= rupiah($previousMonthRevenue) ?></td>
        <td class="font-weight-bold text-dark"><?= rupiah($currentMonthRevenue) ?></td>
      </tr>
      <tr>
        <td class="font-weight-bold text-dark">Today</td>
        <td class="font-weight-bold text-dark"><?= ubahKePersen($percentange, 100) ?> <i class="fa-solid <?= $statusDay == 'naik' ? 'fa-caret-up text-success' : ($statusDay == 'turun' ? 'fa-caret-down text-danger' : 'fa-equals text-primary') ?>"></i></td>
        <td class="font-weight-bold text-dark"><?= rupiah($previousDayRevenue) ?></td>
        <td class="font-weight-bold text-dark"><?= rupiah($currentDayRevenue) ?></td>
      </tr>
    </tbody>
  </table>

  <table class="table table-striped">
    <thead>
      <tr>
        <th class="text-dark font-weight-bold" scope="col">#</th>
        <th class="text-dark font-weight-bold" scope="col">Month</th>
        <th class="text-dark font-weight-bold" scope="col">Revenue</th>
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
          <td class="text-dark font-weight-bold"><?= $no; ?></td>
          <td class="text-dark font-weight-bold"><?= namaBulan($month); ?></td>
          <td class="text-dark font-weight-bold"><?= rupiah($perMonthRevenue); ?></td>
        </tr>
      <?php $no++;
      } ?>

    </tbody>
  </table>




</div>