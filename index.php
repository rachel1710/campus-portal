<?php
require 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $st = db()->prepare('SELECT * FROM users WHERE nim = ?');
    $st->execute([trim($_POST['nim'] ?? '')]);
    $u = $st->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['uid'] = $u['id']; $_SESSION['name'] = $u['name'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'NIM atau kata sandi salah.';
}
page_head('Masuk'); ?>
<main class="auth">
  <h1>Portal Informasi &amp; Presensi Kampus</h1>
  <?php if ($error): ?><p class="alert" role="alert"><?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <label>NIM <input name="nim" required autocomplete="username"></label>
    <label>Kata sandi <input type="password" name="password" required autocomplete="current-password"></label>
    <button>Masuk</button>
  </form>
  <p>Belum punya akun? <a href="register.php">Daftar</a></p>
</main></body></html>
