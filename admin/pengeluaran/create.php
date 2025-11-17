<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

if (isset($_POST['submit'])) {
    $tanggal    = $_POST['tanggal'];
    $jenis      = $_POST['jenis'];
    $nama_acara = $_POST['nama_acara'] ?: null;
    $deskripsi  = $_POST['deskripsi'];
    $jumlah     = $_POST['jumlah'];

    $sql = "INSERT INTO pengeluaran (tanggal, jenis, nama_acara, deskripsi, jumlah)
            VALUES ('$tanggal', '$jenis', " . ($nama_acara ? "'$nama_acara'" : "NULL") . ", '$deskripsi', $jumlah)";
    
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menambah pengeluaran: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pengeluaran - CashIt</title>
</head>
<body>
    <h1>Tambah Pengeluaran</h1>
    <a href="index.php">Kembali</a>
    <br><br>

    <?php if (isset($error)) : ?>
        <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Tanggal</label><br>
        <input type="date" name="tanggal" required><br><br>

        <label>Jenis</label><br>
        <select name="jenis" required>
            <option value="umum">Umum</option>
            <option value="acara">Acara</option>
        </select><br><br>

        <label>Nama Acara (opsional, isi kalau jenis = acara)</label><br>
        <input type="text" name="nama_acara"><br><br>

        <label>Deskripsi</label><br>
        <textarea name="deskripsi" rows="3"></textarea><br><br>

        <label>Jumlah (Rp)</label><br>
        <input type="number" name="jumlah" required><br><br>

        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>
