<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";

if (isset($_POST['submit'])) {
    $nim      = $_POST['nim'];
    $nama     = $_POST['nama'];
    $kelas    = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];
    $email    = $_POST['email'];

    $query = "INSERT INTO mahasiswa (nim, nama, kelas, angkatan, email)
              VALUES ('$nim', '$nama', '$kelas', '$angkatan', '$email')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menambah data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mahasiswa - CashIt</title>
</head>
<body>
    <h1>Tambah Mahasiswa</h1>
    <a href="index.php">Kembali</a><br><br>

    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <form method="POST">
        <label>NIM</label><br>
        <input type="text" name="nim" required><br><br>

        <label>Nama</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Kelas</label><br>
        <input type="text" name="kelas" required><br><br>

        <label>Angkatan</label><br>
        <input type="number" name="angkatan" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email"><br><br>

        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>
