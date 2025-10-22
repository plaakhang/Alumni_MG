<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
require_once __DIR__.'/../lib/upload.php';
require_auth(); start_session();
if(!(has_role('admin')||has_role('editor'))){ flash('err','ต้องมีสิทธิ์ editor หรือ admin'); header('Location:/news.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??''); $body=trim($_POST['body']??''); $img=handle_upload($_FILES['image']??null,null);
  if($title===''||$body===''){ flash('err','กรอกหัวข้อและเนื้อหา'); }
  else { $pdo=db(); $pdo->prepare("INSERT INTO news (title,body,image_path,status,author_id) VALUES (?,?,?,?,?)")->execute([$title,$body,$img,'published',current_user()['id']]); flash('ok','สร้างข่าวสำเร็จ'); header('Location:/news.php'); exit; }
}
header_html('New News'); navbar(current_user()); render_flash();
?>
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">สร้างข่าว</h1>
  <form method="post" enctype="multipart/form-data" class="space-y-3">
    <input name="title" required placeholder="หัวข้อ" class="w-full border px-3 py-2 rounded">
    <textarea name="body" rows="6" required placeholder="เนื้อหา" class="w-full border px-3 py-2 rounded"></textarea>
    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full">
    <button class="px-4 py-2 rounded bg-sky-600 text-white">บันทึก</button>
  </form>
</div>
<?php footer_html();
