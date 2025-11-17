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
    $nim      = $_POST['nim'];
    $nama     = $_POST['nama'];
    $kelas    = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];
    $email    = $_POST['email'];

    $sql = "UPDATE mahasiswa 
            SET nim = '$nim',
                nama = '$nama',
                kelas = '$kelas',
                angkatan = '$angkatan',
                email = '$email'
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
</head>
<body>
    <h1>Edit Mahasiswa</h1>
    <a href="index.php">Kembali</a><br><br>

    <?php if (isset($error)) : ?>
        <p style="color:red"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>NIM</label><br>
        <input type="text" name="nim" value="<?= $mahasiswa['nim']; ?>" required><br><br>

        <label>Nama</label><br>
        <input type="text" name="nama" value="<?= $mahasiswa['nama']; ?>" required><br><br>

        <label>Kelas</label><br>
        <input type="text" name="kelas" value="<?= $mahasiswa['kelas']; ?>" required><br><br>

        <label>Angkatan</label><br>
        <input type="number" name="angkatan" value="<?= $mahasiswa['angkatan']; ?>" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="<?= $mahasiswa['email']; ?>"><br><br>

        <button type="submit" name="submit">Update</button>
    </form>
</body>
</html>
