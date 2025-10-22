<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
start_session(); $user=current_user(); $pdo=db();
$id=(int)($_GET['id']??0);
$st=$pdo->prepare("SELECT n.*, u.display_name FROM news n JOIN users u ON u.id=n.author_id WHERE n.id=?");
$st->execute([$id]); $n=$st->fetch();
if(!$n){ flash('err','ไม่พบบทความนี้'); header('Location:/news.php'); exit; }
$can = $user && can_manage_news($n);
header_html($n['title']); navbar($user); render_flash();
?>
<div class="max-w-3xl mx-auto p-4">
  <article class="rounded-xl border border-slate-200 dark:border-slate-700 p-4">
    <div class="text-sm text-slate-500"><?= htmlspecialchars($n['display_name']) ?> • <?= htmlspecialchars($n['published_at']) ?> • สถานะ: <?= htmlspecialchars($n['status']) ?></div>
    <h1 class="text-2xl font-semibold mt-1 mb-3"><?= htmlspecialchars($n['title']) ?></h1>
    <?php if(!empty($n['image_path'])): ?><img src="<?= htmlspecialchars($n['image_path']) ?>" class="rounded mb-3"><?php endif; ?>
    <div class="whitespace-pre-line text-[15px]"><?= nl2br(htmlspecialchars($n['body'])) ?></div>
    <?php if($can): ?>
      <div class="mt-3 flex gap-2">
        <a class="px-3 py-1.5 rounded border text-sm" href="/news_edit.php?id=<?= (int)$n['id'] ?>">แก้ไข</a>
        <a class="px-3 py-1.5 rounded border text-sm" href="/news_delete.php?id=<?= (int)$n['id'] ?>" onclick="return confirm('ลบข่าวนี้?')">ลบ</a>
      </div>
    <?php endif; ?>
  </article>
</div>
<?php footer_html();
