<?php
session_start();
require 'koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Hanya admin
if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk Admin.";
    exit();
}

// Ambil ID pengguna dari URL
if (!isset($_GET['id'])) {
    die("ID pengguna tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil data pengguna berdasarkan ID
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE id = '$id'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pengguna tidak ditemukan.");
}

// Jika tombol Simpan ditekan
if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = mysqli_query(
        $koneksi,
        "UPDATE users SET
            nama = '$nama',
            email = '$email',
            role = '$role'
         WHERE id = '$id'"
    );

    if ($update) {
        header("Location: data_pengguna.php");
        exit();
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Pengguna</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }

        .container {
            width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #222;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .kembali {
            display: inline-block;
            margin-top: 15px;
            color: #333;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit Pengguna</h1>

    <form method="POST">

        <label>Nama</label>
        <input
            type="text"
            name="nama"
            value="<?= htmlspecialchars($data['nama']); ?>"
            required
        >

        <label>Email</label>
        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($data['email']); ?>"
            required
        >

        <label>Role</label>

        <select name="role" required>

            <option
                value="user"
                <?= $data['role'] === 'user' ? 'selected' : ''; ?>
            >
                User
            </option>

            <option
                value="admin"
                <?= $data['role'] === 'admin' ? 'selected' : ''; ?>
            >
                Admin
            </option>

        </select>

        <button type="submit" name="simpan">
            Simpan Perubahan
        </button>

    </form>

    <a href="data_pengguna.php" class="kembali">
        ← Kembali ke Data Pengguna
    </a>

</div>

</body>
</html>