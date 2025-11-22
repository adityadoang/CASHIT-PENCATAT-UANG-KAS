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

// ambil data lama
$q = mysqli_query($conn, "SELECT * FROM pengeluaran WHERE id = $id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Data pengeluaran tidak ditemukan.");
}

if (isset($_POST['submit'])) {
    $tanggal    = $_POST['tanggal'];
    $jenis      = $_POST['jenis'];
    $nama_acara = $_POST['nama_acara'] ?: null;
    $deskripsi  = $_POST['deskripsi'];
    $jumlah     = $_POST['jumlah'];

    $sql = "UPDATE pengeluaran SET
                tanggal = '$tanggal',
                jenis = '$jenis',
                nama_acara = " . ($nama_acara ? "'$nama_acara'" : "NULL") . ",
                deskripsi = '$deskripsi',
                jumlah = $jumlah
            WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal mengupdate pengeluaran: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengeluaran - CashIt</title>
</head>
<body>
    <div class="content">
    <h1>Edit Pengeluaran</h1>
    <a href="index.php">Kembali</a>
    <br><br>

    <?php if (isset($error)) : ?>
        <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Tanggal</label><br>
        <input type="date" name="tanggal" value="<?= $data['tanggal']; ?>" required><br><br>

        <label>Jenis</label><br>
        <select name="jenis" required>
            <option value="umum" <?= $data['jenis'] == 'umum' ? 'selected' : ''; ?>>Umum</option>
            <option value="acara" <?= $data['jenis'] == 'acara' ? 'selected' : ''; ?>>Acara</option>
        </select><br><br>

        <label>Nama Acara (opsional)</label><br>
        <input type="text" name="nama_acara" value="<?= htmlspecialchars($data['nama_acara']); ?>"><br><br>

        <label>Deskripsi</label><br>
        <textarea name="deskripsi" rows="3"><?= htmlspecialchars($data['deskripsi']); ?></textarea><br><br>

        <label>Jumlah (Rp)</label><br>
        <input type="number" name="jumlah" value="<?= $data['jumlah']; ?>" required><br><br>

        <button type="submit" name="submit">Update</button>
    </form>
    </div>
</body>
</html>
