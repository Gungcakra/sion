<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);
$idJurusanDetail = isset($_POST['idJurusanDetail']) ? $_POST['idJurusanDetail'] : '';
$tingkat = isset($_POST['tingkat']) ? $_POST['tingkat'] : '';
$idKelas = isset($_POST['idKelas']) ? $_POST['idKelas'] : '';
$idGuru = isset($_POST['idGuru']) ? $_POST['idGuru'] : '';

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
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <form id="formDetailKelas" method="post">
                    <input autocomplete="off" type="hidden" id="idKelas" name="idKelas" value="<?= $idKelas ?>">
                   

                    <div class="form-row mb-4">
                        <div class="col-md-6 d-flex flex-column">

                            <label for="idPegawaiSelect" class="font-weight-bold">Guru Wali</label>
                            <select class="form-select" id="idPegawaiSelect" name="idPegawaiSelect" data-live-search="true">
                                <option value="">Pilih Guru Wali</option>
                                <?php foreach ($pegawai as $gr): ?>
                                    <option value="<?= $gr["idPegawai"] ?>"><?= $gr["nama"] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary" onclick="addDetailGuru('<?= isset($dataUpdate['idPegawai']) ? 'updateDetailGuru' : 'addDetailGuru' ?>')"><?= isset($dataUpdate['idPegawai']) ? 'Update' : 'Simpan' ?></button>
                        </div>
                    </div>
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
                            <button type="button" class="btn btn-primary" onclick="prosesDetailKelas()">Simpan</button>
                        </div>

                    </div>
                    <!-- Table for Displaying Siswa in Kelas -->
                    <div id="daftarDetailKelas">

                    </div>
            </div>

            </form>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeDetailModal()">Close</button>
            </div>
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
            $('#idPegawaiSelect').select2({
                dropdownParent: $('#detailKelasModal')
            });
        });

        $('#detailKelasModal').on('hidden.bs.modal', function() {
            // Hapus inisialisasi Select2 untuk mencegah masalah
            $('#idSiswaSelect').select2('destroy');
            $('#idPegawaiSelect').select2('destroy');
            $('#idPegawaiSelect').val('').trigger('change');
        });
    });
</script>