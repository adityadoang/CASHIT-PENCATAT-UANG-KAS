<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

// 1. Ambil ID dari URL
if (!isset($_GET['id'])) {
    die("ID mahasiswa tidak ditemukan.");
}

$id = $_GET['id'];

// 2. Ambil data mahasiswa berdasarkan ID
$q = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id = $id");
$mahasiswa = mysqli_fetch_assoc($q);

if (!$mahasiswa) {
    die("Data mahasiswa tidak ditemukan.");
}

// 3. Jika form disubmit → update data
if (isset($_POST['submit'])) {
    $nama     = $_POST['nama'];
    $nim      = $_POST['nim'];
    $kelas    = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];

    $sql = "UPDATE mahasiswa 
            SET nama = '$nama',
                nim = '$nim',
                kelas = '$kelas',
                angkatan = '$angkatan'
            WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Mahasiswa - CashIt</title>

    <!-- Tambahkan CSS -->
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

<div class="content">

    <h1 class="page-title">Edit Data Mahasiswa</h1>

    <div class="top-menu">
        <a href="index.php" class="btn btn-back">← Kembali</a>
    </div>

    <?php if (isset($error)) : ?>
        <div class="error"><?= $error; ?></div>
    <?php endif; ?>

    <div class="table-card">

        <form method="POST">

            <label>NIM</label>
            <input type="text" name="nim" value="<?= $mahasiswa['nim']; ?>" required>

            <label>Nama</label>
            <input type="text" name="nama" value="<?= $mahasiswa['nama']; ?>" required>

            <label>Kelas</label>
            <input type="text" name="kelas" value="<?= $mahasiswa['kelas']; ?>" required>

            <label>Angkatan</label>
            <input type="number" name="angkatan" value="<?= $mahasiswa['angkatan']; ?>" required>



            <button type="submit" name="submit" class="btn btn-add" style="margin-top:15px;">
                Update
            </button>

        </form>

    </div>

</div>

</body>
</html>
