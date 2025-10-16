FROM php:8.2-apache

# เปิดใช้ extensions ที่จำเป็น
RUN docker-php-ext-install pdo pdo_mysql

# เปิด mod_rewrite (ไม่จำเป็นมาก แต่เผื่อใช้อนาคต)
RUN a2enmod rewrite

# ตั้ง timezone (เปลี่ยนได้)
ENV TZ=Asia/Bangkok

# คัดลอก php.ini แบบ dev
COPY php.ini /usr/local/etc/php/php.ini
