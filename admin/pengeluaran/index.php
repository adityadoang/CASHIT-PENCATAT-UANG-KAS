<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

// filter tahun (optional)
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date("Y");

// ambil data pengeluaran
$sql = "SELECT * FROM pengeluaran WHERE YEAR(tanggal) = '$tahun' ORDER BY tanggal DESC";
$result = mysqli_query($conn, $sql);

// hitung total pengeluaran
$q_total = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran WHERE YEAR(tanggal) = '$tahun'");
$row_total = mysqli_fetch_assoc($q_total);
$total_pengeluaran = $row_total['total'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pengeluaran - CashIt</title>
</head>
<body>
    <h1>Data Pengeluaran Kas - Tahun <?= htmlspecialchars($tahun); ?></h1>
    <a href="../dashboard.php">Kembali ke Dashboard</a> | 
    <a href="create.php">+ Tambah Pengeluaran</a>
    <br><br>

    <!-- filter tahun -->
    <form method="GET" action="index.php">
        <label>Pilih Tahun: </label>
        <input type="number" name="tahun" value="<?= htmlspecialchars($tahun); ?>">
        <button type="submit">Filter</button>
    </form>

    <br>

    <p><strong>Total Pengeluaran:</strong> Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></p>

    <table border="1" cellpadding="5">
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
        while ($row = mysqli_fetch_assoc($result)) :
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['tanggal']); ?></td>
            <td><?= htmlspecialchars($row['jenis']); ?></td>
            <td><?= htmlspecialchars($row['nama_acara']); ?></td>
            <td><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></td>
            <td>Rp <?= number_format($row['jumlah'], 0, ',', '.'); ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> | 
                <a href="delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus pengeluaran ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
