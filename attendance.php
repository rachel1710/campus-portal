<?php
require 'config.php'; require_login();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $status = in_array($_POST['status'] ?? '', ['hadir','izin','sakit'], true) ? $_POST['status'] : 'hadir';
    try {
        db()->prepare('INSERT INTO attendance (user_id, course_id, att_date, status) VALUES (?,?,CURDATE(),?)')
            ->execute([$_SESSION['uid'], (int)($_POST['course_id'] ?? 0), $status]);
        $msg = 'Presensi tersimpan.';
    } catch (PDOException $ex) {
        $msg = $ex->getCode() === '23000' ? 'Anda sudah presensi untuk mata kuliah ini hari ini.' : 'Presensi gagal.';
    }
}
$courses = db()->query('SELECT id, title FROM courses ORDER BY title')->fetchAll();
$st = db()->prepare('SELECT a.att_date, c.title, a.status FROM attendance a JOIN courses c ON c.id = a.course_id WHERE a.user_id = ? ORDER BY a.att_date DESC, a.id DESC');
$st->execute([$_SESSION['uid']]); $rows = $st->fetchAll();
page_head('Presensi'); include 'nav.php'; ?>
<main class="wrap">
  <h1>Presensi</h1>
  <?php if ($msg): ?><p class="alert" role="status"><?= e($msg) ?></p><?php endif; ?>
  <form method="post" class="inline">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <label>Mata kuliah <select name="course_id"><?php foreach ($courses as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['title']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="hadir">Hadir</option><option value="izin">Izin</option><option value="sakit">Sakit</option></select></label>
    <button>Simpan presensi</button>
  </form>
  <h2>Riwayat presensi</h2>
  <?php if (!$rows): ?><p>Belum ada presensi. Pilih mata kuliah lalu simpan presensi pertama Anda.</p><?php else: ?>
  <div class="scroll"><table><tr><th>Tanggal</th><th>Mata kuliah</th><th>Status</th></tr>
    <?php foreach ($rows as $r): ?><tr><td><?= e($r['att_date']) ?></td><td><?= e($r['title']) ?></td><td><?= e($r['status']) ?></td></tr><?php endforeach; ?>
  </table></div><?php endif; ?>
</main></body></html>
