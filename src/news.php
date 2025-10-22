<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
start_session(); $user=current_user(); $pdo=db();
$q=$pdo->query("SELECT n.*, u.display_name FROM news n JOIN users u ON u.id=n.author_id WHERE n.status='published' ORDER BY n.published_at DESC");
$rows=$q->fetchAll();
header_html('News'); navbar($user); render_flash();
?>
<div class="max-w-4xl mx-auto p-4">
  <div class="flex justify-between items-center mb-3">
    <h2 class="text-xl font-semibold">ข่าวสาร</h2>
    <?php if($user && (has_role('admin')||has_role('editor'))): ?>
      <a href="/news_new.php" class="px-3 py-1.5 rounded bg-sky-600 text-white text-sm">เพิ่มข่าว</a>
    <?php endif; ?>
  </div>
  <?php if(!$rows): ?>
    <div class="text-slate-500">ยังไม่มีข่าว</div>
  <?php else: ?>
    <div class="grid sm:grid-cols-2 gap-4">
      <?php foreach($rows as $r): ?>
        <a href="/news_view.php?id=<?= (int)$r['id'] ?>" class="rounded-xl border p-4 hover:bg-slate-50 dark:hover:bg-slate-800/60">
          <div class="text-sm text-slate-500"><?= htmlspecialchars($r['display_name']) ?> • <?= htmlspecialchars(date('Y-m-d', strtotime($r['published_at']))) ?></div>
          <div class="text-lg font-medium mt-1"><?= htmlspecialchars($r['title']) ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php footer_html();
