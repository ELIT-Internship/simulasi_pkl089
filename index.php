<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Contoh validasi sederhana (silakan ganti dengan cek ke database)
    if ($username === "admin" && $password === "admin123") {
        $_SESSION["username"] = $username;
        // header("Location: dashboard.php");
        // exit;
        $error = "Login berhasil! (Ganti baris header di atas untuk redirect ke halaman lain)";
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    body {
        background-color: #f2f2f2;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-box {
        background-color: #dad3d3;
        width: 350px;
        padding: 40px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(204, 203, 203, 0.1);
        text-align: center;
        border: 1px solid #575656;
    }

    .login-box h2 {
        color: #4a4a4a;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .input-group {
        text-align: left;
        margin-bottom: 18px;
    }

    .input-group label {
        display: block;
        font-size: 14px;
        color: #6b6b6b;
        margin-bottom: 6px;
    }

    .input-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d1d1;
        border-radius: 6px;
        background-color: #fafafa;
        font-size: 14px;
        color: #333;
        outline: none;
        transition: border 0.2s;
    }

    .input-group input:focus {
        border-color: #9e9e9e;
        background-color: #ffffff;
    }

    .btn-login {
        width: 100%;
        padding: 11px;
        margin-top: 10px;
        background-color: #7d7d7d;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-login:hover {
        background-color: #616161;
    }

    .message {
        margin-top: 15px;
        font-size: 13px;
        color: #d9534f;
    }
</style>
</head>
<body>

    <div class="login-box">
        <h2>Login</h2>
        <form method="POST" action="login_process.php">
            <div class="input-group">
                <label for="email">email</label>
                <input type="text" id="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>

            <?php if ($error): ?>
                <div class="message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
    </div>

</body>
</html>
