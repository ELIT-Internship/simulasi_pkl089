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


// ==============================
// AMBIL DATA LAPORAN
// ==============================

$queryPesanan = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM pesanan"
);

$dataPesanan = mysqli_fetch_assoc($queryPesanan);


$querySelesai = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM pesanan
     WHERE status = 'Selesai'"
);

$dataSelesai = mysqli_fetch_assoc($querySelesai);


$queryDiproses = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM pesanan
     WHERE status = 'Diproses'"
);

$dataDiproses = mysqli_fetch_assoc($queryDiproses);


$queryPendapatan = mysqli_query(
    $koneksi,
    "SELECT SUM(total_harga) AS total
     FROM pesanan
     WHERE status = 'Selesai'"
);

$dataPendapatan = mysqli_fetch_assoc($queryPendapatan);


// ==============================
// DATA KURS
// ==============================

$kursUSD = 16000;

$totalRupiah = $dataPendapatan['total'] ?? 0;

$totalUSD = $totalRupiah / $kursUSD;


// ==============================
// TAMPILKAN LAPORAN
// ==============================

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Laporan</title>

<style>

body {
    font-family: Arial, sans-serif;
    margin: 40px;
}

h1 {
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

th,
td {
    border: 1px solid black;
    padding: 10px;
}

th {
    background: #ddd;
}

.info {
    margin-top: 30px;
}

@media print {

    .print-button {
        display: none;
    }

}

.print-button {
    margin-top: 30px;
    padding: 10px 20px;
    background: #333;
    color: white;
    border: none;
    cursor: pointer;
}

</style>

</head>

<body>


<h1>LAPORAN TRANSAKSI</h1>


<p>
Tanggal laporan:
<?= date('d-m-Y'); ?>
</p>


<table>

<tr>
    <th>Keterangan</th>
    <th>Jumlah</th>
</tr>

<tr>
    <td>Total Pesanan</td>
    <td>
        <?= $dataPesanan['total']; ?>
    </td>
</tr>

<tr>
    <td>Pesanan Selesai</td>
    <td>
        <?= $dataSelesai['total']; ?>
    </td>
</tr>

<tr>
    <td>Pesanan Diproses</td>
    <td>
        <?= $dataDiproses['total']; ?>
    </td>
</tr>

<tr>
    <td>Total Pendapatan</td>
    <td>
        Rp <?= number_format(
            $totalRupiah,
            0,
            ',',
            '.'
        ); ?>
    </td>
</tr>

</table>


<div class="info">

<h3>Kurs Mata Uang</h3>

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

</div>


<button
    class="print-button"
    onclick="window.print()"
>
    Cetak / Simpan sebagai PDF
</button>


</body>

</html>