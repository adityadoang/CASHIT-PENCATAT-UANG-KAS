<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";

$result = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY angkatan DESC, nama ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa - CashIt</title>
</head>
<body>
    <h1>Data Mahasiswa</h1>
    <a href="../dashboard.php">Kembali</a> | 
    <a href="create.php">+ Tambah Mahasiswa</a>
    <br><br>
    <table border="1" cellpadding="5">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Angkatan</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nim']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['kelas']; ?></td>
            <td><?= $row['angkatan']; ?></td>
            <td><?= $row['email']; ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
                <a href="delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
