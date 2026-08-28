<?php

session_start();
require 'koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$nama_user = $_SESSION['user'];


// ==============================
// AMBIL TRANSAKSI USER
// ==============================

$sql = "SELECT
            id,
            nama_pelanggan,
            jenis_barang,
            kuantiti,
            total_harga,
            status,
            status_pembayaran,
            tanggal
        FROM pesanan
        WHERE nama_pelanggan = ?
        ORDER BY id DESC";

$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $nama_user
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

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
            font-family: Arial, sans-serif;
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
            margin-top: 0;
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
            margin-top: 20px;
            min-width: 850px;
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

        tr:hover {
            background: #f8f8f8;
        }

        .status {
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 13px;
            display: inline-block;
        }

        .diproses {
            background: #ffc107;
            color: #222;
        }

        .selesai {
            background: #198754;
            color: white;
        }

        .dibatalkan {
            background: #dc3545;
            color: white;
        }

        .lunas {
            background: #198754;
            color: white;
        }

        .belum-lunas {
            background: #dc3545;
            color: white;
        }

        .kosong {
            text-align: center;
            padding: 30px;
            color: #777;
        }

    </style>

</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->

    <?php include 'sidebar.php'; ?>


    <!-- CONTENT -->

    <div class="content">

        <h1>Transaksi Saya</h1>

        <p>
            Berikut adalah transaksi yang kamu pesan.
        </p>


        <div class="card">

            <h2>Daftar Transaksi</h2>


            <?php if (mysqli_num_rows($result) > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Jenis Barang</th>

                            <th>Kuantiti</th>

                            <th>Total Harga</th>

                            <th>Status Pesanan</th>

                            <th>Pembayaran</th>

                            <th>Tanggal</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($transaksi = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($transaksi['id']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($transaksi['jenis_barang']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($transaksi['kuantiti']); ?>
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

                                    <?php

                                    $status = $transaksi['status'];

                                    if ($status === 'Selesai') {
                                        $class = 'selesai';
                                    } elseif ($status === 'Dibatalkan') {
                                        $class = 'dibatalkan';
                                    } else {
                                        $class = 'diproses';
                                    }

                                    ?>

                                    <span class="status <?= $class; ?>">
                                        <?= htmlspecialchars($status); ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        $transaksi['status_pembayaran']
                                        === 'Lunas'
                                    ): ?>

                                        <span class="status lunas">
                                            Lunas
                                        </span>

                                    <?php else: ?>

                                        <span class="status belum-lunas">
                                            Belum Lunas
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        $transaksi['tanggal']
                                    ); ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>


            <?php else: ?>

                <div class="kosong">

                    Kamu belum memiliki transaksi.

                </div>

            <?php endif; ?>


        </div>

    </div>

</div>

</body>

</html>