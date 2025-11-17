<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

// tahun bisa diambil dari GET, default tahun sekarang
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date("Y");

// Ambil data mahasiswa + iuran tahun tersebut
$sql = "SELECT m.id, m.nim, m.nama, m.kelas, m.angkatan,
        i.jan, i.feb, i.mar, i.apr, i.mei, i.jun,
        i.jul, i.ags, i.sep, i.okt, i.nov, i.des
        FROM mahasiswa m
        LEFT JOIN iuran_kas i
        ON m.id = i.id_mahasiswa AND i.tahun = '$tahun'
        ORDER BY m.nama ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Status Iuran Kas - CashIt</title>
</head>
<body>
    <h1>Status Iuran Kas Tahun <?= htmlspecialchars($tahun); ?></h1>
    <a href="../dashboard.php">Kembali ke Dashboard</a>
    <br><br>

    <!-- Form pilih tahun (optional) -->
    <form method="GET" action="index.php">
        <label>Pilih Tahun: </label>
        <input type="number" name="tahun" value="<?= htmlspecialchars($tahun); ?>">
        <button type="submit">Ganti Tahun</button>
    </form>

    <br>

    <form method="POST" action="update_iuran.php">
        <!-- kirim tahun ke proses -->
        <input type="hidden" name="tahun" value="<?= htmlspecialchars($tahun); ?>">

        <table border="1" cellpadding="5">
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Ags</th>
                <th>Sep</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
            </tr>
            <?php
            $bulan = ['jan','feb','mar','apr','mei','jun','jul','ags','sep','okt','nov','des'];
            while ($row = mysqli_fetch_assoc($result)) :
                $id_mhs = $row['id'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['nim']); ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['kelas']); ?></td>

                <!-- hidden untuk pastikan id mahasiswa selalu terkirim -->
                <input type="hidden" name="mahasiswa_ids[]" value="<?= $id_mhs; ?>">

                <?php foreach ($bulan as $b) : 
                    $checked = !empty($row[$b]) ? 'checked' : '';
                ?>
                    <td style="text-align:center;">
                        <input type="checkbox" name="iuran[<?= $id_mhs; ?>][<?= $b; ?>]" <?= $checked; ?>>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
