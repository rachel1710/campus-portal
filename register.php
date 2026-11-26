<?php
require 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $nim = trim($_POST['nim'] ?? ''); $name = trim($_POST['name'] ?? ''); $pw = $_POST['password'] ?? '';
    if ($nim === '' || $name === '' || strlen($pw) < 8) {
        $error = 'Isi semua kolom. Kata sandi minimal 8 karakter.';
    } else {
        try {
            db()->prepare('INSERT INTO users (nim, name, password_hash) VALUES (?,?,?)')
                ->execute([$nim, $name, password_hash($pw, PASSWORD_DEFAULT)]);
            header('Location: index.php'); exit;
        } catch (PDOException $ex) {
            $error = $ex->getCode() === '23000' ? 'NIM sudah terdaftar.' : 'Pendaftaran gagal.';
        }
    }
}
page_head('Daftar'); ?>
<main class="auth">
  <h1>Buat akun</h1>
  <?php if ($error): ?><p class="alert" role="alert"><?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <label>NIM <input name="nim" required></label>
    <label>Nama lengkap <input name="name" required></label>
    <label>Kata sandi <input type="password" name="password" minlength="8" required autocomplete="new-password"></label>
    <button>Daftar</button>
  </form>
  <p><a href="index.php">Kembali ke halaman masuk</a></p>
</main></body></html>
