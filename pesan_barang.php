<?php

session_start();
require 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}


// ==============================
// AMBIL DATA BARANG
// ==============================

$barang = mysqli_query(
    $koneksi,
    "SELECT id_barang, nama_barang, harga
     FROM barang
     WHERE nama_barang IN ('Laptop', 'Mouse', 'Keyboard')
     ORDER BY id_barang ASC"
);


// ==============================
// SIMPAN PESANAN
// ==============================

if (isset($_POST['pesan'])) {

    $nama = $_SESSION['user'];
    $id_barang = (int) $_POST['id_barang'];
    $kuantiti = (int) $_POST['kuantiti'];
    $tanggal = date('Y-m-d');

    // Ambil harga berdasarkan barang yang dipilih
    $query_barang = mysqli_prepare(
        $koneksi,
        "SELECT nama_barang, harga
         FROM barang
         WHERE id_barang = ?
         AND nama_barang IN ('Laptop', 'Mouse', 'Keyboard')"
    );

    mysqli_stmt_bind_param(
        $query_barang,
        "i",
        $id_barang
    );

    mysqli_stmt_execute($query_barang);

    $hasil_barang = mysqli_stmt_get_result($query_barang);

    if (mysqli_num_rows($hasil_barang) === 0) {

        $error = "Barang tidak ditemukan.";

    } else {

        $data_barang = mysqli_fetch_assoc($hasil_barang);

        $jenis_barang = $data_barang['nama_barang'];
        $harga = (int) $data_barang['harga'];

        // Hitung total otomatis
        $total_harga = $harga * $kuantiti;

        $produk = $jenis_barang;
        $jumlah = $kuantiti;

        $status = "Diproses";
        $status_pembayaran = "Belum Lunas";


        // Simpan ke tabel pesanan
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

            header("Location: transaksi_saya.php");
            exit();

        } else {

            $error = "Pesanan gagal disimpan: "
                   . mysqli_error($koneksi);

        }

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

        select,
        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[readonly] {
            background: #eee;
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

    <?php include 'sidebar.php'; ?>

    <div class="content">

        <h1>Pesan Barang</h1>

        <p>
            Silakan pilih barang dan jumlah yang ingin dipesan.
        </p>


        <div class="card">

            <?php if (isset($error)): ?>

                <div class="error">
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST">


                <!-- BARANG -->

                <div class="form-group">

                    <label>Jenis Barang</label>

                    <select
                        name="id_barang"
                        id="id_barang"
                        onchange="hitungTotal()"
                        required
                    >

                        <option value="">
                            -- Pilih Barang --
                        </option>

                        <?php while ($row = mysqli_fetch_assoc($barang)): ?>

                            <option
                                value="<?= $row['id_barang']; ?>"
                                data-harga="<?= $row['harga']; ?>"
                            >
                                <?= htmlspecialchars($row['nama_barang']); ?>
                                - Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- KUANTITI -->

                <div class="form-group">

                    <label>Kuantiti</label>

                    <input
                        type="number"
                        name="kuantiti"
                        id="kuantiti"
                        min="1"
                        value="1"
                        onchange="hitungTotal()"
                        oninput="hitungTotal()"
                        required
                    >

                </div>


                <!-- TOTAL HARGA -->

                <div class="form-group">

                    <label>Total Harga</label>

                    <input
                        type="text"
                        id="total_tampilan"
                        value="Rp 0"
                        readonly
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


<script>

function hitungTotal() {

    const barang =
        document.getElementById("id_barang");

    const jumlah =
        document.getElementById("kuantiti");

    const total =
        document.getElementById("total_tampilan");


    const option =
        barang.options[barang.selectedIndex];


    const harga =
        parseInt(option.dataset.harga || 0);


    const kuantiti =
        parseInt(jumlah.value || 0);


    const hasil =
        harga * kuantiti;


    total.value =
        "Rp " + hasil.toLocaleString("id-ID");

}

</script>

</body>

</html>