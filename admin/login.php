<?php
session_start();
require_once "../config/db.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin_users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if (!$user) {
        $error = "Username tidak ditemukan";
    } else {
        if ($password === $user['password']) {
            $_SESSION['admin_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login CashIt</title>
    <link rel="stylesheet" href="../assets/style.css">

</head>
<body class="login-body">

<div class="login-card">
    <h2 class="login-title">CashIt System</h2>
    <p class="login-subtitle">Masuk sebagai administrator</p>

    <?php if (isset($error)) : ?>
        <div class="login-error"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label class="login-label">Username</label>
        <input type="text" name="username" class="login-input" required>

        <label class="login-label">Password</label>
        <input type="password" name="password" class="login-input" required>

        <button type="submit" name="login" class="login-button">Masuk</button>
    </form>
</div>

</body>
</html>
