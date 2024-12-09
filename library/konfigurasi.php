<?php
if ($_SERVER['HTTP_HOST'] === 'localhost'){
    
    $db = mysqli_connect("localhost","root","","siondb");
}else if($_SERVER['HTTP_HOST'] === 'sion.cakra-portfolio.my.id'){
    $db = mysqli_connect("localhost","u686303384_sion","#sion12","u686303384_siondb");

}
date_default_timezone_set("Asia/Jakarta");

setlocale(LC_TIME, 'id_ID.UTF-8');
// LAMBDA FUNCTION FOR CONCATING CONSTANT
$constant = function (string $name) {
    return constant($name) ?? '';
};

function query($query, $params = []) {
    global $db;
    $stmt = mysqli_prepare($db, $query);

    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);

    $queryType = strtoupper(explode(' ', trim($query))[0]);
    if ($queryType === 'SELECT') {
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $rows;
    } else {
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows; 
    }
}



if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('BASE_URL_HTML', '/sion');
    define('BASE_URL_PHP', dirname(__DIR__)); 
} else {
    define('BASE_URL_HTML', ''); 
    define('BASE_URL_PHP', dirname(__DIR__)); 
}


function checkUserSession($db) {

    if (!isset($_SESSION['userId']) || !isset($_SESSION['csrf_token'])) {
        session_destroy(); 
        header("Location: " . BASE_URL_HTML); 
        exit();
    }

    $query = "SELECT * FROM user WHERE userId = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['userId']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_destroy(); 
        header("Location: " . BASE_URL_HTML);
        exit();
    }
}

function encryptUrl($url) {
    return base64_encode($url);
}

function decryptUrl($encryptedUrl) {
    return base64_decode($encryptedUrl);
}



function getCurrentDirectory()
{
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $pathInfo = pathinfo($scriptName);
    return $pathInfo['dirname'];
}