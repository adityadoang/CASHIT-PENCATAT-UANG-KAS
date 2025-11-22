<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

// FILTER TAHUN
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date("Y");

// AMBIL DATA
$sql = "SELECT * FROM pengeluaran 
        WHERE YEAR(tanggal) = '$tahun'
        ORDER BY tanggal DESC";
$result = mysqli_query($conn, $sql);

// TOTAL PENGELUARAN
$q_total = mysqli_query($conn, 
    "SELECT SUM(jumlah) AS total FROM pengeluaran WHERE YEAR(tanggal) = '$tahun'"
);
$row_total = mysqli_fetch_assoc($q_total);
$total_pengeluaran = $row_total['total'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Pengeluaran - CashIt</title>

    <!-- CSS UTAMA -->
    <link rel="stylesheet" href="../../assets/style.css">

    <!-- CSS HALAMAN INI -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <div class="sidebar">
    <h2>CashIt</h2>

    <a href="../dashboard.php">Dashboard</a>
    <a href="../mahasiswa/index.php">Kelola Mahasiswa</a>
    <a href="../iuran/index.php">Status Iuran</a>
    <a href="../pengeluaran/index.php">Pengeluaran</a>

    <a href="../logout.php" class="logout">Logout</a>
</div>



<div class="content">

    <h1 class="page-title">Data Pengeluaran Kas Tahun <?= htmlspecialchars($tahun); ?></h1>

    <div class="top-menu">
        <a href="../dashboard.php" class="btn btn-back">← Kembali ke Dashboard</a>
        <a href="create.php" class="btn btn-add">+ Tambah Pengeluaran</a>
    </div>

    <!-- FILTER TAHUN -->
    <form method="GET" action="index.php" class="year-form">
        <label>Pilih Tahun:</label>
        <input type="number" name="tahun" value="<?= htmlspecialchars($tahun); ?>">
        <button type="submit">Filter</button>
    </form>

    <!-- TOTAL -->
    <div class="total-box">
        <h3>Total Pengeluaran:</h3>
        <p>Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></p>
    </div>

    <!-- TABLE -->
    <div class="table-card">
        <table class="expense-table">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Nama Acara</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>

            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['tanggal']); ?></td>
                <td><?= htmlspecialchars($row['jenis']); ?></td>
                <td><?= htmlspecialchars($row['nama_acara']); ?></td>
                <td><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></td>
                <td>Rp <?= number_format($row['jumlah'], 0, ',', '.'); ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                    <a href="delete.php?id=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

</body>
</html>
