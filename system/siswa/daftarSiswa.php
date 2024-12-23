<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

$flagSiswa = isset($_POST['flagSiswa']) ? $_POST['flagSiswa'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$idJurusan = isset($_POST['idJurusan']) ? $_POST['idJurusan'] : '';
$idAngkatan = isset($_POST['idAngkatan']) ? $_POST['idAngkatan'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagSiswa === 'cari') {
  $conditions = '';
  $params = [];

  // Filter berdasarkan idJurusan
  if (!empty($idJurusan)) {
    $conditions .= " WHERE siswa.idJurusan = ?";
    $params[] = $idJurusan;
  }

  if (!empty($idAngkatan)) {
    if (!empty($conditions)) {
      $conditions .= " AND angkatan.idAngkatan = ?";
    } else {
      $conditions .= " WHERE angkatan.idAngkatan = ?";
    }
    $params[] = $idAngkatan;
  }



  if (!empty($searchQuery)) {
    if (!empty($conditions)) {
      $conditions .= " AND siswa.nama LIKE ?";
    } else {
      $conditions .= " WHERE siswa.nama LIKE ?";
    }
    $params[] = "%$searchQuery%";
  }
}

// Total data untuk pagination
$totalQuery = "SELECT COUNT(*) as total 
             FROM siswa 
             INNER JOIN jurusan ON siswa.idJurusan = jurusan.idJurusan
             INNER JOIN angkatan ON siswa.idAngkatan = angkatan.idAngkatan " . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

// Query utama
$query = "SELECT siswa.*, 
               jurusan.namaJurusan, 
               siswa.idSiswa,
               angkatan.tahunAngkatan, 
               CASE   
                   WHEN YEAR(CURDATE()) - angkatan.tahunAngkatan = 0 THEN 'X'
                   WHEN YEAR(CURDATE()) - angkatan.tahunAngkatan = 1 THEN 'XI'
                   WHEN YEAR(CURDATE()) - angkatan.tahunAngkatan = 2 THEN 'XII'
                   ELSE 'Lulus'
               END AS tingkat 
        FROM siswa 
        INNER JOIN jurusan ON siswa.idJurusan = jurusan.idJurusan
        INNER JOIN angkatan ON siswa.idAngkatan = angkatan.idAngkatan " . $conditions .
  " ORDER BY angkatan.tahunAngkatan DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$siswa = query($query, $params);

$jurusan = query("SELECT * FROM jurusan", []);
$angkatan = query("SELECT * FROM angkatan", []);

// var_dump($siswa[0])
?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">NIS</th>
        <th scope="col">NISN</th>
        <th scope="col">Nama</th>
        <th scope="col">Angkatan</th>
        <th scope="col">Tingkat</th>
        <th scope="col">Jurusan</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($siswa) {

        $no = $offset + 1;
        foreach ($siswa as $rm):
      ?>
          <tr>
            <td><?= $no ?></td>
            <td>
              <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
              </button>
              <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#siswaModal" onclick="editSiswaModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-edit"></i> <strong>EDIT</strong>
                </button>
                <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteSiswa('<?= $rm['idSiswa'] ?>')">
                  <i class="fa fa-trash"></i> <strong>DELETE</strong>
                </button>
              </div>
            </td>
            <td><?= $rm['nis'] ?></td>
            <td><?= $rm['nisn'] ?></td>
            <td><?= $rm['nama'] ?></td>
            <td><?= $rm['tahunAngkatan'] ?></td>
            <td><?= $rm['tingkat'] ?></td>
            <td><?= $rm['namaJurusan'] ?></td>
            <td><a class="p-1 text-white rounded font-weight-bold bg-<?= $rm['status'] == "Aktif" ? "success" : "danger"  ?>"><?= $rm['status'] ?></a></td>
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
<div class="modal fade" id="siswaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Siswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formSiswa" method="post">
          <input autocomplete="off" type="hidden" id="idSiswa" name="idSiswa">
          <input autocomplete="off" type="hidden" id="flagSiswa" name="flagSiswa">
          <div class="input-group mb-2">
            <div class="col">
              <label for="extraNumber">NIS</label>
              <input autocomplete="off" type="text" name="nis" id="nis" class="form-control" placeholder="Masukan NIS Siswa" autocomplete="off">
            </div>
            <div class="col">
              <label for="extraNumber">NISN</label>
              <input autocomplete="off" type="text" name="nisn" id="nisn" class="form-control" placeholder="Masukan NISN Siswa" autocomplete="off">
            </div>
          </div>

          <div class="input-group mb-2">
            <div class="col">
              <label for="extraNumber">Nama</label>
              <input autocomplete="off" type="text" name="nama" id="nama" class="form-control" placeholder="Masukan Nama Siswa" autocomplete="off">
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="extraNumber">Tanggal Lahir</label>
              <input autocomplete="off" type="date" name="tglLahir" id="tglLahir" class="form-control" autocomplete="off">
            </div>
            <div class="col">
              <label for="extraNumber">No Telp</label>
              <input autocomplete="off" type="text" name="noTelp" id="noTelp" class="form-control" autocomplete="off" placeholder="Masukan Nomor Telp Siswa">
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="extraNumber">Alamat</label>
              <input autocomplete="off" type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukan Alamat" autocomplete="off">
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="">Kelas</label>
              <select class="custom-select" id="idJurusan" name="idJurusan" style="width: 100%">
                <option value="">Pilih Kelas</option>
                <?php foreach ($jurusan as $kl): ?>
                  <option value="<?= $kl["idJurusan"] ?>">
                    <?= $kl["nama"] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col">
              <label for="">Angkatan</label>
              <select class="custom-select" id="idAngkatan" name="idAngkatan" style="width: 100%">
                <option value="">Pilih Angkatan</option>
                <?php foreach ($angkatan as $kl): ?>
                  <option value="<?= $kl["idAngkatan"] ?>">
                    <?= $kl["tahunAngkatan"] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="input-group mb-2">
            <div class="col">
              <label for="extraNumber">Nama Ayah</label>
              <input autocomplete="off" type="text" name="namaAyah" id="namaAyah" class="form-control" placeholder="Masukan Nama Ayah Siswa" autocomplete="off">
            </div>
            <div class="col">
              <label for="extraNumber">Nama Ibu</label>
              <input autocomplete="off" type="text" name="namaIbu" id="namaIbu" class="form-control" placeholder="Masukan Nama Ibu Siswa" autocomplete="off">
            </div>
          </div>



        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="prosesSiswa()">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.getElementById('flagSiswa').value = 'add';
  $('#siswaModal').on('hidden.bs.modal', function() {
    $('#formSiswa')[0].reset();
    document.getElementById('flagSiswa').value = 'add';
  });
</script>