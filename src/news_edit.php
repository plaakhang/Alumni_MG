<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
require_once __DIR__.'/../lib/upload.php';
require_auth(); start_session();
$pdo=db(); $id=(int)($_GET['id']??0);
$st=$pdo->prepare("SELECT * FROM news WHERE id=?"); $st->execute([$id]); $n=$st->fetch();
if(!$n){ flash('err','ไม่พบข่าว'); header('Location:/news.php'); exit; }
if(!can_manage_news($n)){ flash('err','ไม่มีสิทธิ์แก้ไข'); header('Location:/news.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??''); $body=trim($_POST['body']??''); $img=handle_upload($_FILES['image']??null,$n['image_path']);
  if($title===''||$body===''){ flash('err','กรอกข้อมูลให้ครบ'); }
  else { $pdo->prepare("UPDATE news SET title=?, body=?, image_path=?, updated_at=NOW() WHERE id=?")->execute([$title,$body,$img,$id]); flash('ok','แก้ไขแล้ว'); header("Location:/news_view.php?id=$id"); exit; }
}
header_html('Edit News'); navbar(current_user()); render_flash();
?>
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">แก้ไขข่าว</h1>
  <form method="post" enctype="multipart/form-data" class="space-y-3">
    <input name="title" value="<?= htmlspecialchars($n['title']) ?>" required class="w-full border px-3 py-2 rounded">
    <textarea name="body" rows="6" required class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($n['body']) ?></textarea>
    <?php if(!empty($n['image_path'])): ?><img src="<?= htmlspecialchars($n['image_path']) ?>" class="rounded mb-2 max-h-60"><?php endif; ?>
    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
    <button class="px-4 py-2 rounded bg-sky-600 text-white">บันทึก</button>
  </form>
</div>
<?php footer_html();
