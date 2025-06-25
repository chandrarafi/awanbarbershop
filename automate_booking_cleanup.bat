@echo off
REM Automate Booking Cleanup Script for Awan Barbershop
REM Buat Task Scheduler Windows dengan menjalankan script ini setiap 5 menit

cd /d "%~dp0"
echo Running booking cleanup at %date% %time%...

REM Jalankan PHP dengan script update_expired.php
php update_expired.php

echo Cleanup completed.

REM Tulis log
echo %date% %time% - Cleanup completed >> writable\logs\booking_cleanup_batch.log 