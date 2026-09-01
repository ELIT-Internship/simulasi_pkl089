<?php

session_start();
require 'koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Hanya admin
if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk Admin.";
    exit();
}


// ==============================
// CEK ID TRANSAKSI
// ==============================

if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = (int) $_GET['id'];


// ==============================
// AMBIL DATA TRANSAKSI
// ==============================

$query = mysqli_query(
    $koneksi,
    "SELECT *
     FROM pesanan
     WHERE id = $id"
);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$transaksi = mysqli_fetch_assoc($query);

if (!$transaksi) {
    die("Data transaksi tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Struk Transaksi</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    margin: 0;
    padding: 30px;
}

.struk {
    width: 350px;
    margin: auto;
    background: white;
    padding: 25px;
    border: 1px solid #ddd;
}

.header {
    text-align: center;
    border-bottom: 1px dashed #999;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.header h2 {
    margin: 0;
}

.header p {
    margin: 5px 0;
    color: #666;
}

.data {
    margin-bottom: 10px;
}

.data strong {
    display: inline-block;
    width: 120px;
}

.total {
    border-top: 1px dashed #999;
    margin-top: 15px;
    padding-top: 15px;
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    font-size: 16px;
}

.status {
    margin-top: 15px;
    padding: 10px;
    background: #f5f5f5;
    text-align: center;
}

.tombol {
    text-align: center;
    margin-top: 20px;
}

button {
    background: #333;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #555;
}


/* Saat PDF / print */

@media print {

    body {
        background: white;
        padding: 0;
    }

    .struk {
        border: none;
        width: 100%;
    }

    .tombol {
        display: none;
    }

}

</style>

</head>

<body>


<div class="struk">


    <div class="header">

        <h2>STRUK TRANSAKSI</h2>

        <p>
            Simulasi PKL
        </p>

    </div>


    <div class="data">

        <strong>ID Transaksi:</strong>

        <?= htmlspecialchars($transaksi['id']); ?>

    </div>


    <div class="data">

        <strong>Pelanggan:</strong>

        <?= htmlspecialchars(
            $transaksi['nama_pelanggan']
        ); ?>

    </div>


    <div class="data">

        <strong>Barang:</strong>

        <?= htmlspecialchars(
            $transaksi['produk']
        ); ?>

    </div>


    <div class="data">

        <strong>Jumlah:</strong>

        <?= htmlspecialchars(
            $transaksi['jumlah']
        ); ?>

    </div>


    <div class="data">

        <strong>Tanggal:</strong>

        <?= htmlspecialchars(
            $transaksi['tanggal']
        ); ?>

    </div>


    <div class="total">

        <span>Total</span>

        <span>
            Rp <?= number_format(
                $transaksi['total_harga'],
                0,
                ',',
                '.'
            ); ?>
        </span>

    </div>


    <div class="status">

        Status Pesanan:
        <strong>
            <?= htmlspecialchars(
                $transaksi['status']
            ); ?>
        </strong>

        <br>

        Pembayaran:
        <strong>
            <?= htmlspecialchars(
                $transaksi['status_pembayaran']
            ); ?>
        </strong>

    </div>


    <div class="tombol">

        <button onclick="window.print()">
            Cetak / Simpan sebagai PDF
        </button>

    </div>


</div>


</body>

</html>