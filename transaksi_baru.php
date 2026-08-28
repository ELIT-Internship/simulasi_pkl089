<?php

session_start();
require 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'] ?? 'user';

if ($role !== 'admin') {
    header("Location: dashboard.php");
    exit();
}


// ==============================
// SIMPAN TRANSAKSI
// ==============================

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama_pelanggan'];
    $produk = $_POST['produk'];
    $jumlah = $_POST['jumlah'];
    $total = $_POST['total_harga'];
    $status = $_POST['status'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $tanggal = $_POST['tanggal'];


    $query = mysqli_prepare(
        $koneksi,
        "INSERT INTO pesanan
        (nama_pelanggan, produk, jumlah, total_harga, status, status_pembayaran, tanggal)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );


    mysqli_stmt_bind_param(
        $query,
        "ssidsss",
        $nama,
        $produk,
        $jumlah,
        $total,
        $status,
        $status_pembayaran,
        $tanggal
    );


    if (mysqli_stmt_execute($query)) {

        header("Location: data_transaksi.php");
        exit();

    } else {

        $error = "Transaksi gagal disimpan.";

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Transaksi Baru</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #f5f5f5;
}

.wrapper {
    display: flex;
    min-height: 100vh;
}

.content {
    flex: 1;
    padding: 30px;
}

.form-box {
    max-width: 600px;
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,.1);
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
}

input,
select {
    width: 100%;
    padding: 11px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background: #222;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #444;
}

.back {
    display: inline-block;
    margin-top: 15px;
    color: #333;
    text-decoration: none;
}

</style>

</head>

<body>

<div class="wrapper">

    <?php include 'sidebar.php'; ?>


    <div class="content">

        <h1>Transaksi Baru</h1>

        <p>
            Masukkan data transaksi baru.
        </p>


        <div class="form-box">

            <?php if (isset($error)): ?>

                <p style="color:red;">
                    <?= htmlspecialchars($error); ?>
                </p>

            <?php endif; ?>


            <form method="POST">


                <!-- NAMA PELANGGAN -->

                <div class="form-group">

                    <label>Nama Pelanggan</label>

                    <input
                        type="text"
                        name="nama_pelanggan"
                        placeholder="Masukkan nama pelanggan"
                        required
                    >

                </div>


                <!-- JENIS BARANG -->

                <div class="form-group">

                    <label>Jenis Barang</label>

                    <input
                        type="text"
                        name="produk"
                        placeholder="Contoh: Laptop, Mouse, Keyboard"
                        required
                    >

                </div>


                <!-- KUANTITI -->

                <div class="form-group">

                    <label>Kuantiti</label>

                    <input
                        type="number"
                        name="jumlah"
                        placeholder="Masukkan jumlah barang"
                        min="1"
                        required
                    >

                </div>


                <!-- TOTAL HARGA -->

                <div class="form-group">

                    <label>Total Harga</label>

                    <input
                        type="number"
                        name="total_harga"
                        placeholder="Masukkan total harga"
                        min="0"
                        required
                    >

                </div>


                <!-- STATUS PESANAN -->

                <div class="form-group">

                    <label>Status Pesanan</label>

                    <select name="status" required>

                        <option value="Diproses">
                            Diproses
                        </option>

                        <option value="Selesai">
                            Selesai
                        </option>

                        <option value="Dibatalkan">
                            Dibatalkan
                        </option>

                    </select>

                </div>


                <!-- STATUS PEMBAYARAN -->

                <div class="form-group">

                    <label>Status Pembayaran</label>

                    <select name="status_pembayaran" required>

                        <option value="Belum Lunas">
                            Belum Lunas
                        </option>

                        <option value="Lunas">
                            Lunas
                        </option>

                    </select>

                </div>


                <!-- TANGGAL -->

                <div class="form-group">

                    <label>Tanggal</label>

                    <input
                        type="date"
                        name="tanggal"
                        value="<?= date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <button type="submit" name="simpan">
                    Simpan Transaksi
                </button>


            </form>


            <a href="data_transaksi.php" class="back">
                ← Kembali ke Data Transaksi
            </a>

        </div>

    </div>

</div>

</body>

</html>