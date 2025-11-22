<?php
require_once "../config/db.php";

// 1. tentukan tahun (bisa pilih)
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date("Y");

// 2. nominal kas per bulan per mahasiswa (SILAKAN GANTI SESUAI KESPAKATAN)
$nominal_per_bulan = 10000; // Rp 10.000

// 3. ambil data iuran + mahasiswa
$sql = "SELECT m.id, m.nim, m.nama, m.kelas, m.angkatan,
        i.jan, i.feb, i.mar, i.apr, i.mei, i.jun,
        i.jul, i.ags, i.sep, i.okt, i.nov, i.des
        FROM mahasiswa m
        LEFT JOIN iuran_kas i
        ON m.id = i.id_mahasiswa AND i.tahun = '$tahun'
        ORDER BY m.nama ASC";

$result = mysqli_query($conn, $sql);

// 4. hitung total bulan terbayar (untuk pemasukan)
$bulan = ['jan','feb','mar','apr','mei','jun','jul','ags','sep','okt','nov','des'];
$total_bulan_terbayar = 0;

// simpan data mahasiswa ke array
$data_mahasiswa = [];

while ($row = mysqli_fetch_assoc($result)) {
    $paid_count = 0;
    foreach ($bulan as $b) {
        if (!empty($row[$b])) {
            $paid_count++;
            $total_bulan_terbayar++;
        }
    }
    $row['paid_count'] = $paid_count;
    $data_mahasiswa[] = $row;
}

// 5. total pemasukan = banyaknya bulan terbayar × nominal
$total_pemasukan = $total_bulan_terbayar * $nominal_per_bulan;

// 6. total & rincian pengeluaran di tahun tersebut
$q_pengeluaran_total = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM pengeluaran WHERE YEAR(tanggal) = '$tahun'");
$row_pengeluaran_total = mysqli_fetch_assoc($q_pengeluaran_total);
$total_pengeluaran = $row_pengeluaran_total['total_keluar'] ?? 0;

// ambil detail pengeluaran
$q_pengeluaran_detil = mysqli_query($conn, "SELECT * FROM pengeluaran WHERE YEAR(tanggal) = '$tahun' ORDER BY tanggal ASC, id ASC");

// 7. saldo
$saldo = $total_pemasukan - $total_pengeluaran;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kas CashIt - Tahun <?= htmlspecialchars($tahun); ?></title>
    <!-- SESUAIKAN PATH JIKA PERLU -->
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="content">

    <!-- Judul Halaman -->
    <h1 class="page-title">Laporan Uang Kas - CashIt</h1>


    <!-- Filter Tahun (full width, tanpa card) -->
    <section style="margin: 15px 0 25px 0;">
        <h2 style="margin-bottom: 8px;">Tahun Laporan</h2>
        <form method="GET" action="laporan.php" class="year-form">
            <label for="tahun">Pilih Tahun:</label>
            <input type="number" id="tahun" name="tahun" value="<?= htmlspecialchars($tahun); ?>">
            <button type="submit">Tampilkan</button>
        </form>
    </section>

    <!-- Ringkasan Kas (full width) -->
    <section style="margin-bottom: 25px;">
        <h2 style="margin-bottom: 8px;">Ringkasan Kas Tahun <?= htmlspecialchars($tahun); ?></h2>
        <p><strong>Nominal kas per bulan per mahasiswa:</strong>
            Rp <?= number_format($nominal_per_bulan, 0, ',', '.'); ?></p>
        <p><strong>Total bulan terbayar (semua mahasiswa):</strong>
            <?= $total_bulan_terbayar; ?> bulan</p>
        <p><strong>Total Pemasukan:</strong>
            Rp <?= number_format($total_pemasukan, 0, ',', '.'); ?></p>
        <p><strong>Total Pengeluaran:</strong>
            Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></p>
        <p><strong>Saldo Akhir:</strong>
            Rp <?= number_format($saldo, 0, ',', '.'); ?></p>
    </section>

    <!-- Status Iuran per Mahasiswa (full width table) -->
    <section style="margin-bottom: 30px;">
        <h2 style="margin-bottom: 10px;">Status Iuran per Mahasiswa</h2>
        <table class="iuran-table">
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
                <th>Jumlah Bulan Lunas</th>
            </tr>
            <?php foreach ($data_mahasiswa as $m) : ?>
                <tr>
                    <td><?= htmlspecialchars($m['nim']); ?></td>
                    <td><?= htmlspecialchars($m['nama']); ?></td>
                    <td><?= htmlspecialchars($m['kelas']); ?></td>
                    <?php foreach ($bulan as $b) : ?>
                        <td>
                            <?= !empty($m[$b]) ? '✔' : '-' ?>
                        </td>
                    <?php endforeach; ?>
                    <td><?= $m['paid_count']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Rincian Pengeluaran (full width table) -->
    <section style="margin-bottom: 20px;">
        <h2 style="margin-bottom: 10px;">Rincian Pengeluaran</h2>

        <?php if (mysqli_num_rows($q_pengeluaran_detil) > 0) : ?>
            <table class="expense-table">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Nama Acara</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
                <?php
                $no = 1;
                while ($p = mysqli_fetch_assoc($q_pengeluaran_detil)) :
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($p['tanggal']); ?></td>
                        <td>
                            <?= ($p['jenis'] === 'acara') ? 'Acara' : 'Umum'; ?>
                        </td>
                        <td><?= htmlspecialchars($p['nama_acara']); ?></td>
                        <td><?= nl2br(htmlspecialchars($p['deskripsi'])); ?></td>
                        <td>Rp <?= number_format($p['jumlah'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p><em>Belum ada pengeluaran yang tercatat di tahun ini.</em></p>
        <?php endif; ?>
    </section>

</div>

</body>
</html>
