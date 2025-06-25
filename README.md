# Awan Barbershop - Sistem Booking Online

"With great power comes great responsibility."

## Sistem Penanganan Booking Expired

Awan Barbershop menggunakan pendekatan multi-layer untuk memastikan booking yang telah melewati batas waktu pembayaran akan diupdate secara otomatis:

### 1. Sistem Multi-Layer

Sistem ini menggunakan 5 lapisan mekanisme untuk memastikan booking yang expired diperbarui statusnya:

1. **WebSocket Server (Real-time)**:

   - Memberikan notifikasi real-time kepada pengguna saat booking expired
   - File: `app/Commands/WebSocketServer.php` dan `public/assets/js/booking-socket.js`

2. **AJAX Polling (Client-side fallback)**:

   - Memeriksa status booking secara berkala dari sisi client
   - Berjalan setiap 30 detik pada halaman pembayaran
   - File: `app/Views/customer/booking/payment.php`

3. **Cron Job (Server-side scheduled task)**:

   - Endpoint untuk memeriksa dan memperbarui booking expired secara terjadwal
   - URL: `/cron/check-expired-bookings?key=awanbarbershop_secret_key`
   - File: `app/Controllers/Cron.php`

4. **CLI Command (Manual check)**:

   - Perintah CLI untuk memeriksa dan memperbarui booking expired
   - Command: `php spark booking:check-expired [--force] [--booking=KODE]`
   - File: `app/Commands/CheckExpiredBookings.php`

5. **Direct Script (Emergency fix)**:
   - Script yang dapat dijalankan langsung untuk memeriksa dan memperbarui booking
   - File: `direct_update.php`

### 2. Endpoint-endpoint untuk Debugging

Untuk keperluan debugging, terdapat beberapa endpoint yang dapat diakses:

1. **List Semua Booking Pending**:

   - URL: `/cron/list-pending-bookings?key=awanbarbershop_secret_key`
   - Menampilkan semua booking yang masih pending beserta informasi waktunya

2. **Check Status Booking Tertentu**:

   - URL: `/cron/check-booking/KODE_BOOKING?key=awanbarbershop_secret_key`
   - Memeriksa status booking tertentu dan menampilkan debug info

3. **Force Expire Booking**:
   - URL: `/cron/expire-booking/KODE_BOOKING?key=awanbarbershop_secret_key`
   - Memaksa status booking menjadi expired tanpa memeriksa waktu

### 3. Troubleshooting

Jika terdapat masalah dengan status booking yang tidak terupdate otomatis:

1. **Periksa Timezone Server**:

   - Pastikan timezone server diatur ke `Asia/Jakarta`
   - Cek konfigurasi di `app/Config/App.php`: `$appTimezone = 'Asia/Jakarta'`

2. **Jalankan CLI Command**:

   - Gunakan perintah `php spark booking:check-expired --force --booking=KODE_BOOKING`
   - Parameter `--force` memaksa update tanpa memeriksa waktu

3. **Gunakan Direct Script**:

   - Jalankan `php direct_update.php`
   - Script akan menampilkan semua booking pending dan memberikan opsi untuk mengupdate

4. **Periksa Log Sistem**:
   - Cek file log di `writable/logs/` untuk informasi error

### 4. Konfigurasi Cron Job

Untuk memastikan sistem berjalan otomatis, tambahkan cron job berikut pada server:

```
# Cek booking expired setiap 5 menit
*/5 * * * * curl -s "https://yourdomain.com/cron/check-expired-bookings?key=awanbarbershop_secret_key" > /dev/null 2>&1
```

### 5. Catatan Penting

- Pastikan waktu server sesuai dengan waktu lokal Indonesia
- Jika WebSocket server tidak berjalan, sistem akan menggunakan AJAX polling sebagai fallback
- Untuk debugging, gunakan parameter `?key=awanbarbershop_secret_key` pada semua endpoint cron

## Menjalankan WebSocket Server

Untuk menjalankan WebSocket server, gunakan perintah berikut:

```bash
php spark websocket:start
```

Server akan berjalan pada port 8080 secara default. Pastikan port ini terbuka di firewall server Anda.

## Debugging

Untuk debugging booking expired, gunakan perintah berikut:

```bash
# Cek semua booking yang sudah expired
php spark booking:check-expired

# Cek dan force update status booking tertentu
php spark booking:check-expired --booking KODE_BOOKING --force
```

## Update Terbaru: Penanganan Otomatis Booking Expired

Sistem terbaru telah diimplementasikan untuk menangani booking expired secara otomatis, tanpa memerlukan interaksi pengguna:

### 1. Event-driven Background Processing

Sistem sekarang menjalankan pengecekan booking expired secara otomatis pada setiap 5% request halaman (lihat `app/Config/Events.php`). Ini memastikan bahwa booking yang sudah expired akan diperbarui statusnya meskipun tidak ada pengguna yang mengaksesnya.

### 2. Scheduled Task Windows (Task Scheduler)

File batch `automate_booking_cleanup.bat` telah dibuat untuk dijalankan melalui Windows Task Scheduler:

- Jadwal: Setiap 5 menit
- Command: `C:\path\to\awanbarbershop\automate_booking_cleanup.bat`
- Output: Log tersimpan di `writable\logs\booking_cleanup_batch.log`

### 3. Script Standalone dengan Parameter

Script `update_expired.php` telah diperbarui untuk mendukung parameter command line:

- `--force`: Memaksa pembaruan tanpa memeriksa waktu expired
- `--booking=KODE`: Memeriksa booking tertentu saja
- `--debug`: Menampilkan informasi debug

Contoh penggunaan:

```
php update_expired.php --force --debug
php update_expired.php --booking=BK-20250624-0001
```

### 4. Integrasi dengan Controller Events

Event listener pada `post_controller_constructor` memastikan bahwa booking expired diperiksa secara otomatis pada setiap permintaan halaman, dengan throttling untuk menghindari beban server berlebihan.
