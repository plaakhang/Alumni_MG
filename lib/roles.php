<?php
require_once __DIR__.'/auth.php';
require_once __DIR__.'/db.php';

function user_roles(int $uid): array {
  $pdo = db();
  try{
    $st = $pdo->prepare("SELECT r.name FROM user_roles ur JOIN roles r ON r.id=ur.role_id WHERE ur.user_id=?");
    $st->execute([$uid]);
    return array_map(fn($r)=>$r['name'], $st->fetchAll());
  }catch(Throwable $e){ return []; }
}
function has_role(string $role): bool {
  $u = current_user(); if(!$u) return false;
  static $cache = null;
  if($cache===null) $cache = user_roles((int)$u['id']);
  return in_array($role, $cache, true);
}
function can_manage_post(array $post): bool {
  $u = current_user(); if(!$u) return false;
  if(has_role('admin')) return true;
  if(has_role('editor') && (int)$post['user_id']===(int)$u['id']) return true;
  return false;
}
function can_manage_news(array $news): bool {
  $u = current_user(); if(!$u) return false;
  if(has_role('admin')) return true;
  if(has_role('editor') && (int)$news['author_id']===(int)$u['id']) return true;
  return false;
}
