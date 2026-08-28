<?php
session_start();

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'] ?? 'user';

// ==============================
// DATA KONSUMEN (contoh)
// Nantinya bisa diganti dengan data dari database
// ==============================
$konsumen = [
    "foto"        => "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80",
    "nama"        => "rifky",
    "username"    => "@rifky.",
    "email"       => "rifky.@email.com",
    "telepon"     => "0812-3456-7890",
    "alamat"      => "Jl. Merdeka No. 12, tokyo, jepang",
    "tanggal_gabung" => "12 Januari 2023",
    "total_pesanan"  => 24,
    "total_belanja"  => 3250000,
    "poin_reward"    => 850,
    "status_member"  => "Gold Member"
];

// Fungsi format Rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ",", ".");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil Konsumen</title>

<style>

/* =========================
   RESET
========================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Arial, sans-serif;
}


/* =========================
   BODY
========================= */

body {
    background: #f3f4f6;
}


/* =========================
   LAYOUT
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
    min-width: 0;
    padding: 30px;
}


/* =========================
   PROFILE CONTAINER
========================= */

.profile-container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}


/* =========================
   PROFILE CARD
========================= */

.card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}


/* =========================
   HEADER PROFILE
========================= */

.header {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    padding: 30px 20px;
    text-align: center;
    color: #fff;
}

.header img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #fff;
    object-fit: cover;
}

.header h2 {
    margin-top: 12px;
    font-size: 20px;
}

.header p {
    font-size: 14px;
    opacity: 0.85;
    margin-top: 2px;
}

.badge {
    display: inline-block;
    margin-top: 10px;
    background: #facc15;
    color: #78350f;
    font-size: 12px;
    font-weight: bold;
    padding: 4px 12px;
    border-radius: 20px;
}


/* =========================
   STATS
========================= */

.stats {
    display: flex;
    justify-content: space-around;
    padding: 18px 10px;
    border-bottom: 1px solid #f0f0f0;
    background: white;
}

.stats div {
    text-align: center;
}

.stats .value {
    font-size: 17px;
    font-weight: bold;
    color: #1f2937;
}

.stats .label {
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
}


/* =========================
   INFORMATION
========================= */

.info {
    padding: 20px 22px;
    background: white;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    color: #6b7280;
}

.info-item .value {
    color: #111827;
    font-weight: 500;
    text-align: right;
    max-width: 60%;
}


/* =========================
   BUTTON
========================= */

.actions {
    display: flex;
    gap: 10px;
    padding: 0 22px 22px 22px;
    background: white;
}

.btn {
    flex: 1;
    text-align: center;
    padding: 10px 0;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
}

.btn-primary {
    background: #4f46e5;
    color: #fff;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 800px) {

    .sidebar {
        width: 190px;
    }

    .content {
        padding: 20px;
    }

    .profile-container {
        max-width: 100%;
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


        <div class="profile-container">


            <div class="card">


                <!-- HEADER PROFIL -->

                <div class="header">

                    <img
                        src="<?= htmlspecialchars($konsumen['foto']) ?>"
                        alt="Foto Profil"
                    >

                    <h2>
                        <?= htmlspecialchars($konsumen['nama']) ?>
                    </h2>

                    <p>
                        <?= htmlspecialchars($konsumen['username']) ?>
                    </p>

                    <span class="badge">
                        ⭐ <?= htmlspecialchars($konsumen['status_member']) ?>
                    </span>

                </div>


                <!-- STATISTIK -->

                <div class="stats">

                    <div>

                        <div class="value">
                            <?= $konsumen['total_pesanan'] ?>
                        </div>

                        <div class="label">
                            Pesanan
                        </div>

                    </div>


                    <div>

                        <div class="value">
                            <?= formatRupiah($konsumen['total_belanja']) ?>
                        </div>

                        <div class="label">
                            Total Belanja
                        </div>

                    </div>


                    <div>

                        <div class="value">
                            <?= $konsumen['poin_reward'] ?>
                        </div>

                        <div class="label">
                            Poin
                        </div>

                    </div>

                </div>


                <!-- DETAIL INFORMASI -->

                <div class="info">


                    <div class="info-item">

                        <span class="label">
                            Email
                        </span>

                        <span class="value">
                            <?= htmlspecialchars($konsumen['email']) ?>
                        </span>

                    </div>


                    <div class="info-item">

                        <span class="label">
                            Telepon
                        </span>

                        <span class="value">
                            <?= htmlspecialchars($konsumen['telepon']) ?>
                        </span>

                    </div>


                    <div class="info-item">

                        <span class="label">
                            Alamat
                        </span>

                        <span class="value">
                            <?= htmlspecialchars($konsumen['alamat']) ?>
                        </span>

                    </div>


                    <div class="info-item">

                        <span class="label">
                            Bergabung Sejak
                        </span>

                        <span class="value">
                            <?= htmlspecialchars($konsumen['tanggal_gabung']) ?>
                        </span>

                    </div>


                </div>


                <!-- TOMBOL -->

                <div class="actions">

                    <a href="#" class="btn btn-secondary">
                        Edit Profil
                    </a>

                    <a href="#" class="btn btn-primary">
                        Riwayat Pesanan
                    </a>

                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>