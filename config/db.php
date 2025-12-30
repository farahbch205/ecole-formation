<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "ecole_formation";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات");
}
?>
