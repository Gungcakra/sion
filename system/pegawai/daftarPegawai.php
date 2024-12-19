<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

$flagPegawai = isset($_POST['flagPegawai']) ? $_POST['flagPegawai'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$idJabatan = isset($_POST['idJabatan']) ? $_POST['idJabatan'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagPegawai === 'cari') {
  $conditions = '';
  $params = [];

  // Filter berdasarkan idJabatan
  if (!empty($idJabatan)) {
    $conditions .= " WHERE pegawai.idJabatan = ?";
    $params[] = $idJabatan;
  }



  if (!empty($searchQuery)) {
    if (!empty($conditions)) {
      $conditions .= " AND pegawai.nama LIKE ?";
    } else {
      $conditions .= " WHERE pegawai.nama LIKE ?";
    }
    $params[] = "%$searchQuery%";
  }
}

// Total data untuk pagination
$totalQuery = "SELECT COUNT(*) as total 
             FROM pegawai 
             INNER JOIN jabatan ON pegawai.idJabatan = jabatan.idJabatan " . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

// Query utama
$query = "SELECT pegawai.*, 
               jabatan.nama AS namaJabatan
        FROM pegawai 
        INNER JOIN jabatan ON pegawai.idJabatan = jabatan.idJabatan " . $conditions .
  " ORDER BY pegawai.nama ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$pegawai = query($query, $params);

$jabatan = query("SELECT * FROM jabatan", []);

// var_dump($pegawai[0])
?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">NIP</th>
        <th scope="col">Nama</th>
        <th scope="col">Jabatan</th>
        <th scope="col">No Telp</th>
        <th scope="col">Alamat</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($pegawai) {

        $no = $offset + 1;
        foreach ($pegawai as $rm):
      ?>
          <tr>
            <td><?= $no ?></td>
            <td>
              <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
              </button>
              <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#pegawaiModal" onclick="editPegawaiModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-edit"></i> <strong>EDIT</strong>
                </button>
                <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deletePegawai('<?= $rm['idPegawai'] ?>')">
                  <i class="fa fa-trash"></i> <strong>DELETE</strong>
                </button>
              </div>
            </td>
            <td><?= $rm['nip'] ?></td>
            <td><?= $rm['nama'] ?></td>
            <td><?= $rm['namaJabatan'] ?></td>
            <td><?= $rm['noTelp'] ?></td>
            <td><?= $rm['alamat'] ?></td>
            <!-- <td><a class="p-1 text-white rounded font-weight-bold bg-<?= $rm['status'] == "Aktif" ? "success" : "danger"  ?>"><?= $rm['status'] ?></a></td> -->
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
<div class="modal fade" id="pegawaiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Pegawai</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formPegawai" method="post">
          <input autocomplete="off" type="hidden" id="idPegawai" name="idPegawai">
          <input autocomplete="off" type="hidden" id="flagPegawai" name="flagPegawai">
          <div class="input-group mb-2">
            <div class="col">
              <label for="nip">NIP</label>
              <input autocomplete="off" type="text" name="nip" id="nip" class="form-control" placeholder="Masukan NIP Pegawai" autocomplete="off">
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="nama">Nama</label>
              <input autocomplete="off" type="text" name="nama" id="nama" class="form-control" placeholder="Masukan Nama Pegawai" autocomplete="off">
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="noTelp">No Telp</label>
              <input autocomplete="off" type="text" name="noTelp" id="noTelp" class="form-control" autocomplete="off" placeholder="Masukan Nomor Telp Pegawai">
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="alamat">Alamat</label>
              <input autocomplete="off" type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukan Alamat" autocomplete="off">
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="idJabatan">Jabatan</label>
              <select class="custom-select" id="idJabatan" name="idJabatan" style="width: 100%">
          <option value="">Pilih Jabatan</option>
          <?php foreach ($jabatan as $jb): ?>
            <option value="<?= $jb["idJabatan"] ?>">
              <?= $jb["nama"] ?>
            </option>
          <?php endforeach; ?>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="prosesPegawai()">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.getElementById('flagPegawai').value = 'add';
  $('#pegawaiModal').on('hidden.bs.modal', function() {
    $('#formPegawai')[0].reset();
    document.getElementById('flagPegawai').value = 'add';
  });
</script>