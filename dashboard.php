
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

$role = $_SESSION['role'];


// ==============================
// TOTAL PENGGUNA
// ==============================

$queryUser = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM pesanan"
);

$dataUser = mysqli_fetch_assoc($queryUser);

$totalPengguna = $dataUser['total'] ?? 0;


// ==============================
// TOTAL PESANAN
// ==============================

$queryPesanan = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM pesanan"
);

$dataPesanan = mysqli_fetch_assoc($queryPesanan);

$totalPesanan = $dataPesanan['total'] ?? 0;


// ==============================
// TOTAL PENDAPATAN
// Hanya pesanan yang selesai
// ==============================

$queryPendapatan = mysqli_query(
    $koneksi,
    "SELECT SUM(total_harga) AS total
     FROM pesanan
     WHERE status = 'Selesai'"
);

$dataPendapatan = mysqli_fetch_assoc($queryPendapatan);

$totalPendapatan = $dataPendapatan['total'] ?? 0;


// ==============================
// AKTIVITAS TERBARU
// Mengambil 5 pesanan terbaru
// ==============================

$queryAktivitas = mysqli_query(
    $koneksi,
    "SELECT id, nama_pelanggan, status, tanggal
     FROM pesanan
     ORDER BY tanggal DESC, id DESC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
        }


        /* =========================
           WRAPPER
        ========================= */

        .wrapper {
            display: flex;
            min-height: 100vh;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 230px;
            background: #222;
            color: white;
            min-height: 100vh;
            flex-shrink: 0;
        }


        .sidebar h2 {
            padding: 25px 20px;
            margin: 0;
            text-align: center;
        }


        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px 20px;
        }


        .sidebar a:hover {
            background: #444;
        }


        /* =========================
           LOGOUT
        ========================= */

        .logout {
            margin: 25px 20px;
        }


        .logout a {
            display: block;
            background: #dc3545;
            color: white;
            text-align: center;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }


        .logout a:hover {
            background: #b02a37;
        }


        /* =========================
           CONTENT
        ========================= */

        .content {
            flex: 1;
            padding: 30px;
            min-width: 0;
        }


        .content h1 {
            margin-top: 0;
        }


        /* =========================
           CARDS
        ========================= */

        .cards {
            display: flex;
            gap: 20px;
            margin-top: 25px;
            margin-bottom: 30px;
        }


        .card {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
        }


        .card h3 {
            margin-top: 0;
            color: #666;
        }


        .card h1 {
            margin-top: 10px;
        }


        /* =========================
           TABLE
        ========================= */

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
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


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 800px) {

            .cards {
                flex-direction: column;
            }


            .sidebar {
                width: 190px;
            }

        }

    </style>

</head>


<body>


<div class="wrapper">


    <!-- =========================
         SIDEBAR
    ========================= -->

    <?php include 'sidebar.php'; ?>


    <!-- =========================
         CONTENT
    ========================= -->

    <div class="content">


        <?php if ($role === 'admin'): ?>


            <!-- =========================
                 DASHBOARD ADMIN
            ========================= -->

            <h1>Dashboard Admin</h1>


            <p>

                Selamat datang,

                <strong>
                    <?= htmlspecialchars($_SESSION['user']); ?>
                </strong>

            </p>


            <!-- =========================
                 CARDS
            ========================= -->

            <div class="cards">


                <!-- TOTAL PENGGUNA -->

                <div class="card">

                    <h3>Total Pengguna</h3>

                    <h1>
                        <?= number_format($totalPengguna, 0, ',', '.'); ?>
                    </h1>

                    <small>
                        Data dari database
                    </small>

                </div>


                <!-- TOTAL PESANAN -->

                <div class="card">

                    <h3>Total Pesanan</h3>

                    <h1>
                        <?= number_format($totalPesanan, 0, ',', '.'); ?>
                    </h1>

                    <small>
                        Data dari database
                    </small>

                </div>


                <!-- TOTAL PENDAPATAN -->

                <div class="card">

                    <h3>Pendapatan</h3>

                    <h1>
                        Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?>
                    </h1>

                    <small>
                        Pesanan yang selesai
                    </small>

                </div>


            </div>


            <!-- =========================
                 AKTIVITAS TERBARU
            ========================= -->

            <h2>Aktivitas Terbaru</h2>


            <table>


                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nama</th>

                        <th>Status</th>

                        <th>Tanggal</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($queryAktivitas && mysqli_num_rows($queryAktivitas) > 0): ?>


                        <?php while ($row = mysqli_fetch_assoc($queryAktivitas)): ?>


                            <tr>

                                <td>
                                    <?= htmlspecialchars($row['id']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($row['nama_pelanggan']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($row['status']); ?>
                                </td>


                                <td>

                                    <?= date(
                                        'd M Y',
                                        strtotime($row['tanggal'])
                                    ); ?>

                                </td>

                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td colspan="4">
                                Belum ada data pesanan.
                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>


            </table>


        <?php else: ?>


            <!-- =========================
                 DASHBOARD USER
            ========================= -->

            <h1>Dashboard User</h1>


            <div class="card">


                <h2>
                    Selamat Datang 👋
                </h2>


                <p>

                    Halo,

                    <strong>
                        <?= htmlspecialchars($_SESSION['user']); ?>
                    </strong>

                </p>


                <p>

                    Email:

                    <?= htmlspecialchars($_SESSION['email'] ?? '-'); ?>

                </p>


                <p>

                    Role:

                    <strong>
                        USER
                    </strong>

                </p>


                <p>
                    Kamu berhasil login sebagai User.
                </p>


            </div>


        <?php endif; ?>


    </div>


</div>


</body>

</html>