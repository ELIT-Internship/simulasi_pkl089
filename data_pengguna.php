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
// AMBIL DATA PENGGUNA
// ==============================

$queryUser = mysqli_query(
    $koneksi,
    "SELECT id, nama, email, role, created_at
     FROM users
     ORDER BY id DESC"
);

if (!$queryUser) {
    die("Query gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pengguna</title>

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
           CONTAINER
        ========================= */

        .container {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
        }

        /* =========================
           HEADER DATA
        ========================= */

        .header-data {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-data h1 {
            margin: 0;
        }

        /* =========================
           TOMBOL TAMBAH
        ========================= */

        .btn-tambah {
            display: inline-block;
            background: #222;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
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
           TOMBOL EDIT
        ========================= */

        .btn-edit {
            display: inline-block;
            background: #333;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }

        .btn-edit:hover {
            background: #555;
        }

        /* =========================
           TOMBOL HAPUS
        ========================= */

        .btn-hapus {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-hapus:hover {
            background: #b02a37;
        }

        .aksi {
            white-space: nowrap;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 800px) {

            .sidebar {
                width: 190px;
            }

            .header-data {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            table {
                font-size: 14px;
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

        <div class="container">

            <!-- =========================
                 JUDUL + TAMBAH
            ========================= -->

            <div class="header-data">

                <h1>
                    Data Pengguna
                </h1>

                <a
                    href="tambah_pengguna.php"
                    class="btn-tambah"
                >
                    + Tambah Pengguna
                </a>

            </div>


            <!-- =========================
                 TABEL PENGGUNA
            ========================= -->

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nama</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Dibuat</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (mysqli_num_rows($queryUser) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($queryUser)): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($row['id']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['email']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['role']); ?>
                                </td>

                                <td>

                                    <?= date(
                                        'd M Y',
                                        strtotime($row['created_at'])
                                    ); ?>

                                </td>

                                <td class="aksi">

                                    <!-- EDIT -->

                                    <a
                                        href="edit_pengguna.php?id=<?= $row['id']; ?>"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>


                                    <!-- HAPUS -->

                                    <a
                                        href="hapus_pengguna.php?id=<?= $row['id']; ?>"
                                        class="btn-hapus"
                                        onclick="return confirm('Apakah kamu yakin ingin menghapus pengguna ini?');"
                                    >
                                        Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6" style="text-align: center;">
                                Belum ada data pengguna.
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