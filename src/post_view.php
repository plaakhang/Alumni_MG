<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
start_session(); $user=current_user(); $pdo=db();
$id=(int)($_GET['id']??0);
$st=$pdo->prepare("SELECT p.*, u.display_name, u.avatar_path FROM posts p JOIN users u ON u.id=p.user_id WHERE p.id=?");
$st->execute([$id]); $post=$st->fetch();
if(!$post){ flash('err','ไม่พบโพสต์'); header('Location:/posts.php'); exit; }
$avatar = $post['avatar_path'] ?: 'https://cdn-icons-png.flaticon.com/512/9815/9815472.png';
header_html($post['title']); navbar($user); render_flash();
?>
<div class="max-w-3xl mx-auto p-4">
  <article class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-800/60">
    <header class="px-4 py-3 flex items-center gap-3">
      <img src="<?= htmlspecialchars($avatar) ?>" class="w-10 h-10 rounded-full object-cover border" alt="">
      <div>
        <div class="font-semibold"><?= htmlspecialchars($post['display_name']) ?></div>
        <div class="text-xs text-slate-500"><?= htmlspecialchars(time_ago_th($post['created_at'])) ?></div>
      </div>
      <div class="ml-auto">
        <?php if($user && can_manage_post($post)): ?>
          <a class="text-sm px-2 py-1 rounded border" href="/post_edit.php?id=<?= (int)$post['id'] ?>">แก้ไข</a>
          <a class="text-sm px-2 py-1 rounded border" href="/post_delete.php?id=<?= (int)$post['id'] ?>" onclick="return confirm('ลบโพสต์นี้?')">ลบ</a>
        <?php endif; ?>
      </div>
    </header>
    <div class="px-4 pb-4">
      <h1 class="text-xl font-semibold mb-2"><?= htmlspecialchars($post['title']) ?></h1>
      <?php if(!empty($post['image_path'])): ?>
        <img src="<?= htmlspecialchars($post['image_path']) ?>" class="rounded-lg mb-3 max-h-[480px] object-contain">
      <?php endif; ?>
      <div class="whitespace-pre-line"><?= nl2br(htmlspecialchars($post['body'])) ?></div>
    </div>
  </article>

  <?php
  // comments (ถ้ามีตาราง)
  if(table_exists($pdo,'comments')):
    if($_SERVER['REQUEST_METHOD']==='POST' && $user){
      $body=trim($_POST['body']??'');
      if($body!==''){ $pdo->prepare("INSERT INTO comments(post_id,user_id,body) VALUES (?,?,?)")->execute([$id,$user['id'],$body]); header("Location:/post_view.php?id=$id"); exit; }
    }
    $c=$pdo->prepare("SELECT c.*, u.display_name, u.avatar_path FROM comments c JOIN users u ON u.id=c.user_id WHERE c.post_id=? ORDER BY c.created_at ASC");
    $c->execute([$id]); $comments=$c->fetchAll();
  ?>
  <section class="mt-4">
    <h3 class="font-medium mb-2">ความคิดเห็น</h3>
    <div class="space-y-3">
      <?php foreach($comments as $cm):
        $av = $cm['avatar_path'] ?: 'https://cdn-icons-png.flaticon.com/512/9815/9815472.png'; ?>
        <div class="flex gap-3">
          <img src="<?= htmlspecialchars($av) ?>" class="w-8 h-8 rounded-full object-cover border">
          <div>
            <div class="text-sm font-medium"><?= htmlspecialchars($cm['display_name']) ?> <span class="text-xs text-slate-500"><?= htmlspecialchars(time_ago_th($cm['created_at'])) ?></span></div>
            <div class="text-sm"><?= nl2br(htmlspecialchars($cm['body'])) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if($user): ?>
        <form method="post" class="flex items-start gap-3">
          <input name="body" class="flex-1 border px-3 py-2 rounded" placeholder="เขียนความคิดเห็น...">
          <button class="px-3 py-2 rounded bg-sky-600 text-white text-sm">ส่ง</button>
        </form>
      <?php else: ?>
        <div class="text-sm text-slate-500">เข้าสู่ระบบเพื่อแสดงความคิดเห็น</div>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>
</div>
<?php footer_html();
