<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

$flagKelas = isset($_POST['flagKelas']) ? $_POST['flagKelas'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$idJurusan = isset($_POST['idJurusan']) ? $_POST['idJurusan'] : '';
$idJurusanDetail = isset($_POST['idJurusanDetail']) ? $_POST['idJurusanDetail'] : '';
$tingkat = isset($_POST['tingkat']) ? $_POST['tingkat'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagKelas === 'cari') {
  $conditions = '';
  $params = [];

  // Filter berdasarkan idKela

  if (!empty($idJurusan)) {
    if (!empty($conditions)) {
      $conditions .= " AND kelas.idJurusan = ?";
    } else {
      $conditions .= " WHERE kelas.idJurusan = ?";
    }
    $params[] = $idJurusan;
  }

  if (!empty($searchQuery)) {
    if (!empty($conditions)) {
      $conditions .= " AND (kelas.nama LIKE ? OR jurusan.namaJurusan LIKE ?)";
    } else {
      $conditions .= " WHERE (kelas.nama LIKE ? OR jurusan.namaJurusan LIKE ?)";
    }
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
  }
}

// Total data untuk pagination
$totalQuery = "SELECT COUNT(*) as total 
       FROM kelas 
       INNER JOIN jurusan ON kelas.idJurusan = jurusan.idJurusan
       LEFT JOIN detail_kelas ON kelas.idKelas = detail_kelas.idKelas" . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

// Query utama
$query = "SELECT kelas.idKelas,
         kelas.nama,
         jurusan.idJurusan,
         kelas.tingkat, 
         jurusan.namaJurusan,
         detail_kelas.idPegawai as idGuru
      FROM kelas 
      INNER JOIN jurusan ON kelas.idJurusan = jurusan.idJurusan
      LEFT JOIN detail_kelas ON kelas.idKelas = detail_kelas.idKelas" . $conditions . " ORDER BY kelas.nama ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$kelas = query($query, $params);
$jurusan = query("SELECT * FROM jurusan", []);

?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">Kelas</th>
        <th scope="col">Jurusan</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($kelas) {

        $no = $offset + 1;
        foreach ($kelas as $rm):
        
      ?>
          <tr>
            <td><?= $no ?></td>
            <td>
              <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
              </button>
              <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#kelasModal" onclick="editKelasModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-edit"></i> <strong>EDIT</strong>
                </button>
                <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteKelas('<?= $rm['idKelas'] ?>')">
                  <i class="fa fa-trash"></i> <strong>DELETE</strong>
                </button>
                <button type="button" class="btn btn-info btn-sm tombol-dropdown-last" onclick="loadDetailKelas(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-info-circle"></i> <strong>DETAIL</strong>
                </button>
              </div>
            </td>
            <td><?= $rm['nama'] ?></td>
            <td><?= $rm['namaJurusan'] ?></td>
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
<div class="modal fade" id="kelasModal" tabindex="-1" aria-labelledby="kelasModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="kelasModalLabel">Form Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formKelas" method="post">
          <input type="hidden" id="idKelas" name="idKelas" autocomplete="off">
          <input type="hidden" id="flagKelas" name="flagKelas" autocomplete="off">
          <div class="row mb-3">
            <div class="col">
              <label for="nama" class="form-label">Nama Kelas</label>
              <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Kelas" autocomplete="off">
            </div>
            <div class="col">
              <label for="nama" class="form-label">Tingkat</label>
              <input type="text" name="tingkat" id="tingkat" class="form-control" placeholder="Masukkan Tingkat Kelas" autocomplete="off">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
              <label for="idJurusan" class="form-label">Jurusan</label>
              <select class="form-select" id="idJurusan" name="idJurusan" style="width: 100%;">
                <option value="">Pilih Jurusan</option>
                <?php foreach ($jurusan as $jr): ?>
                  <option value="<?= $jr["idJurusan"] ?>"><?= $jr["namaJurusan"] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="prosesKelas()">Save changes</button>
      </div>
    </div>
  </div>
</div>







<script>

  document.getElementById('flagKelas').value = 'add';
  $('#kelasModal').on('hidden.bs.modal', function() {
    $('#formKelas')[0].reset();
    document.getElementById('flagKelas').value = 'add';
  });

  
  
</script>