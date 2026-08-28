<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    echo "Email dan password harus diisi.";
    exit;
}

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {

    $data = mysqli_fetch_assoc($result);

    // cek password
    if (password_verify($password, $data['password'])) {

        // membuat session login
        $_SESSION['user'] = $data['nama'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['role'] = $data['role'];

        // pindah ke dashboard
        header("Location: dashboard.php");
        exit;

    } else {
        echo "
        <script>
            alert('Password salah!');
            window.location='login.php';
        </script>";
    }

} else {

    echo "
    <script>
        alert('Email tidak ditemukan!');
        window.location='login.php';
    </script>";

}

?>