<?php
session_start();
require 'koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'] ?? 'user';

$nama_user = $_SESSION['user'];

// ==============================
// AMBIL DATA USER DARI DATABASE
// ==============================

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE nama = '$nama_user'"
);

$user = mysqli_fetch_assoc($query);

if (!$user) {
    die("Data user tidak ditemukan.");
}


// ==============================
// PROSES EDIT PROFIL
// ==============================

if (isset($_POST['simpan_profil'])) {

    $nama = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama']
    );

    $email = mysqli_real_escape_string(
        $koneksi,
        $_POST['email']
    );

    // Jika password diisi
    if (!empty($_POST['password'])) {

        $password = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );

        $update = mysqli_query(
            $koneksi,
            "UPDATE users SET
                nama = '$nama',
                email = '$email',
                password = '$password'
             WHERE id = '{$user['id']}'"
        );

    } else {

        $update = mysqli_query(
            $koneksi,
            "UPDATE users SET
                nama = '$nama',
                email = '$email'
             WHERE id = '{$user['id']}'"
        );
    }

    if ($update) {

        $_SESSION['user'] = $nama;

        echo "<script>
                alert('Profil berhasil diubah!');
                window.location='profile.php';
              </script>";

        exit();

    } else {

        echo "<script>
                alert('Profil gagal diubah!');
              </script>";
    }
}


// ==============================
// DATA PROFIL
// ==============================

$konsumen = [
    "foto" => "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=687&q=80",

    "nama" => $user['nama'],

    "username" => "@" . $user['nama'],

    "email" => $user['email'],

    "telepon" => "0812-3456-7890",

    "alamat" => "Jl. Merdeka No. 12, tokyo, jepang",

    "tanggal_gabung" => date(
        "d F Y",
        strtotime($user['created_at'])
    ),

    "total_pesanan" => 24,

    "total_belanja" => 3250000,

    "poin_reward" => 850,

    "status_member" => "Gold Member"
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

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Arial, sans-serif;
}

body {
    background: #f3f4f6;
}

.wrapper {
    display: flex;
    min-height: 100vh;
}

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

.content {
    flex: 1;
    min-width: 0;
    padding: 30px;
}

.profile-container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

.card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

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

/* FORM EDIT */

.edit-form {
    padding: 22px;
    background: white;
    border-top: 1px solid #f3f4f6;
}

.edit-form h3 {
    margin-bottom: 18px;
}

.edit-form label {
    display: block;
    margin-bottom: 6px;
    color: #374151;
    font-size: 14px;
    font-weight: 600;
}

.edit-form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.edit-form input:focus {
    outline: none;
    border-color: #4f46e5;
}

.form-buttons {
    display: flex;
    gap: 10px;
}

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

    <?php include 'sidebar.php'; ?>

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


                <!-- FORM EDIT -->

                <?php if (isset($_GET['edit'])): ?>

                <div class="edit-form">

                    <h3>
                        Edit Profil
                    </h3>

                    <form method="POST">

                        <label>
                            Nama
                        </label>

                        <input
                            type="text"
                            name="nama"
                            value="<?= htmlspecialchars($user['nama']) ?>"
                            required
                        >

                        <label>
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            required
                        >

                        <label>
                            Password Baru
                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Kosongkan jika tidak ingin mengubah password"
                        >

                        <div class="form-buttons">

                            <button
                                type="submit"
                                name="simpan_profil"
                                class="btn btn-primary"
                            >
                                Simpan Perubahan
                            </button>

                            <a
                                href="profile.php"
                                class="btn btn-secondary"
                            >
                                Batal
                            </a>

                        </div>

                    </form>

                </div>

                <?php endif; ?>


                <!-- TOMBOL -->

                <div class="actions">

                    <?php if (!isset($_GET['edit'])): ?>

                    <a
                        href="profile.php?edit=1"
                        class="btn btn-secondary"
                    >
                        Edit Profil
                    </a>

                    <?php endif; ?>

                    <a
                        href="transaksi_saya.php"
                        class="btn btn-primary"
                    >
                        Riwayat Pesanan
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>