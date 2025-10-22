<?php
function db(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;
  $host = getenv('DB_HOST') ?: 'db';
  $port = getenv('DB_PORT') ?: '3306';
  $name = getenv('DB_DATABASE') ?: 'alumni_db';
  $user = getenv('DB_USERNAME') ?: 'app';
  $pass = getenv('DB_PASSWORD') ?: 'apppass';
  $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  return $pdo;
}
function table_exists(PDO $pdo, string $t): bool {
  try { $pdo->query("SELECT 1 FROM `$t` LIMIT 1"); return true; }
  catch(Throwable $e){ return false; }
}
