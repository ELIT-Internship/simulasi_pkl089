<?php

session_start();
require 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk Admin.";
    exit();
}


// Ambil ID

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID transaksi tidak valid.");
}


// Ambil data

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM pesanan WHERE id = $id"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data transaksi tidak ditemukan.");
}


// ==============================
// UPDATE
// ==============================

if (isset($_POST['update'])) {

    $nama = $_POST['nama_pelanggan'];
    $produk = $_POST['produk'];
    $jumlah = (int) $_POST['jumlah'];
    $total = (int) $_POST['total_harga'];
    $status = $_POST['status'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $tanggal = $_POST['tanggal'];


    $sql = "UPDATE pesanan SET

                nama_pelanggan = ?,
                produk = ?,
                jumlah = ?,
                total_harga = ?,
                status = ?,
                status_pembayaran = ?,
                tanggal = ?

            WHERE id = ?";


    $stmt = mysqli_prepare($koneksi, $sql);


    mysqli_stmt_bind_param(
        $stmt,
        "ssiisssi",
        $nama,
        $produk,
        $jumlah,
        $total,
        $status,
        $status_pembayaran,
        $tanggal,
        $id
    );


    if (mysqli_stmt_execute($stmt)) {

        header("Location: data_transaksi.php");
        exit();

    } else {

        $error = "Data gagal diperbarui: "
               . mysqli_error($koneksi);

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Transaksi</title>

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

.error {
    color: #dc3545;
    margin-bottom: 15px;
}

</style>

</head>


<body>


<div class="wrapper">


    <?php include 'sidebar.php'; ?>


    <div class="content">

        <h1>Edit Transaksi</h1>


        <div class="form-box">


            <?php if (isset($error)): ?>

                <div class="error">
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST">


                <div class="form-group">

                    <label>Nama Pelanggan</label>

                    <input
                        type="text"
                        name="nama_pelanggan"
                        value="<?= htmlspecialchars(
                            $data['nama_pelanggan']
                        ); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Jenis Barang</label>

                    <input
                        type="text"
                        name="produk"
                        value="<?= htmlspecialchars(
                            $data['produk']
                        ); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Kuantiti</label>

                    <input
                        type="number"
                        name="jumlah"
                        value="<?= htmlspecialchars(
                            $data['jumlah']
                        ); ?>"
                        min="1"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Total Harga</label>

                    <input
                        type="number"
                        name="total_harga"
                        value="<?= htmlspecialchars(
                            $data['total_harga']
                        ); ?>"
                        min="0"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Status Pesanan</label>

                    <select name="status">

                        <option value="Diproses"
                            <?= $data['status'] == 'Diproses'
                                ? 'selected'
                                : ''; ?>>
                            Diproses
                        </option>

                        <option value="Selesai"
                            <?= $data['status'] == 'Selesai'
                                ? 'selected'
                                : ''; ?>>
                            Selesai
                        </option>

                        <option value="Dibatalkan"
                            <?= $data['status'] == 'Dibatalkan'
                                ? 'selected'
                                : ''; ?>>
                            Dibatalkan
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Status Pembayaran</label>

                    <select name="status_pembayaran">

                        <option value="Belum Lunas"
                            <?= $data['status_pembayaran'] == 'Belum Lunas'
                                ? 'selected'
                                : ''; ?>>
                            Belum Lunas
                        </option>

                        <option value="Lunas"
                            <?= $data['status_pembayaran'] == 'Lunas'
                                ? 'selected'
                                : ''; ?>>
                            Lunas
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Tanggal</label>

                    <input
                        type="date"
                        name="tanggal"
                        value="<?= htmlspecialchars(
                            $data['tanggal']
                        ); ?>"
                        required
                    >

                </div>


                <button type="submit" name="update">
                    Simpan Perubahan
                </button>


            </form>


            <a
                href="data_transaksi.php"
                class="back"
            >
                ← Kembali ke Data Transaksi
            </a>


        </div>

    </div>

</div>

</body>

</html>