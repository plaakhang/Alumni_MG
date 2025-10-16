-- สร้างตาราง users แบบง่าย
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตัวอย่างผู้ใช้ (อีเมลทดสอบ / รหัสผ่าน: test1234)
INSERT INTO users (email, password_hash, display_name)
VALUES ('test@example.com', 
        '$2y$10$nJc8JzCkM2o6bqvZbUQ6p.Ix7qD2I8q6E5yVtEwqvK4zX3VqGmVvG', 
        'Test User');
-- หมายเหตุ: hash ข้างบนคือของ "test1234"
