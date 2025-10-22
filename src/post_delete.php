<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
require_auth();
$id=(int)($_GET['id']??0); $pdo=db();
$st=$pdo->prepare("SELECT * FROM posts WHERE id=?"); $st->execute([$id]); $post=$st->fetch();
if($post && can_manage_post($post)){ $pdo->prepare("DELETE FROM posts WHERE id=?")->execute([$id]); flash('ok','ลบโพสต์แล้ว'); }
else { flash('err','ไม่มีสิทธิ์ลบหรือไม่พบโพสต์'); }
header('Location:/posts.php'); exit;
