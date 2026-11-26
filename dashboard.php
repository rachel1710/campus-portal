<?php
require 'config.php'; require_login();
$news = db()->query('SELECT title, body, posted_at FROM announcements ORDER BY posted_at DESC LIMIT 10')->fetchAll();
$courses = db()->query('SELECT code, title, lecturer FROM courses ORDER BY title')->fetchAll();
page_head('Beranda'); include 'nav.php'; ?>
<main class="wrap">
  <h1>Informasi kampus</h1>
  <?php foreach ($news as $n): ?>
    <article class="card"><h2><?= e($n['title']) ?></h2><p><?= e($n['body']) ?></p><small><?= e($n['posted_at']) ?></small></article>
  <?php endforeach; ?>
  <h2>Mata kuliah</h2>
  <div class="scroll"><table>
    <tr><th>Kode</th><th>Mata kuliah</th><th>Dosen</th></tr>
    <?php foreach ($courses as $c): ?><tr><td><?= e($c['code']) ?></td><td><?= e($c['title']) ?></td><td><?= e($c['lecturer']) ?></td></tr><?php endforeach; ?>
  </table></div>
</main></body></html>
