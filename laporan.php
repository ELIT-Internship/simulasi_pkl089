
<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk Admin.";
    exit();
}


// ==============================
// UPDATE KURS
// ==============================

if (isset($_POST['update_kurs'])) {

    $kursBaru = (int) $_POST['kursUSD'];

    if ($kursBaru > 0) {
        $_SESSION['kursUSD'] = $kursBaru;
    }
}


// Kurs default
$kursUSD = $_SESSION['kursUSD'] ?? 16000;


// ==============================
// DATA LAPORAN
// ==============================

// Jumlah pesanan
$queryPesanan = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM pesanan"
);

$dataPesanan = mysqli_fetch_assoc($queryPesanan);


// Pesanan selesai
$querySelesai = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM pesanan
     WHERE status = 'Selesai'"
);

$dataSelesai = mysqli_fetch_assoc($querySelesai);


// Pesanan diproses
$queryDiproses = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM pesanan
     WHERE status = 'Diproses'"
);

$dataDiproses = mysqli_fetch_assoc($queryDiproses);


// Total pendapatan
$queryPendapatan = mysqli_query(
    $koneksi,
    "SELECT SUM(total_harga) AS total
     FROM pesanan
     WHERE status = 'Selesai'"
);

$dataPendapatan = mysqli_fetch_assoc($queryPendapatan);


// ==============================
// PERHITUNGAN KURS
// ==============================

$totalRupiah = $dataPendapatan['total'] ?? 0;

// Konversi Rupiah ke USD
$totalUSD = $totalRupiah / $kursUSD;

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #f5f5f5;
}

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

.content h1 {
    margin-top: 0;
}


/* =========================
   CARDS
========================= */

.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 25px;
}

.card {
    flex: 1;
    min-width: 200px;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,.1);
}

.card h3 {
    color: #666;
    margin-top: 0;
}

.card h1 {
    margin: 0;
}


/* =========================
   KURS
========================= */

.kurs {
    margin-top: 25px;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,.1);
}

.kurs h2 {
    margin-top: 0;
}

.kurs form {
    margin-top: 15px;
    margin-bottom: 20px;
}

.kurs label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.kurs input {
    width: 200px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-right: 10px;
}

.kurs button {
    padding: 10px 15px;
    border: none;
    background: #222;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

.kurs button:hover {
    background: #444;
}


/* =========================
   BUTTON PDF
========================= */

.btn-pdf {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    background: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.btn-pdf:hover {
    background: #b02a37;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 800px) {

    .sidebar {
        width: 190px;
    }

    .cards {
        flex-direction: column;
    }

}

</style>

</head>


<body>

<div class="wrapper">


    <!-- SIDEBAR -->

    <?php include 'sidebar.php'; ?>


    <!-- CONTENT -->

    <div class="content">

        <h1>Laporan</h1>

        <p>
            Laporan data pesanan berdasarkan database.
        </p>


        <!-- =========================
             CARDS
        ========================= -->

        <div class="cards">


            <div class="card">

                <h3>
                    Total Pesanan
                </h3>

                <h1>
                    <?= $dataPesanan['total']; ?>
                </h1>

            </div>


            <div class="card">

                <h3>
                    Pesanan Selesai
                </h3>

                <h1>
                    <?= $dataSelesai['total']; ?>
                </h1>

            </div>


            <div class="card">

                <h3>
                    Pesanan Diproses
                </h3>

                <h1>
                    <?= $dataDiproses['total']; ?>
                </h1>

            </div>


            <div class="card">

                <h3>
                    Total Pendapatan
                </h3>

                <h1>
                    Rp
                    <?= number_format(
                        $totalRupiah,
                        0,
                        ',',
                        '.'
                    ); ?>
                </h1>

            </div>


        </div>


        <!-- =========================
             KURS
        ========================= -->

        <div class="kurs">

            <h2>Kurs Mata Uang</h2>

            <p>
                1 USD =
                <strong>
                    Rp <?= number_format(
                        $kursUSD,
                        0,
                        ',',
                        '.'
                    ); ?>
                </strong>
            </p>


            <!-- FORM UPDATE KURS -->

            <form method="POST">

                <label>
                    Ubah Kurs USD:
                </label>

                <input
                    type="number"
                    name="kursUSD"
                    value="<?= $kursUSD; ?>"
                    min="1"
                    required
                >

                <button
                    type="submit"
                    name="update_kurs"
                >
                    Update Kurs
                </button>

            </form>


            <p>
                Total Pendapatan dalam Rupiah:
                <strong>
                    Rp <?= number_format(
                        $totalRupiah,
                        0,
                        ',',
                        '.'
                    ); ?>
                </strong>
            </p>


            <p>
                Total Pendapatan dalam USD:
                <strong>
                    $ <?= number_format(
                        $totalUSD,
                        2,
                        ',',
                        '.'
                    ); ?>
                </strong>
            </p>


            <!-- EXPORT PDF -->

            <a
                href="export_pdf.php"
                class="btn-pdf"
            >
                Export PDF
            </a>

        </div>

    </div>

</div>

</body>

</html>

