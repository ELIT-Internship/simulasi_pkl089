<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? 'user';

?>

<style>

.sidebar {
    width: 240px;
    min-width: 240px;
    min-height: 100vh;
    background: #222;
    color: white;
    padding: 25px 15px;
    flex-shrink: 0;
}

.sidebar h2 {
    text-align: center;
    margin: 0 0 25px;
    font-size: 22px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 13px 15px;
    margin-bottom: 5px;
    border-radius: 6px;
    font-size: 15px;
}

.sidebar a:hover {
    background: #444;
}

/* SUBMENU TRANSAKSI */
.menu-transaksi {
    margin-bottom: 5px;
}

.menu-transaksi > a {
    display: flex;
    justify-content: space-between;
}

.submenu {
    display: none;
    margin-left: 10px;
}

.submenu a {
    color: #ddd;
    font-size: 14px;
    padding: 10px 15px;
}

.menu-transaksi:hover .submenu {
    display: block;
}

/* LOGOUT */
.logout {
    margin-top: 20px;
}

.logout a {
    background: #dc3545;
    text-align: center;
}

.logout a:hover {
    background: #b02a37;
}

</style>


<div class="sidebar">

    <h2>
        <?= strtoupper(htmlspecialchars($role)); ?>
    </h2>


    <!-- DASHBOARD -->
    <a href="dashboard.php">
        Dashboard
    </a>


    <?php if ($role === 'admin'): ?>

        <!-- ================= ADMIN ================= -->

        <a href="data_pengguna.php">
            Data User
        </a>

        <a href="data_pesanan.php">
            Data Pesanan
        </a>


        <!-- TRANSAKSI ADMIN -->
        <div class="menu-transaksi">

            <a href="javascript:void(0);">
                <span>Transaksi</span>
                <span></span>
            </a>

            <div class="submenu">

                <a href="transaksi_baru.php">
                    Transaksi Baru
                </a>

                <a href="data_transaksi.php">
                    Data Transaksi
                </a>

            </div>

        </div>


        <a href="laporan.php">
            Laporan
        </a>


    <?php else: ?>

        <!-- ================= USER ================= -->

        <a href="pesan_barang.php">
            Pesan Barang
        </a>

        <a href="transaksi_saya.php">
            Transaksi Saya
        </a>


    <?php endif; ?>


    <!-- PROFILE -->
    <a href="profile.php">
        Profile
    </a>


    <!-- LOGOUT -->
    <div class="logout">

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>