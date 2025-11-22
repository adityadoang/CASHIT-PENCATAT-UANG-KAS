<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

if (isset($_POST['submit'])) {

    // Ambil data dari form
    $nama     = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $nim      = isset($_POST['nim']) ? trim($_POST['nim']) : '';
    $kelas    = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
    $angkatan = isset($_POST['angkatan']) ? trim($_POST['angkatan']) : '';

    // Validasi field wajib
    if (empty($nama) || empty($nim) || empty($kelas) || empty($angkatan)) {
        die("Semua field wajib diisi!");
    }

    // Insert mahasiswa
    $stmt = $conn->prepare("
        INSERT INTO mahasiswa (nama, nim, kelas, angkatan)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("sssi", $nama, $nim, $kelas, $angkatan);

    if (!$stmt->execute()) {
        die("Error insert mahasiswa: " . $stmt->error);
    }

    $id_mahasiswa = $stmt->insert_id;
    $stmt->close();

    // Insert iuran awal jika ada
    if ($jumlah_iuran > 0) {
        $stmt_iuran = $conn->prepare("
            INSERT INTO iuran_kas (id_mahasiswa, jumlah, tanggal)
            VALUES (?, ?, CURDATE())
        ");
        $stmt_iuran->bind_param("id", $id_mahasiswa, $jumlah_iuran);

        if (!$stmt_iuran->execute()) {
            die("Error insert iuran: " . $stmt_iuran->error);
        }

        $stmt_iuran->close();
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mahasiswa - CashIt</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

<div class="content">

    <h1 class="page-title">Tambah Mahasiswa</h1>

    <div class="top-menu">
        <a href="index.php" class="btn btn-back">← Kembali</a>
    </div>

    <div class="table-card">

        <form method="POST">

            <label>NIM</label>
            <input type="text" name="nim" required>

            <label>Nama</label>
            <input type="text" name="nama" required>

            <label>Kelas</label>
            <input type="text" name="kelas" required>

            <label>Angkatan</label>
            <input type="text" name="angkatan" required>

            <button type="submit" name="submit" class="btn btn-add" style="margin-top:15px;">
                Simpan
            </button>

        </form>

    </div>

</div>

</body>
</html>
