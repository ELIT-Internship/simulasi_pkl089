<?php

session_start();
require 'koneksi.php';

// ==============================
// CEK LOGIN
// ==============================
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// ==============================
// CEK ROLE ADMIN
// ==============================
if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk Admin.";
    exit();
}

// ==============================
// CEK ID
// ==============================
if (!isset($_GET['id'])) {
    die("ID pengguna tidak ditemukan.");
}

$id = (int) $_GET['id'];

// ==============================
// HAPUS DATA
// ==============================
$query = mysqli_query(
    $koneksi,
    "DELETE FROM users WHERE id = $id"
);

if ($query) {

    header("Location: data_pengguna.php");
    exit();

} else {

    echo "Gagal menghapus data: "
        . mysqli_error($koneksi);
}

?>