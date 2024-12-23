<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);
$idJurusanDetail = isset($_POST['idJurusanDetail']) ? $_POST['idJurusanDetail'] : '';
$tingkat = isset($_POST['tingkat']) ? $_POST['tingkat'] : '';

$pegawai = query("SELECT * FROM pegawai WHERE idJabatan = ?", [2]);
$siswa = query("
    SELECT siswa.*
    FROM siswa
    INNER JOIN angkatan ON siswa.idAngkatan = angkatan.idAngkatan
    INNER JOIN jurusan ON siswa.idJurusan = jurusan.idJurusan
    WHERE CASE 
        WHEN YEAR(CURDATE()) - angkatan.tahunAngkatan = 0 THEN 'X'
        WHEN YEAR(CURDATE()) - angkatan.tahunAngkatan = 1 THEN 'XI'
        WHEN YEAR(CURDATE()) - angkatan.tahunAngkatan = 2 THEN 'XII'
    END = ? AND jurusan.idJurusan = ?", [$tingkat, $idJurusanDetail]);

?>
<!-- Modal Detail Kelas -->
<div class="modal fade" id="detailKelasModal" tabindex="-1" aria-labelledby="detailKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailKelasLabel">Detail Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDetailKelas" method="post">
                    <input autocomplete="off" type="hidden" id="detailIdKelas" name="idKelas">
                    <input autocomplete="off" type="hidden" id="detailIdSiswa" name="idSiswa">

                    <!-- Siswa Selection -->
                    <div class="form-row mb-4">
                        <div class="col-md-6 d-flex flex-column">
                            <label for="idSiswaSelect" class="font-weight-bold">Siswa</label>
                            <select class="form-select" id="idSiswaSelect" name="idSiswaSelect">
                                <option value="">Pilih Siswa</option>
                                <?php foreach ($siswa as $rt): ?>
                                    <option value="<?= $rt["idSiswa"] ?>"><?= $rt["nama"] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary" onclick="addSiswaToKelas()">Add Siswa</button>
                        </div>
                    </div>

                    <!-- Guru and Kelas Selection (Horizontal Layout) -->
                    <div class="form-row mb-4">
                        <div class="col-md-6 d-flex flex-column">

                            <label for="idGuruSelect" class="font-weight-bold">Guru Wali</label>
                            <select class="form-select" id="idGuruSelect" name="idGuruSelect" data-live-search="true">
                                <option value="">Pilih Guru Wali</option>
                                <?php foreach ($pegawai as $gr): ?>
                                    <option value="<?= $gr["idPegawai"] ?>"><?= $gr["nama"] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>



                </form>

                <!-- Table for Displaying Siswa in Kelas -->
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIS</th>
                            <th scope="col">Nama Siswa</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="detailKelasTableBody">
                        <!-- Table rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#detailKelasModal').on('shown.bs.modal', function() {
            // Inisialisasi Select2 saat modal ditampilkan
            $('#idSiswaSelect').select2({
                dropdownParent: $('#detailKelasModal')
            });
            $('#idGuruSelect').select2({
                dropdownParent: $('#detailKelasModal')
            });
        });

        $('#detailKelasModal').on('hidden.bs.modal', function() {
            // Hapus inisialisasi Select2 untuk mencegah masalah
            $('#idSiswaSelect').select2('destroy');
        });
    });
</script>