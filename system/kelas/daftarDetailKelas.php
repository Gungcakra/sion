<?php
session_start();
require_once "../../library/konfigurasi.php";
checkUserSession($db);
$idKelas = isset($_POST['idKelas']) ? $_POST['idKelas'] : '';
$detailKelas = query("SELECT detail_kelas.*,
siswa.nama as namaSiswa,
siswa.nis
FROM detail_kelas
INNER JOIN siswa ON detail_kelas.idSiswa = siswa.idSiswa
WHERE detail_kelas.idKelas = ? AND detail_kelas.idSiswa IS NOT NULL", [$idKelas]);
?>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Action</th>
            <th scope="col">NIS</th>
            <th scope="col">Nama Siswa</th>
        </tr>
    </thead>
    <tbody id="detailKelasTableBody">
        <?php

        foreach ($detailKelas as $key => $dk) {
        ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="deleteSiswaFromKelas(<?= $dk['idDetailKelas'] ?>)">Delete</button>
                </td>
                <td><?= $dk['nis'] ?></td>
                <td><?= $dk['namaSiswa'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>