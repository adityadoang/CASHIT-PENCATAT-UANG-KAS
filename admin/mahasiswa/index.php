<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";

$result = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY nim ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa - CashIt</title>
<link rel="stylesheet" href="../../assets/style.css">
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



<!-- Wrapper untuk mengikuti layout dashboard -->
<div class="content">

    <!-- Judul halaman -->
    <h1 class="page-title">Data Mahasiswa</h1>

    <!-- Tombol atas -->
    <div class="top-menu">
        <a class="btn btn-back" href="../dashboard.php">← Kembali</a>
        <a class="btn btn-add" href="create.php">＋ Tambah Mahasiswa</a>
    </div>

    <!-- Kartu tabel -->
    <div class="table-card">
        <table class="expense-table">
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Angkatan</th>
                <th>Aksi</th>
            </tr>

            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) : ?>
            
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nim']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['kelas']; ?></td>
                <td><?= $row['angkatan']; ?></td>
                <td>
                    <a class="edit-btn" href="edit.php?id=<?= $row['id']; ?>">Edit</a>
                    <a class="delete-btn" 
                       href="delete.php?id=<?= $row['id']; ?>"
                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                       Hapus
                    </a>
                </td>
            </tr>

            <?php endwhile; ?>

        </table>
    </div>

</div>

</body>
</html>
