<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/upload.php';
require_auth(); start_session();
$pdo = db(); $u = current_user();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $prefix = $_POST['prefix'] ?? null;
  $fullname = trim($_POST['fullname'] ?? '');
  $bio = trim($_POST['bio'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $addr = trim($_POST['address_user'] ?? '');
  $gy = $_POST['grad_year'] !== '' ? (int)$_POST['grad_year'] : null;
  $avatar = handle_upload($_FILES['avatar'] ?? null, null);
  $sql = "UPDATE users SET prefix=?, fullname=?, bio=?, phone=?, address_user=?, grad_year=?".($avatar?", avatar_path=?":"")." WHERE id=?";
  $p = [$prefix,$fullname,$bio,$phone,$addr,$gy]; if($avatar) $p[]=$avatar; $p[]=$u['id'];
  $pdo->prepare($sql)->execute($p);
  flash('ok','อัปเดตโปรไฟล์แล้ว'); refresh_current_user(); header('Location:/profile.php'); exit;
}
$st=$pdo->prepare("SELECT * FROM users WHERE id=?"); $st->execute([$u['id']]); $me=$st->fetch();
header_html('Edit Profile'); navbar($u); render_flash();
?>
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">แก้ไขโปรไฟล์</h1>
  <form method="post" enctype="multipart/form-data" class="space-y-3">
    <label class="block text-sm">คำนำหน้า
      <select name="prefix" class="w-full border px-3 py-2 rounded">
        <option value="">-- เลือก --</option>
        <option value="นาย" <?= ($me['prefix']??'')==='นาย'?'selected':''; ?>>นาย</option>
        <option value="นางสาว" <?= ($me['prefix']??'')==='นางสาว'?'selected':''; ?>>นางสาว</option>
      </select>
    </label>
    <input name="fullname" value="<?= htmlspecialchars($me['fullname']??'') ?>" placeholder="ชื่อ–นามสกุล" class="w-full border px-3 py-2 rounded">
    <textarea name="bio" rows="4" placeholder="เกี่ยวกับฉัน" class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($me['bio']??'') ?></textarea>
    <div class="grid sm:grid-cols-2 gap-3">
      <input name="phone" value="<?= htmlspecialchars($me['phone']??'') ?>" placeholder="เบอร์โทร" class="w-full border px-3 py-2 rounded">
      <input name="grad_year" type="number" value="<?= htmlspecialchars($me['grad_year']??'') ?>" placeholder="ปีที่จบ (พ.ศ.)" class="w-full border px-3 py-2 rounded">
    </div>
    <textarea name="address_user" rows="3" placeholder="ที่อยู่" class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($me['address_user']??'') ?></textarea>
    <div><input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp" class="w-full"></div>
    <button class="px-4 py-2 rounded bg-sky-600 text-white">บันทึก</button>
  </form>
</div>
<?php footer_html();
