<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/db.php";


// ===== HITUNG TOTAL PEMASUKAN (iuran_kas) =====
$qPemasukan = mysqli_query($conn, "
    SELECT 
        SUM(jan + feb + mar + apr + mei + jun + jul + ags + sep + okt + nov + des) AS total
    FROM iuran_kas
");
$dataPemasukan = mysqli_fetch_assoc($qPemasukan);

$nilaiPerBulan = 10000;

$totalPemasukan = ($dataPemasukan['total'] ?? 0) * $nilaiPerBulan;


// ===== HITUNG TOTAL PENGELUARAN =====
$qPengeluaran = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran");
$dataPengeluaran = mysqli_fetch_assoc($qPengeluaran);

$totalPengeluaran = $dataPengeluaran['total'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard CashIt</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="sidebar">
    <h2>CashIt</h2>

    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="mahasiswa/index.php">Kelola Mahasiswa</a>
    <a href="iuran/index.php">Status Iuran</a>
    <a href="pengeluaran/index.php">Pengeluaran</a>

    <a href="logout.php" class="logout">Logout</a>
</div>



<!-- CONTENT -->
<div class="content">

    <h1>Dashboard</h1>

    <div class="card-container">

        <!-- CARD PEMASUKAN -->
        <div class="card-box">
            <div class="card-title">Total Pemasukan</div>
            <div class="card-value">
                Rp <?= number_format($totalPemasukan, 0, ',', '.'); ?>
            </div>
        </div>

        <!-- CARD PENGELUARAN -->
        <div class="card-box">
            <div class="card-title">Total Pengeluaran</div>
            <div class="card-value">
                Rp <?= number_format($totalPengeluaran, 0, ',', '.'); ?>
            </div>
        </div>

    </div>

</div>

</body>
</html>
