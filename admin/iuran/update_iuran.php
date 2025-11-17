<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

if (!isset($_POST['tahun'])) {
    die("Tahun tidak ditemukan.");
}

$tahun = $_POST['tahun'];
$mahasiswa_ids = isset($_POST['mahasiswa_ids']) ? $_POST['mahasiswa_ids'] : [];
$iuran_post = isset($_POST['iuran']) ? $_POST['iuran'] : [];

$bulan = ['jan','feb','mar','apr','mei','jun','jul','ags','sep','okt','nov','des'];

foreach ($mahasiswa_ids as $id_mhs) {
    $id_mhs = (int)$id_mhs;

    // cek apakah sudah ada baris iuran_kas untuk mahasiswa & tahun ini
    $cek = mysqli_query($conn, "SELECT id FROM iuran_kas WHERE id_mahasiswa = $id_mhs AND tahun = '$tahun'");
    if (mysqli_num_rows($cek) == 0) {
        // kalau belum ada, buat baris baru dengan default 0
        mysqli_query($conn, "INSERT INTO iuran_kas (id_mahasiswa, tahun) VALUES ($id_mhs, '$tahun')");
    }

    // buat query UPDATE untuk 12 bulan
    $set_parts = [];
    foreach ($bulan as $b) {
        // kalau checkbox ada di POST → 1, kalau tidak → 0
        $val = (isset($iuran_post[$id_mhs]) && isset($iuran_post[$id_mhs][$b])) ? 1 : 0;
        $set_parts[] = "$b = $val";
    }

    $set_sql = implode(", ", $set_parts);

    $update_sql = "UPDATE iuran_kas SET $set_sql WHERE id_mahasiswa = $id_mhs AND tahun = '$tahun'";
    mysqli_query($conn, $update_sql);
}

// setelah selesai, balik lagi ke index
header("Location: index.php?tahun=" . urlencode($tahun));
exit;
