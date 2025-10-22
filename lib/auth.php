<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/ui.php';

function start_session(){ if(session_status()!==PHP_SESSION_ACTIVE) session_start(); }

function current_user(): ?array {
  start_session();
  return $_SESSION['user'] ?? null;
}

function refresh_current_user(): void {
  $u = current_user(); if(!$u) return;
  $pdo = db();
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
  $stmt->execute([$u['id']]);
  $row = $stmt->fetch();
  if($row){
    $_SESSION['user'] = [
      'id'=>$row['id'], 'email'=>$row['email'], 'display_name'=>$row['display_name'],
      'avatar_path'=>$row['avatar_path'] ?? null
    ];
  }
}

function require_auth(){
  if(!current_user()){
    flash('err','กรุณาเข้าสู่ระบบ');
    header('Location: /login.php'); exit;
  }
}

function login(string $email, string $password): bool {
  $pdo = db();
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if($u && (password_verify($password, $u['password_hash']) || hash('sha256',$password)===$u['password_hash'])){ // รองรับ seed แบบ SHA2 และ bcrypt
    start_session();
    $_SESSION['user'] = ['id'=>$u['id'],'email'=>$u['email'],'display_name'=>$u['display_name'],'avatar_path'=>$u['avatar_path']??null];
    return true;
  }
  return false;
}

function register_user(array $d): bool {
  $email = trim($d['email'] ?? '');
  $display = trim($d['display_name'] ?? '');
  $pass = $d['password'] ?? '';
  if($email==='' || $display==='' || strlen($pass)<6) return false;
  $hash = password_hash($pass, PASSWORD_DEFAULT);
  $pdo = db();
  $stmt = $pdo->prepare("INSERT INTO users (email,password_hash,display_name) VALUES (?,?,?)");
  $stmt->execute([$email,$hash,$display]);
  // ให้ role member โดยอัตโนมัติ
  try {
    $pdo->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (LAST_INSERT_ID(), (SELECT id FROM roles WHERE name='member' LIMIT 1))")->execute();
  } catch(Throwable $e){}
  return true;
}
