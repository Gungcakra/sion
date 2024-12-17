<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

$flagMapel = isset($_POST['flagMapel']) ? $_POST['flagMapel'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagMapel === 'cari') {
  $conditions = '';
  $params = [];


 if (!empty($searchQuery)) {
    if (!empty($conditions)) {
      $conditions .= " AND nama LIKE ?";
    } else {
      $conditions .= " WHERE nama LIKE ?";
    }
    $params[] = "%$searchQuery%";
  }
}

// Total data untuk pagination
$totalQuery = "SELECT COUNT(*) as total 
             FROM mapel " . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

// Query utama
$query = "SELECT * FROM mapel " . $conditions . " ORDER BY nama ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$mapel = query($query, $params);

// var_dump($siswa[0])
?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">Nama Mapel</th>
        <th scope="col">Kode Mapel</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($mapel) {

        $no = $offset + 1;
        foreach ($mapel as $rm):
      ?>
          <tr>
            <td><?= $no ?></td>
            <td>
              <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
              </button>
              <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#mapelModal" onclick="editMapelModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-edit"></i> <strong>EDIT</strong>
                </button>
                <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteMapel('<?= $rm['idMapel'] ?>')">
                  <i class="fa fa-trash"></i> <strong>DELETE</strong>
                </button>
              </div>
            </td>
            <td><?= $rm['nama'] ?></td>
            <td><?= $rm['kode'] ?></td>
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
<div class="modal fade" id="mapelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Mapel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formMapel" method="post">
          <input autocomplete="off" type="hidden" id="idMapel" name="idMapel">
          <input autocomplete="off" type="hidden" id="flagMapel" name="flagMapel">
          <div class="input-group mb-2">
            <div class="col">
              <label for="extraNumber">Nama Mapel</label>
              <input autocomplete="off" type="text" name="nama" id="nama" class="form-control" placeholder="Masukan Nama Mapel" autocomplete="off">
            </div>
            <div class="col">
              <label for="extraNumber">Kode Mapel</label>
              <input autocomplete="off" type="text" name="kode" id="kode" class="form-control" placeholder="Masukan Nama Mapel" autocomplete="off">
            </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="prosesMapel()">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.getElementById('flagMapel').value = 'add';
  $('#mapelModal').on('hidden.bs.modal', function() {
    $('#formMapel')[0].reset();
    document.getElementById('flagMapel').value = 'add';
  });
</script>