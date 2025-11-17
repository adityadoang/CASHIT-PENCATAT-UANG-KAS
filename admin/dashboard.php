<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard CashIt</title>
</head>
<body>
    <h1>Dashboard CashIt</h1>
    <p>Halo, <?= $_SESSION['admin_username']; ?></p>
    <ul>
        <li><a href="mahasiswa/index.php">Kelola Mahasiswa</a></li>
        <li><a href="iuran/index.php">Status Iuran</a></li>
        <li><a href="pengeluaran/index.php">Pengeluaran</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
