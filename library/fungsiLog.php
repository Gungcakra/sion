<?php
include_once "konfigurasi.php";
function logOut(){

session_destroy();

echo '<script>window.location.href = "'.BASE_URL_HTML.'";</script>';

}