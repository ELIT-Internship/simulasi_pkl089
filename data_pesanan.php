<?php

session_start();
require 'koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Hanya Admin
if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk Admin.";
    exit();
}


// ==============================
// HAPUS DATA
// ==============================

if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    $query = mysqli_prepare(
        $koneksi,
        "DELETE FROM pesanan WHERE id = ?"
    );

    mysqli_stmt_bind_param($query, "i", $id);

    if (mysqli_stmt_execute($query)) {

        header("Location: data_pesanan.php");
        exit();

    } else {

        echo "Data gagal dihapus.";

    }
}


// ==============================
// AMBIL DATA PESANAN
// ==============================

$sql = "SELECT * FROM pesanan ORDER BY id DESC";

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

<title>Data Pesanan</title>

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
    min-width: 0;
}

.content h1 {
    margin-top: 0;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
}

.btn-tambah {
    display: inline-block;
    padding: 10px 15px;
    background: #222;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 10px;
}

.btn-tambah:hover {
    background: #444;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-top: 20px;
}

th,
td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #333;
    color: white;
}

.aksi {
    white-space: nowrap;
}

.btn-edit {
    display: inline-block;
    padding: 7px 10px;
    background: #222;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.btn-edit:hover {
    background: #444;
}

.btn-hapus {
    display: inline-block;
    padding: 7px 10px;
    background: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.btn-hapus:hover {
    background: #b02a37;
}

@media (max-width: 800px) {

    .content {
        padding: 20px;
    }

    table {
        font-size: 13px;
    }

}

</style>

</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->

    <?php include 'sidebar.php'; ?>


    <!-- CONTENT -->

    <div class="content">

        <h1>Data Pesanan</h1>

        <p>
            Selamat datang,
            <strong>
                <?= htmlspecialchars($_SESSION['user']); ?>
            </strong>
        </p>


        <div class="card">

            <h2>Daftar Pesanan</h2>


            <!-- TOMBOL TAMBAH -->

            <a href="transaksi_baru.php" class="btn-tambah">
                + Tambah Pesanan
            </a>


            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nama Pelanggan</th>

                        <th>Jenis Barang</th>

                        <th>Kuantiti</th>

                        <th>Total Harga</th>

                        <th>Status</th>

                        <th>Tanggal</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($pesanan = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($pesanan['id']); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($pesanan['nama_pelanggan']); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($pesanan['produk']); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($pesanan['jumlah']); ?>
                            </td>


                            <td>
                                Rp
                                <?= number_format(
                                    $pesanan['total_harga'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($pesanan['status']); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($pesanan['tanggal']); ?>
                            </td>


                            <td class="aksi">

                                <a
                                    href="edit_pesanan.php?id=<?= $pesanan['id']; ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>


                                <a
                                    href="data_pesanan.php?hapus=<?= $pesanan['id']; ?>"
                                    class="btn-hapus"
                                    onclick="return confirm('Yakin ingin menghapus pesanan ini?');"
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