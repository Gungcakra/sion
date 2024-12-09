<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once "../../library/konfigurasi.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiRupiah.php";

//CEK USER
checkUserSession($db);

// CEK STATUS ROOM
$currentDate = date("Y-m-d");

$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$roleId = isset($_POST['roleId']) ? $_POST['roleId'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flag === 'cari') {

  if (!empty($roleId)) {
    $searchQuery = '';
    $conditions .= " WHERE employees.roleId = ?";
    $params[] = $roleId;
  }
  if (!empty($searchQuery)) {
    $roleId = '';
    $conditions .= " WHERE employees.name LIKE ?";
    $params[] = "%$searchQuery%";
  }
}

$totalQuery = "SELECT COUNT(*) as total FROM employees " . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "SELECT employees.*, 
                 employeeroles.roleName 
          FROM employees 
          INNER JOIN employeeroles 
          ON employees.roleId = employeeroles.roleId" . $conditions . " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$employee = query($query, $params);
$guest = query("SELECT *
FROM guests
WHERE guestId NOT IN (
    SELECT guestId 
    FROM reservations 
    WHERE checkOutDate >= ?
)
ORDER BY name ASC;
;
", [$currentDate]);
$extra = query("SELECT * FROM extra ORDER BY name ASC");
$room = query(
  "SELECT rooms.*,
	               roomtypes.typeName,
                 roomtypes.price
          FROM rooms INNER JOIN roomtypes 
          ON rooms.roomTypeId = roomtypes.roomTypeId
          WHERE rooms.status = ?",
  ['Available']
);
$userId = $_SESSION['userId'];
?>

<div class="card p-2 m-2 rounded p-1 mb-2 w-100">
  <p class="m-1 fs-1 font-weight-bold">Add Reservation</p>

  <form id="formReservation" method="post" class="d-flex">
    <input type="hidden" id="userId" name="userId" value="<?= $userId ?>">
    <div class="col-4">
      <div class="form-group m-1 mb-2">
        <label for="" class="font-weight-bold">Guest</label>
        <select class="custom-select" id="guestId" name="guestId">
          <option value="">Choose...</option>
          <?php foreach ($guest as $rt): ?>
            <option value="<?= $rt["guestId"] ?>">
              <?= $rt["name"] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group m-1 mb-4">
        <label for="" class="font-weight-bold">Room</label>
        <select class="custom-select" id="roomId" name="roomId" data-live-search="true">
          <option value="">Choose...</option>
          <?php foreach ($room as $rt): ?>
            <option value="<?= $rt["roomId"] ?>" data-price="<?= $rt["price"] ?>">
              <?= $rt["roomNumber"] ?> - <?= $rt['typeName'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group m-1 mb-4">
        <label for="" class="font-weight-bold">Extra</label>
        <select class="custom-select" id="extraId" name="extraId" data-live-search="true">
          <option value="">Choose...</option>
          <?php foreach ($extra as $rt): ?>
            <option value="<?= $rt["extraId"] ?>" data-price="<?= $rt["price"] ?>">
              <?= $rt['name'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group m-1">
        <label for="rentang " class="font-weight-bold"> Check In - Out</label>
        <div class="input-group">
          <input type="text" id="rentang" name="rentang" class="form-control" data-date-range="true">
          <div class="input-group-append">
            <span class="input-group-text"> <i class="fa-solid fa-calendar-days"></i>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-4">
      <div class="row">

        <div class="col m-1">
          <label class="fs-2 font-weight-bold">Adult</label>
          <input type="number" id="adult" class="form-control" name="adult" value="0">
        </div>
        <div class="col m-1">
          <label class="fs-2 font-weight-bold">Child</label>
          <input type="number" id="child" class="form-control" name="child" value="0">
        </div>
      </div>
      <div class="form-group m-1">
        <label class="fs-2 font-weight-bold">Bill</label>
        <input type="text" id="priceDisplay" class="form-control" name="priceDisplay" placeholder="Room Price" readonly>
        <input type="hidden" id="totalPrice" class="form-control" name="totalPrice" placeholder="Room Price" readonly>
      </div>
    </div>
  </form>
  <button type="button" class="btn btn-primary mt-2" onclick="prosesReservation()">Save</button>
</div>
</div>




<script>
  $(function() {
    // Initialize Select2 for room and guest selection
    $('#roomId').select2();
    $('#guestId').select2();
    $('#extraId').select2();

    // Update room price based on selected room and date range
    $('#roomId, #extraId, #rentang').on('change', function() {
      const roomPrice = $('#roomId').find(':selected').data('price') || 0;
      const extraPrice = $('#extraId').find(':selected').data('price') || 0;
      const dateRange = $('#rentang').val().split(' - ');

      if (dateRange.length === 2) {
        const startDate = new Date(dateRange[0]);
        const endDate = new Date(dateRange[1]);
        const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
        const totalPrice = (roomPrice + extraPrice) * days;
        $('#priceDisplay').val(totalPrice.toLocaleString("id-ID", {
          style: "currency",
          currency: "IDR"
        }));
        $('#totalPrice').val(totalPrice || '')
      } else {
        $('#totalPrice').val('');
      }
    });


  });

  $(function() {
    $('#rentang').daterangepicker({
      opens: 'left'
    });
  });
</script>