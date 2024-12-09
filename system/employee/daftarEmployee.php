<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

$flagEmployee = isset($_POST['flagEmployee']) ? $_POST['flagEmployee'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$roleId = isset($_POST['roleId']) ? $_POST['roleId'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagEmployee === 'cari') {

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
$role = query("SELECT * FROM employeeroles");
?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">Name</th>
        <th scope="col">Role</th>
        <th scope="col">Phone Number</th>
        <th scope="col">Email</th>
        <th scope="col">Address</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($employee) {

        $no = $offset + 1;
        foreach ($employee as $rm):
      ?>
          <tr>
            <td><?= $no ?></td>
            <td>
              <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
              </button>
              <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#employeeModal" onclick="editEmployeeModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-edit"></i> <strong>EDIT</strong>
                </button>
                <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteEmployee('<?= $rm['employeeId'] ?>')">
                  <i class="fa fa-trash"></i> <strong>DELETE</strong>
                </button>
              </div>
            </td>
            <td><?= $rm['name'] ?></td>
            <td><?= $rm['roleName'] ?></td>
            <td><?= $rm['phoneNumber'] ?></td>
            <td><?= $rm['email'] ?></td>
            <td><?= $rm['address'] ?></td>
          </tr>
        <?php
          $no++;
        endforeach;
      } else {
        ?>

        <tr>
          <td colspan="10">
            <p class="text-center font-weight-bold">No Result!</p>
          </td>

        </tr>

      <?php } ?>
    </tbody>
  </table>

  <!-- Pagination Controls -->
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <button class="page-link" onclick="loadPage(<?= $page - 1 ?>)">Previous</button>
        </li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <button class="page-link" onclick="loadPage(<?= $i ?>)"><?= $i ?></button>
        </li>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
        <li class="page-item">
          <button class="page-link" onclick="loadPage(<?= $page + 1 ?>)">Next</button>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<!-- Modal Edit extra -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Employee Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEmployee" method="post">
          <input autocomplete="off" type="hidden" id="employeeId" name="employeeId">
          <input autocomplete="off" type="text" id="flagEmployee" name="flagEmployee">
          <div class="form-group">
            <label for="extraNumber">Name</label>
            <input autocomplete="off" type="text" name="name" id="name" class="form-control" placeholder="Add Employee Name" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="">Role</label>
            <select class="custom-select" id="roleId" name="roleId">
              <option value="">Choose...</option>
              <?php foreach ($role as $rt): ?>
                <option value="<?= $rt["roleId"] ?>">
                  <?= $rt["roleName"] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="extraNumber">Phone Number</label>
            <input autocomplete="off" type="text" name="phoneNumber" id="phoneNumber" class="form-control" placeholder="Add Employee Phone Number" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="extraNumber">Email</label>
            <input autocomplete="off" type="text" name="email" id="email" class="form-control" placeholder="Add Employee Email" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="extraNumber">Address</label>
            <input autocomplete="off" type="text" name="address" id="address" class="form-control" placeholder="Add Employee Adress" autocomplete="off">
          </div>


        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="prosesEmployee()">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.getElementById('flagEmployee').value = 'add';
  $('#employeeModal').on('hidden.bs.modal', function() {
    $('#formEmployee')[0].reset();
    document.getElementById('flagEmployee').value = 'add';
  });
</script>