<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

// Proses Submit
if (isset($_POST['submit'])) {

    $tanggal    = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jenis      = mysqli_real_escape_string($conn, $_POST['jenis']);
    $nama_acara = !empty($_POST['nama_acara']) ? "'" . mysqli_real_escape_string($conn, $_POST['nama_acara']) . "'" : "NULL";
    $deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $jumlah     = mysqli_real_escape_string($conn, $_POST['jumlah']);

    $sql = "INSERT INTO pengeluaran (tanggal, jenis, nama_acara, deskripsi, jumlah)
            VALUES ('$tanggal', '$jenis', $nama_acara, '$deskripsi', $jumlah)";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menambah pengeluaran: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengeluaran - CashIt</title>
<link rel="stylesheet" href="../../assets/css/style.css">

</head>
<body>

<div class="create-wrapper">

    <h2 class="create-title">Tambah Pengeluaran</h2>

    <div class="create-top-actions">
        <a href="index.php" class="btn-back-create">← Kembali</a>
    </div>

    <div class="create-card">

        <?php if (isset($error)) : ?>
            <p style="color:red; text-align:center;"><?= $error; ?></p>
        <?php endif; ?>

        <form method="POST" class="create-form">

            <div>
                <label>Tanggal</label>
                <input type="date" name="tanggal" required>
            </div>

            <div>
                <label>Jenis Pengeluaran</label>
                <select name="jenis" required>
                    <option value="umum">Umum</option>
                    <option value="acara">Acara</option>
                </select>
            </div>

            <div>
                <label>Nama Acara (opsional jika jenis = acara)</label>
                <input type="text" name="nama_acara" placeholder="Isi jika jenis acara">
            </div>

            <div>
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="3"></textarea>
            </div>

            <div>
                <label>Jumlah (Rp)</label>
                <input type="number" name="jumlah" required>
            </div>

            <button type="submit" name="submit" class="btn-submit-expense">
                Simpan Pengeluaran
            </button>

        </form>

    </div>

</div>

</body>
</html>
