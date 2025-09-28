<?php
$host = "localhost";
$user = "root";       // Sesuaikan dengan username MySQL
$pass = "";           // Sesuaikan dengan password MySQL
$db   = "kevin4_";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>