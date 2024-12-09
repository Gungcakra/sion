<?php
include_once ".././library/konfigurasi.php";

session_destroy();

echo '<script>window.location.href = "'.BASE_URL_HTML.'";</script>';