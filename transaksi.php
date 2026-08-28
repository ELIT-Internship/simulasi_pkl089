<?php

session_start();
require 'koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Ambil role
$role = $_SESSION['role'] ?? 'user';

// Hanya admin yang boleh masuk
if ($role !== 'admin') {
    header("Location: dashboard.php");
    exit();
}


// ==============================
// TOTAL TRANSAKSI
// ==============================
$queryTotal = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM pesanan"
);

$dataTotal = mysqli_fetch_assoc($queryTotal);
$totalTransaksi = $dataTotal['total'] ?? 0;


// ==============================
// TRANSAKSI SELESAI
// ==============================
$querySelesai = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM pesanan
     WHERE status = 'Selesai'"
);

$dataSelesai = mysqli_fetch_assoc($querySelesai);
$totalSelesai = $dataSelesai['total'] ?? 0;


// ==============================
// TRANSAKSI DIPROSES
// ==============================
$queryProses = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM pesanan
     WHERE status != 'Selesai'"
);

$dataProses = mysqli_fetch_assoc($queryProses);
$totalProses = $dataProses['total'] ?? 0;

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

        /* =========================
           LAYOUT
        ========================= */

        .wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .wrapper .sidebar {
            .sidebar { width: 240px; min-width: 240px; min-height: 100vh; background: #222; color: white; padding: 25px 15px; flex-shrink: 0; } .sidebar h2 { text-align: center; margin: 0 0 30px; font-size: 22px; color: white; } .sidebar a { display: block; color: white; text-decoration: none; padding: 13px 15px; margin-bottom: 8px; border-radius: 6px; transition: 0.2s; font-size: 15px; } .sidebar a:hover { background: #444; }
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            flex: 1;
            padding: 35px;
            min-width: 0;
        }

        .content h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .description {
            margin: 0;
            color: #666;
            font-size: 15px;
        }

        /* =========================
           CARDS
        ========================= */

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .card h3 {
            margin: 0 0 15px;
            font-size: 16px;
            font-weight: normal;
            color: #666;
        }

        .card h2 {
            margin: 0;
            font-size: 32px;
            color: #222;
        }

        /* =========================
           AKSI
        ========================= */

        .actions {
            margin-top: 30px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .actions h2 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .actions p {
            margin: 0 0 20px;
            color: #666;
        }

        /* =========================
           BUTTON
        ========================= */

        .btn {
            display: inline-block;
            padding: 12px 18px;
            background: #222;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-right: 8px;
            transition: 0.2s;
        }

        .btn:hover {
            background: #444;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .cards {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 25px;
            }

        }

        @media (max-width: 600px) {

            .wrapper {
                flex-direction: column;
            }

            .wrapper .sidebar {
                width: 100%;
                min-width: 100%;
                min-height: auto;
            }

            .content {
                padding: 20px;
            }

            .btn {
                display: block;
                margin: 10px 0;
                text-align: center;
            }

        }

    </style>

</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>


    <!-- CONTENT -->
    <main class="content">

        <h1>Data Transaksi</h1>

        <p class="description">
            Kelola dan lihat informasi transaksi pada sistem PKL Simulasi.
        </p>


        <!-- =========================
             RINGKASAN TRANSAKSI
        ========================== -->

        <div class="cards">

            <div class="card">

                <h3>Total Transaksi</h3>

                <h2>
                    <?= number_format($totalTransaksi, 0, ',', '.'); ?>
                </h2>

            </div>


            <div class="card">

                <h3>Transaksi Selesai</h3>

                <h2>
                    <?= number_format($totalSelesai, 0, ',', '.'); ?>
                </h2>

            </div>


            <div class="card">

                <h3>Transaksi Diproses</h3>

                <h2>
                    <?= number_format($totalProses, 0, ',', '.'); ?>
                </h2>

            </div>

        </div>


        <!-- =========================
             AKSI TRANSAKSI
        ========================== -->

        <div class="actions">

            <h2>Aksi Transaksi</h2>

            <p>
                Pilih menu yang ingin digunakan.
            </p>

            <a href="transaksi_baru.php" class="btn">
                + Transaksi Baru
            </a>

            <a href="data_transaksi.php" class="btn">
                Data Transaksi
            </a>

        </div>

    </main>

</div>

</body>

</html>