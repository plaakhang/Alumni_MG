# Alumni WebApp (PHP + MySQL + Docker)

- Web: http://localhost:8000
- phpMyAdmin: http://localhost:8080  (Server: db)

## Credentials (ค่าเริ่มต้น)
- MySQL Root: root / rootpass
- App DB: app / apppass
- Database: alumni_db

## คำสั่งพื้นฐาน
- รันบริการ: docker compose up -d
- หยุด: docker compose down
- ดู log เว็บ: docker compose logs -f web
- ดู log DB: docker compose logs -f db
