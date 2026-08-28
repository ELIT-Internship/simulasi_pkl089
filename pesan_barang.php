<?php

session_start();
require 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// ==============================
// SIMPAN PESANAN
// ==============================

if (isset($_POST['pesan'])) {

    $nama = $_SESSION['user'];
    $jenis_barang = $_POST['jenis_barang'];
    $kuantiti = (int) $_POST['kuantiti'];
    $total_harga = (int) $_POST['total_harga'];
    $tanggal = date('Y-m-d');

    // Karena tabel kamu masih memiliki kolom produk dan jumlah,
    // kita isi juga dengan data yang sama.
    $produk = $jenis_barang;
    $jumlah = $kuantiti;

    $status = "Diproses";
    $status_pembayaran = "Belum Lunas";


    $sql = "INSERT INTO pesanan
            (
                nama_pelanggan,
                jenis_barang,
                kuantiti,
                produk,
                jumlah,
                total_harga,
                status,
                status_pembayaran,
                tanggal
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = mysqli_prepare($koneksi, $sql);

    if (!$stmt) {
        die("Prepare gagal: " . mysqli_error($koneksi));
    }


    mysqli_stmt_bind_param(
        $stmt,
        "ssisisiss",
        $nama,
        $jenis_barang,
        $kuantiti,
        $produk,
        $jumlah,
        $total_harga,
        $status,
        $status_pembayaran,
        $tanggal
    );


    if (mysqli_stmt_execute($stmt)) {

        // Setelah berhasil, masuk ke Transaksi Saya
        header("Location: transaksi_saya.php");
        exit();

    } else {

        $error = "Pesanan gagal disimpan: "
               . mysqli_error($koneksi);

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesan Barang</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }


        .wrapper {
            display: flex;
            min-height: 100vh;
        }


        .content {
            flex: 1;
            padding: 30px;
        }


        .content h1 {
            margin-top: 0;
        }


        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 2px 5px rgba(0,0,0,.08);
        }


        .form-group {
            margin-bottom: 18px;
        }


        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }


        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }


        button {
            background: #222;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
        }


        button:hover {
            background: #444;
        }


        .error {
            background: #f8d7da;
            color: #842029;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

    </style>

</head>


<body>


<div class="wrapper">


    <!-- SIDEBAR -->

    <?php include 'sidebar.php'; ?>


    <!-- CONTENT -->

    <div class="content">


        <h1>Pesan Barang</h1>


        <p>
            Silakan isi barang yang ingin dipesan.
        </p>


        <div class="card">


            <?php if (isset($error)): ?>

                <div class="error">
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST">


                <!-- JENIS BARANG -->

                <div class="form-group">

                    <label>
                        Jenis Barang
                    </label>

                    <input
                        type="text"
                        name="jenis_barang"
                        placeholder="Contoh: Laptop"
                        required
                    >

                </div>


                <!-- KUANTITI -->

                <div class="form-group">

                    <label>
                        Kuantiti
                    </label>

                    <input
                        type="number"
                        name="kuantiti"
                        min="1"
                        placeholder="Jumlah barang"
                        required
                    >

                </div>


                <!-- TOTAL HARGA -->

                <div class="form-group">

                    <label>
                        Total Harga
                    </label>

                    <input
                        type=""
                        name="total_harga"
                        min="0"
                        placeholder="Contoh: 5000000"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="pesan"
                >
                    Pesan Barang
                </button>


            </form>


        </div>


    </div>


</div>


</body>

</html>