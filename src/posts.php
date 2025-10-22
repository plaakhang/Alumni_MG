<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
start_session(); $user=current_user(); $pdo=db();
$q=$pdo->query("SELECT p.*, u.display_name, u.avatar_path FROM posts p JOIN users u ON u.id=p.user_id ORDER BY p.created_at DESC"); $rows=$q->fetchAll();
header_html('Posts'); navbar($user); render_flash();
?>
<div class="max-w-3xl mx-auto p-4">
  <div class="flex justify-between items-center mb-3">
    <h2 class="text-xl font-semibold">โพสต์ล่าสุด</h2>
    <?php if($user && (has_role('admin')||has_role('editor'))): ?>
      <a href="/post_new.php" class="px-3 py-1.5 rounded bg-sky-600 text-white text-sm">สร้างโพสต์</a>
    <?php endif; ?>
  </div>
  <?php if(!$rows): ?>
    <div class="text-slate-500">ยังไม่มีโพสต์</div>
  <?php else: foreach($rows as $r):
    $avatar = $r['avatar_path'] ?: 'https://cdn-icons-png.flaticon.com/512/9815/9815472.png'; ?>
    <article class="mb-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-800/60">
      <header class="px-4 py-3 flex items-center gap-3">
        <img src="<?= htmlspecialchars($avatar) ?>" class="w-10 h-10 rounded-full object-cover border" alt="">
        <div>
          <div class="font-semibold leading-tight"><?= htmlspecialchars($r['display_name']) ?></div>
          <div class="text-xs text-slate-500"><?= htmlspecialchars(time_ago_th($r['created_at'])) ?></div>
        </div>
      </header>
      <div class="px-4 pb-4">
        <a href="/post_view.php?id=<?= (int)$r['id'] ?>" class="block">
          <h3 class="text-lg font-medium mb-1"><?= htmlspecialchars($r['title']) ?></h3>
          <p class="text-slate-600 dark:text-slate-300 line-clamp-3"><?= nl2br(htmlspecialchars($r['body'])) ?></p>
        </a>
      </div>
    </article>
  <?php endforeach; endif; ?>
</div>
<?php footer_html();
