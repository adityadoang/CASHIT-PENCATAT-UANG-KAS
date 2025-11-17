<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

// pastikan ada id di URL
if (!isset($_GET['id'])) {
    die("ID mahasiswa tidak ditemukan.");
}

$id = $_GET['id'];

// (OPSIONAL) hapus dulu iuran_kas yang terkait mahasiswa ini
mysqli_query($conn, "DELETE FROM iuran_kas WHERE id_mahasiswa = $id");

// hapus mahasiswa
$sql = "DELETE FROM mahasiswa WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
