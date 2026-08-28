<?php
session_start();
require 'koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Hanya Admin yang boleh masuk
if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk Admin.";
    exit();
}

// Ambil data user dari database
$sql = "SELECT id, nama, email, role, created_at FROM users";
$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data User</title>


<style>

/* =========================
   RESET
========================= */

* {
    box-sizing: border-box;
}


/* =========================
   BODY
========================= */

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
   TABLE
========================= */

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


/* =========================
   ROLE
========================= */

.role-admin {
    color: #b91c1c;
    font-weight: bold;
}

.role-user {
    color: #2563eb;
    font-weight: bold;
}


/* =========================
   CARD
========================= */

.card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 800px) {

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


        <h1>Data User</h1>


        <p>
            Selamat datang,
            <strong>
                <?= htmlspecialchars($_SESSION['user']); ?>
            </strong>
        </p>


        <div class="card">


            <h2>Daftar User</h2>


            <table>


                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nama</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Dibuat</th>

                    </tr>

                </thead>


                <tbody>


                    <?php while ($user = mysqli_fetch_assoc($result)): ?>


                        <tr>

                            <td>
                                <?= htmlspecialchars($user['id']); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($user['nama']); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($user['email']); ?>
                            </td>


                            <td>

                                <?php if ($user['role'] === 'admin'): ?>

                                    <span class="role-admin">
                                        ADMIN
                                    </span>

                                <?php else: ?>

                                    <span class="role-user">
                                        USER
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>
                                <?= htmlspecialchars($user['created_at']); ?>
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