<?php
require_once __DIR__.'/ui.php';
function handle_upload(?array $f, ?string $old=null): ?string {
  if(!$f || ($f['error']??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE) return $old;
  if($f['error']!==UPLOAD_ERR_OK) { flash('err','อัปโหลดล้มเหลว'); return $old; }
  $allowed=['jpg','jpeg','png','webp']; $max=2*1024*1024;
  if($f['size']>$max){ flash('err','ไฟล์ใหญ่เกิน 2MB'); return $old; }
  $ext=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));
  if(!in_array($ext,$allowed,true)){ flash('err','อนุญาต JPG/PNG/WebP'); return $old; }
  $finfo=finfo_open(FILEINFO_MIME_TYPE); $mime=finfo_file($finfo,$f['tmp_name']); finfo_close($finfo);
  if(!in_array($mime,['image/jpeg','image/png','image/webp'],true)){ flash('err','ไฟล์ไม่ใช่รูป'); return $old; }
  $name = bin2hex(random_bytes(8)).'.'.$ext;
  $dest = __DIR__.'/../uploads/'.$name;
  if(!move_uploaded_file($f['tmp_name'],$dest)){ flash('err','ย้ายไฟล์ไม่สำเร็จ'); return $old; }
  return '/uploads/'.$name;
}
