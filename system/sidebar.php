<?php
// Fungsi untuk mendapatkan nama direktori saat ini
$current_dir = getCurrentDirectory();

// Daftar menu dalam DATA
$data_menu = [
    'user' => 'User',
    'siswa' => 'Siswa',
    'pegawai' => 'Pegawai',
    'kelas' => 'Kelas',
    'jurusan' => 'Jurusan', // Tambahkan jurusan sebagai navigasi
    'mapel' => 'Mapel',
    'jadwal' => 'Jadwal'
];
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL_HTML ?>/sion/system/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SION ESEMKA</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($current_dir == BASE_URL_HTML . '/system') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= BASE_URL_HTML ?>/system/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - DATA -->
    <?php 
    $data_active = in_array(str_replace(BASE_URL_HTML . '/system/', '', $current_dir), array_keys($data_menu));
    ?>
    <li class="nav-item <?= $data_active ? 'active' : '' ?>">
        <a class="nav-link" data-toggle="collapse" data-target="#collapseData" 
            aria-expanded="<?= $data_active ? 'true' : 'false' ?>" aria-controls="collapseData">
            <i class="fas fa-fw fa-database"></i>
            <span>DATA</span>
        </a>
        <div id="collapseData" class="collapse <?= $data_active ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <?php foreach ($data_menu as $key => $value): ?>
                    <a class="collapse-item font-weight-bold text-white <?= ($current_dir == BASE_URL_HTML . "/system/$key") ? 'bg-white text-dark' : '' ?>" 
                       href="<?= BASE_URL_HTML ?>/system/<?= $key ?>/"><?= $value ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
