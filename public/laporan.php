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
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kas CashIt - Tahun <?= htmlspecialchars($tahun); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .container {
            max-width: 1100px;
            margin: 20px auto;
            background: #fff;
            padding: 20px 25px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }
        h1, h2, h3 {
            margin-top: 0;
        }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 14px; }
        th { background-color: #f0f0f0; }
        .center { text-align: center; }
        .right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            color: #fff;
        }
        .badge-umum { background: #6c757d; }
        .badge-acara { background: #0d6efd; }
        .summary-box {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Laporan Uang Kas - CashIt</h1>
    <h3>Tahun: <?= htmlspecialchars($tahun); ?></h3>

    <!-- form ganti tahun -->
    <form method="GET" action="laporan.php">
        <label>Pilih Tahun: </label>
        <input type="number" name="tahun" value="<?= htmlspecialchars($tahun); ?>">
        <button type="submit">Tampilkan</button>
    </form>

    <hr>

    <h2>Ringkasan Kas</h2>
    <div class="summary-box">
        <p><strong>Nominal kas per bulan per mahasiswa:</strong> Rp <?= number_format($nominal_per_bulan, 0, ',', '.'); ?></p>
        <p><strong>Total bulan terbayar (semua mahasiswa):</strong> <?= $total_bulan_terbayar; ?> bulan</p>
        <p><strong>Total Pemasukan:</strong> Rp <?= number_format($total_pemasukan, 0, ',', '.'); ?></p>
        <p><strong>Total Pengeluaran:</strong> Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></p>
        <p><strong>Saldo Akhir:</strong> Rp <?= number_format($saldo, 0, ',', '.'); ?></p>
    </div>

    <hr>

    

    <h2>Status Iuran per Mahasiswa</h2>
    <table>
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
            <td class="center"><?= htmlspecialchars($m['kelas']); ?></td>
            <?php foreach ($bulan as $b) : ?>
                <td class="center">
                    <?= !empty($m[$b]) ? '✔' : '-' ?>
                </td>
            <?php endforeach; ?>
            <td class="center"><?= $m['paid_count']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <!-- 🔻 BAGIAN BARU: RINCIAN PENGELUARAN -->
    <h2>Rincian Pengeluaran</h2>
    <?php if (mysqli_num_rows($q_pengeluaran_detil) > 0) : ?>
        <table>
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
                <td class="center"><?= $no++; ?></td>
                <td class="center"><?= htmlspecialchars($p['tanggal']); ?></td>
                <td class="center">
                    <?php if ($p['jenis'] == 'acara') : ?>
                        <span class="badge badge-acara">Acara</span>
                    <?php else : ?>
                        <span class="badge badge-umum">Umum</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['nama_acara']); ?></td>
                <td><?= nl2br(htmlspecialchars($p['deskripsi'])); ?></td>
                <td class="right">Rp <?= number_format($p['jumlah'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p><em>Belum ada pengeluaran yang tercatat di tahun ini.</em></p>
    <?php endif; ?>

    <hr>
</div>
</body>
</html>
