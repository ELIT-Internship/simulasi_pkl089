<?php

session_start();
require 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$nama = $_SESSION['user'];


$query = mysqli_prepare(
    $koneksi,
    "SELECT
        id,
        produk,
        jumlah,
        total_harga,
        status,
        status_pembayaran,
        tanggal
     FROM pesanan
     WHERE nama_pelanggan = ?
     ORDER BY id DESC"
);


mysqli_stmt_bind_param(
    $query,
    "s",
    $nama
);


mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Transaksi Saya</title>

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

.card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,.08);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 750px;
}

th,
td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #333;
    color: white;
}

.lunas {
    background: #198754;
    color: white;
    padding: 5px 9px;
    border-radius: 5px;
    font-size: 13px;
}

.belum {
    background: #dc3545;
    color: white;
    padding: 5px 9px;
    border-radius: 5px;
    font-size: 13px;
}

</style>

</head>


<body>

<div class="wrapper">

    <?php include 'sidebar.php'; ?>


    <div class="content">

        <h1>Transaksi Saya</h1>

        <p>
            Berikut adalah daftar pesanan Anda.
        </p>


        <div class="card">

            <h2>Daftar Transaksi</h2>


            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Barang</th>
                        <th>Kuantiti</th>
                        <th>Total Harga</th>
                        <th>Status Pesanan</th>
                        <th>Pembayaran</th>
                        <th>Tanggal</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($data = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($data['id']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['produk']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['jumlah']); ?>
                            </td>

                            <td>
                                Rp
                                <?= number_format(
                                    $data['total_harga'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['status']); ?>
                            </td>

                            <td>

                                <?php if (
                                    $data['status_pembayaran'] === 'Lunas'
                                ): ?>

                                    <span class="lunas">
                                        Lunas
                                    </span>

                                <?php else: ?>

                                    <span class="belum">
                                        Belum Lunas
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>
                                <?= htmlspecialchars($data['tanggal']); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="7" style="text-align:center;">
                            Belum ada transaksi.
                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>