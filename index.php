<?php
// file: cashit/index.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CashIt - Sistem Uang Kas</title>

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: #fff;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .card h1 {
            font-size: 26px;
            margin-bottom: 10px;
        }
        .card p {
            margin-bottom: 25px;
            color: #555;
        }
        .btn-group-vertical .btn {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>CashIt</h1>
    <p>Sistem Pencatatan Uang Kas Angkatan</p>

    <div class="btn-group-vertical w-100">
        <a href="admin/login.php" class="btn btn-primary">
            Login Admin
        </a>
        <a href="public/laporan.php" class="btn btn-success">
            Lihat Laporan Kas
        </a>
    </div>
</div>

</body>
</html>
