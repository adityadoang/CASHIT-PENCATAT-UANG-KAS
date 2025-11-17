<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

if (!isset($_GET['id'])) {
    die("ID pengeluaran tidak ditemukan.");
}

$id = (int) $_GET['id'];

$sql = "DELETE FROM pengeluaran WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus pengeluaran: " . mysqli_error($conn);
}
