<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "db_pkl_simulasi";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

?>