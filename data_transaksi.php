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


// ==============================
// HAPUS TRANSAKSI
// ==============================

if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM pesanan WHERE id = $id"
    );

    header("Location: data_transaksi.php");
    exit();
}


// ==============================
// AMBIL DATA
// ==============================

$sql = "SELECT
            id,
            nama_pelanggan,
            produk,
            jumlah,
            total_harga,
            status,
            status_pembayaran,
            tanggal
        FROM pesanan
        ORDER BY id DESC";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Transaksi</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #f5f5f5;
    color: #222;
}

.wrapper {
    display: flex;
    min-height: 100vh;
}

.content {
    flex: 1;
    padding: 30px;
    min-width: 0;
}

.content h1 {
    margin: 0 0 8px;
}

.content p {
    margin-top: 0;
    color: #666;
}


/* =========================
   CARD
========================= */

.card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,.08);
    overflow-x: auto;
}


/* =========================
   BUTTON TAMBAH
========================= */

.btn-tambah {
    display: inline-block;
    background: #222;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 20px;
}

.btn-tambah:hover {
    background: #444;
}


/* =========================
   TABLE
========================= */

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

th,
td {
    padding: 12px 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #333;
    color: white;
}

tr:hover {
    background: #f8f8f8;
}


/* =========================
   STATUS
========================= */

.status-lunas {
    background: #198754;
    color: white;
    padding: 5px 9px;
    border-radius: 5px;
    font-size: 13px;
}

.status-belum {
    background: #dc3545;
    color: white;
    padding: 5px 9px;
    border-radius: 5px;
    font-size: 13px;
}


/* =========================
   AKSI
========================= */

.aksi {
    white-space: nowrap;
}

.btn-edit {
    background: #198754;
    color: white;
    padding: 7px 10px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 13px;
}

.btn-hapus {
    background: #dc3545;
    color: white;
    padding: 7px 10px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 13px;
}

.btn-edit:hover {
    background: #157347;
}

.btn-hapus:hover {
    background: #b02a37;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 800px) {

    .content {
        padding: 20px;
    }

}

</style>

</head>


<body>


<div class="wrapper">


    <?php include 'sidebar.php'; ?>


    <div class="content">

        <h1>Data Transaksi</h1>

        <p>
            Selamat datang,
            <strong>
                <?= htmlspecialchars($_SESSION['user']); ?>
            </strong>
        </p>


        <div class="card">

            <h2>Daftar Transaksi</h2>


            <a
                href="transaksi_baru.php"
                class="btn-tambah"
            >
                + Transaksi Baru
            </a>


            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nama Pelanggan</th>

                        <th>Barang</th>

                        <th>Jumlah</th>

                        <th>Total</th>

                        <th>Status Pesanan</th>

                        <th>Pembayaran</th>

                        <th>Tanggal</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($transaksi = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($transaksi['id']); ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $transaksi['nama_pelanggan']
                            ); ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $transaksi['produk']
                            ); ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $transaksi['jumlah']
                            ); ?>
                        </td>


                        <td>
                            Rp
                            <?= number_format(
                                $transaksi['total_harga'],
                                0,
                                ',',
                                '.'
                            ); ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $transaksi['status']
                            ); ?>
                        </td>


                        <td>

                            <?php if (
                                $transaksi['status_pembayaran']
                                === 'Lunas'
                            ): ?>

                                <span class="status-lunas">
                                    Lunas
                                </span>

                            <?php else: ?>

                                <span class="status-belum">
                                    Belum Lunas
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $transaksi['tanggal']
                            ); ?>
                        </td>


                        <td class="aksi">

                            <a
                                href="edit_transaksi.php?id=<?= $transaksi['id']; ?>"
                                class="btn-edit"
                            >
                                Edit
                            </a>

                            <a
                                href="data_transaksi.php?hapus=<?= $transaksi['id']; ?>"
                                class="btn-hapus"
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?');"
                            >
                                Hapus
                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>