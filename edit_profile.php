<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$nama_user = $_SESSION['user'];

// Ambil data user
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE nama = '$nama_user'"
);

$user = mysqli_fetch_assoc($query);

if (!$user) {
    die("Data user tidak ditemukan.");
}


// SIMPAN PERUBAHAN
if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama']
    );

    $email = mysqli_real_escape_string(
        $koneksi,
        $_POST['email']
    );

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
                window.location='profil.php';
              </script>";

        exit();

    } else {

        echo "<script>
                alert('Gagal mengubah profil!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profil</title>

<style>

* {
    box-sizing: border-box;
    font-family: 'Segoe UI', Arial, sans-serif;
}

body {
    margin: 0;
    background: #f3f4f6;
}

.wrapper {
    display: flex;
    min-height: 100vh;
}

.content {
    flex: 1;
    padding: 30px;
}

.profile-container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

.card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.header {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    padding: 30px 20px;
    text-align: center;
    color: white;
}

.header h2 {
    margin: 0;
    font-size: 20px;
}

.form {
    padding: 25px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-weight: 600;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 11px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

.form-group input:focus {
    border-color: #4f46e5;
}

.actions {
    display: flex;
    gap: 10px;
    padding: 0 25px 25px;
}

.btn {
    flex: 1;
    text-align: center;
    padding: 11px;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
}

.btn-primary {
    background: #4f46e5;
    color: white;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

</style>

</head>

<body>

<div class="wrapper">

    <?php include 'sidebar.php'; ?>

    <div class="content">

        <div class="profile-container">

            <div class="card">

                <div class="header">
                    <h2>Edit Profil</h2>
                </div>

                <form method="POST">

                    <div class="form">

                        <div class="form-group">

                            <label>Nama</label>

                            <input
                                type="text"
                                name="nama"
                                value="<?= htmlspecialchars($user['nama']) ?>"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label>Email</label>

                            <input
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($user['email']) ?>"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label>Password Baru</label>

                            <input
                                type="password"
                                name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password"
                            >

                        </div>

                    </div>

                    <div class="actions">

                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary"
                        >
                            Simpan Perubahan
                        </button>

                        <a
                            href="profil.php"
                            class="btn btn-secondary"
                        >
                            Batal
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>

</html>