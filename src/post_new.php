<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
require_once __DIR__.'/../lib/upload.php';
require_auth(); start_session();
if(!(has_role('admin')||has_role('editor'))){ flash('err','ต้องมีสิทธิ์ editor หรือ admin'); header('Location:/posts.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??''); $body=trim($_POST['body']??'');
  $img=handle_upload($_FILES['image']??null,null);
  if($title===''||$body===''){ flash('err','กรอกข้อมูลให้ครบ'); }
  else { $pdo=db(); $pdo->prepare("INSERT INTO posts(user_id,title,body,image_path) VALUES (?,?,?,?)")->execute([current_user()['id'],$title,$body,$img]); flash('ok','สร้างโพสต์สำเร็จ'); header('Location:/posts.php'); exit; }
}
header_html('New Post'); navbar(current_user()); render_flash();
?>
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">สร้างโพสต์</h1>
  <form method="post" enctype="multipart/form-data" class="space-y-3">
    <input name="title" required placeholder="หัวข้อ" class="w-full border px-3 py-2 rounded">
    <textarea name="body" rows="6" required placeholder="เนื้อหา" class="w-full border px-3 py-2 rounded"></textarea>
    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full">
    <button class="px-4 py-2 rounded bg-sky-600 text-white">บันทึก</button>
  </form>
</div>
<?php footer_html();
