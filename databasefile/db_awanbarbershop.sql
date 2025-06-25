/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - db_awanbarbershop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_awanbarbershop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_awanbarbershop`;

/*Table structure for table `booking` */

DROP TABLE IF EXISTS `booking`;

CREATE TABLE `booking` (
  `kdbooking` char(20) COLLATE utf8mb4_general_ci NOT NULL,
  `idpelanggan` char(20) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `jenispembayaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlahbayar` double NOT NULL DEFAULT '0',
  `total` char(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idkaryawan` char(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_booking` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdbooking`),
  KEY `booking_idpelanggan_foreign` (`idpelanggan`),
  KEY `booking_idkaryawan_foreign` (`idkaryawan`),
  CONSTRAINT `booking_idkaryawan_foreign` FOREIGN KEY (`idkaryawan`) REFERENCES `karyawan` (`idkaryawan`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `booking_idpelanggan_foreign` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `booking` */

insert  into `booking`(`kdbooking`,`idpelanggan`,`status`,`jenispembayaran`,`jumlahbayar`,`total`,`idkaryawan`,`tanggal_booking`,`created_at`,`updated_at`,`expired_at`) values 
('BK-20250624-0001','PLG0001','expired','Belum Bayar',0,'100000.00','KRY001','2025-06-24','2025-06-24 10:29:22','2025-06-24 10:34:25','2025-06-24 10:34:22'),
('BK-20250624-0002','PLG0001','expired','Belum Bayar',0,'150000.00','KRY003','2025-06-24','2025-06-24 10:40:39','2025-06-24 10:41:15','2025-06-24 10:41:10'),
('BK-20250624-0003','PLG0001','expired','Belum Bayar',0,'50000.00','KRY002','2025-06-24','2025-06-24 10:45:58','2025-06-24 11:00:50','2025-06-24 10:50:58'),
('BK-20250624-0004','PLG0001','expired','Belum Bayar',0,'100000.00','KRY002','2025-06-24','2025-06-24 11:01:10','2025-06-24 11:06:13','2025-06-24 11:06:10'),
('BK-20250624-0005','PLG0001','expired','Belum Bayar',0,'150000.00','KRY001','2025-06-24','2025-06-24 11:22:11','2025-06-24 11:23:11','2025-06-24 11:23:11'),
('BK-20250624-0006','PLG0001','confirmed','Lunas',100000,'100000.00','KRY001','2025-06-24','2025-06-24 14:28:42','2025-06-24 14:29:29','2025-06-24 14:33:42'),
('BK-20250624-0007','PLG0001','confirmed','DP',75000,'150000.00','KRY003','2025-06-24','2025-06-24 14:35:02','2025-06-24 14:36:00','2025-06-24 14:40:02'),
('BK-20250624-0008','PLG0001','expired','Belum Bayar',0,'100000.00','KRY002','2025-06-24','2025-06-24 14:45:25','2025-06-24 14:50:30','2025-06-24 14:50:25'),
('BK-20250624-0009','PLG0001','completed','Lunas',150000,'150000.00','KRY002','2025-06-24','2025-06-24 15:30:27','2025-06-24 15:33:30','2025-06-24 15:35:26'),
('BK-20250625-0001','PLG0003','expired','Belum Bayar',0,'100000.00','KRY001','2025-06-26','2025-06-25 23:05:38','2025-06-25 23:07:15','2025-06-25 23:07:15'),
('BK-20250625-0002','PLG0003','completed','Lunas',100000,'100000.00','KRY001','2025-06-26','2025-06-25 23:08:38','2025-06-25 23:13:52','2025-06-25 23:13:38'),
('BK-20250625-0003','PLG0003','pending','Lunas',100000,'100000.00','KRY003','2025-06-26','2025-06-25 23:14:40','2025-06-25 23:14:57','2025-06-25 23:19:40'),
('BK-20250625-0004','PLG0003','pending','DP',25000,'50000.00','KRY002','2025-06-26','2025-06-25 23:15:13','2025-06-25 23:15:29','2025-06-25 23:20:13'),
('BK-20250625-0005','PLG0004','completed','Lunas',150000,'150000.00','KRY001','2025-06-26','2025-06-25 23:18:48','2025-06-25 23:19:26',NULL);

/*Table structure for table `detail_booking` */

DROP TABLE IF EXISTS `detail_booking`;

CREATE TABLE `detail_booking` (
  `iddetail` char(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `kdbooking` char(20) COLLATE utf8mb4_general_ci NOT NULL,
  `kdpaket` char(25) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_paket` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga` double NOT NULL DEFAULT '0',
  `jamstart` time NOT NULL,
  `jamend` time NOT NULL,
  `status` enum('1','2','3','4') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `idkaryawan` char(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `detail_booking_kdbooking_foreign` (`kdbooking`),
  KEY `detail_booking_kdpaket_foreign` (`kdpaket`),
  KEY `detail_booking_idkaryawan_foreign` (`idkaryawan`),
  CONSTRAINT `detail_booking_idkaryawan_foreign` FOREIGN KEY (`idkaryawan`) REFERENCES `karyawan` (`idkaryawan`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `detail_booking_kdbooking_foreign` FOREIGN KEY (`kdbooking`) REFERENCES `booking` (`kdbooking`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_booking_kdpaket_foreign` FOREIGN KEY (`kdpaket`) REFERENCES `paket` (`idpaket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detail_booking` */

insert  into `detail_booking`(`iddetail`,`tgl`,`kdbooking`,`kdpaket`,`nama_paket`,`deskripsi`,`harga`,`jamstart`,`jamend`,`status`,`idkaryawan`,`created_at`,`updated_at`) values 
('DTL-20250624-0001','2025-06-24','BK-20250624-0001','PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000,'11:00:00','12:00:00','','KRY001','2025-06-24 10:29:22','2025-06-24 10:34:25'),
('DTL-20250624-0002','2025-06-24','BK-20250624-0002','PKT003','Complete Treatment','Potong rambut, keramas, facial, pijat kepala, dan styling premium',150000,'11:00:00','12:00:00','','KRY003','2025-06-24 10:40:39','2025-06-24 10:41:15'),
('DTL-20250624-0003','2025-06-24','BK-20250624-0003','PKT001','Basic Haircut','Potong rambut dasar dengan styling sederhana',50000,'11:00:00','12:00:00','','KRY002','2025-06-24 10:45:58','2025-06-24 11:00:50'),
('DTL-20250624-0004','2025-06-24','BK-20250624-0004','PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000,'12:00:00','13:00:00','','KRY002','2025-06-24 11:01:10','2025-06-24 11:06:13'),
('DTL-20250624-0005','2025-06-24','BK-20250624-0005','PKT003','Complete Treatment','Potong rambut, keramas, facial, pijat kepala, dan styling premium',150000,'12:00:00','13:00:00','','KRY001','2025-06-24 11:22:11','2025-06-24 11:23:11'),
('DTL-20250624-0006','2025-06-24','BK-20250624-0006','PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000,'15:00:00','16:00:00','1','KRY001','2025-06-24 14:28:42','2025-06-24 14:28:42'),
('DTL-20250624-0007','2025-06-24','BK-20250624-0007','PKT003','Complete Treatment','Potong rambut, keramas, facial, pijat kepala, dan styling premium',150000,'15:00:00','16:00:00','2','KRY003','2025-06-24 14:35:02','2025-06-24 14:36:00'),
('DTL-20250624-0008','2025-06-24','BK-20250624-0008','PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000,'15:00:00','16:00:00','','KRY002','2025-06-24 14:45:25','2025-06-24 14:50:30'),
('DTL-20250624-0009','2025-06-24','BK-20250624-0009','PKT003','Complete Treatment','Potong rambut, keramas, facial, pijat kepala, dan styling premium',150000,'16:00:00','17:00:00','2','KRY002','2025-06-24 15:30:27','2025-06-24 15:30:56'),
('DTL-20250625-0001','2025-06-26','BK-20250625-0001','PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000,'09:00:00','10:00:00','','KRY001','2025-06-25 23:05:38','2025-06-25 23:07:15'),
('DTL-20250625-0002','2025-06-26','BK-20250625-0002','PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000,'09:00:00','10:00:00','2','KRY001','2025-06-25 23:08:38','2025-06-25 23:08:57'),
('DTL-20250625-0003','2025-06-26','BK-20250625-0003','PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000,'09:00:00','10:00:00','2','KRY003','2025-06-25 23:14:40','2025-06-25 23:14:57'),
('DTL-20250625-0004','2025-06-26','BK-20250625-0004','PKT001','Basic Haircut','Potong rambut dasar dengan styling sederhana',50000,'09:00:00','10:00:00','2','KRY002','2025-06-25 23:15:13','2025-06-25 23:15:29'),
('DTL-20250625-0005','2025-06-26','BK-20250625-0005','PKT003','Complete Treatment','Potong rambut, keramas, facial, pijat kepala, dan styling premium',150000,'10:00:00','11:00:00','2','KRY001','2025-06-25 23:18:48','2025-06-25 23:18:48');

/*Table structure for table `detail_pembayaran` */

DROP TABLE IF EXISTS `detail_pembayaran`;

CREATE TABLE `detail_pembayaran` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `kdbayar` int unsigned NOT NULL,
  `total_bayar` double NOT NULL DEFAULT '0',
  `grandtotal` double NOT NULL DEFAULT '0',
  `metode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'cash',
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'paid',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_pembayaran_kdbayar_foreign` (`kdbayar`),
  CONSTRAINT `detail_pembayaran_kdbayar_foreign` FOREIGN KEY (`kdbayar`) REFERENCES `pembayaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detail_pembayaran` */

/*Table structure for table `karyawan` */

DROP TABLE IF EXISTS `karyawan`;

CREATE TABLE `karyawan` (
  `idkaryawan` char(10) COLLATE utf8mb4_general_ci NOT NULL,
  `namakaryawan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `nohp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idkaryawan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `karyawan` */

insert  into `karyawan`(`idkaryawan`,`namakaryawan`,`alamat`,`nohp`,`status`,`created_at`,`updated_at`) values 
('KRY001','Budi Santoso','Jl. Mawar No. 10, Jakarta Selatan','081234567890','aktif',NULL,NULL),
('KRY002','Dewi Lestari','Jl. Melati No. 15, Jakarta Pusat','082345678901','aktif',NULL,NULL),
('KRY003','Ahmad Rizki','Jl. Anggrek No. 20, Jakarta Timur','083456789012','aktif',NULL,NULL);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(1,'2025-05-14-175340','App\\Database\\Migrations\\CreateUsersTable','default','App',1747245454,1),
(2,'2025-05-14-175341','App\\Database\\Migrations\\CreateKaryawanTable','default','App',1747245454,1),
(3,'2025-05-14-175342','App\\Database\\Migrations\\CreatePelangganTable','default','App',1747245454,1),
(4,'2025-05-14-175343','App\\Database\\Migrations\\CreatePaketTable','default','App',1747245454,1),
(5,'2025-05-14-175344','App\\Database\\Migrations\\CreateOtpTable','default','App',1747245454,1),
(6,'2025-06-16-084745','App\\Database\\Migrations\\CreateBookingTables','default','App',1750063731,2),
(7,'2025-06-17-171531','App\\Database\\Migrations\\AddStatusToKaryawan','default','App',1750180559,3),
(8,'2025-06-18-120332','App\\Database\\Migrations\\AddJenisToPembayaran','default','App',1750248240,4),
(9,'2025-06-19-070341','App\\Database\\Migrations\\AddBuktiToPembayaran','default','App',1750316679,5),
(11,'2025-06-20-000001','App\\Database\\Migrations\\CreateNotificationsTable','default','App',1750410479,6),
(12,'2025-06-21-000001','App\\Database\\Migrations\\CreatePengeluaranTable','default','App',1750662375,7),
(13,'2025-06-23-000001','App\\Database\\Migrations\\AddExpiredAtToBooking','default','App',1750667720,8);

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'jenis notifikasi: booking_baru, pembayaran, dll',
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `reference_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'kdbooking, idfaktur, atau ID lain yang terkait',
  `idpelanggan` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'ID pelanggan yang terkait dengan notifikasi',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=unread, 1=read',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idpelanggan` (`idpelanggan`),
  KEY `is_read` (`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notifications` */

insert  into `notifications`(`id`,`type`,`title`,`message`,`reference_id`,`idpelanggan`,`is_read`,`created_at`,`updated_at`) values 
(21,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0002','BK-20250624-0002','PLG0001',1,'2025-06-24 05:16:47','2025-06-24 05:16:47'),
(22,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0003','BK-20250624-0003','PLG0001',1,'2025-06-24 09:31:23','2025-06-24 09:31:23'),
(23,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0004','BK-20250624-0004','PLG0001',1,'2025-06-24 09:48:01','2025-06-24 09:48:01'),
(24,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0001','BK-20250624-0001','PLG0001',1,'2025-06-24 10:02:53','2025-06-24 10:02:53'),
(25,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0002','BK-20250624-0002','PLG0001',1,'2025-06-24 10:14:56','2025-06-24 10:14:56'),
(26,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0001','BK-20250624-0001','PLG0001',1,'2025-06-24 10:29:22','2025-06-24 10:29:22'),
(27,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0002','BK-20250624-0002','PLG0001',1,'2025-06-24 10:40:39','2025-06-24 10:40:39'),
(28,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0003','BK-20250624-0003','PLG0001',1,'2025-06-24 10:45:58','2025-06-24 10:45:58'),
(29,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0004','BK-20250624-0004','PLG0001',1,'2025-06-24 11:01:10','2025-06-24 11:01:10'),
(30,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0005','BK-20250624-0005','PLG0001',1,'2025-06-24 11:22:11','2025-06-24 11:22:11'),
(31,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0006','BK-20250624-0006','PLG0001',1,'2025-06-24 14:28:42','2025-06-24 14:28:42'),
(32,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250624-0006 telah diterima dan menunggu konfirmasi.','BK-20250624-0006',NULL,1,'2025-06-24 14:29:29','2025-06-24 14:29:29'),
(33,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0007','BK-20250624-0007','PLG0001',1,'2025-06-24 14:35:02','2025-06-24 14:35:02'),
(34,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250624-0007 telah diterima dan menunggu konfirmasi.','BK-20250624-0007',NULL,1,'2025-06-24 14:36:00','2025-06-24 14:36:00'),
(35,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0008','BK-20250624-0008','PLG0001',1,'2025-06-24 14:45:25','2025-06-24 14:45:25'),
(36,'booking_baru','Booking Baru','Booking baru oleh Budiman dengan kode BK-20250624-0009','BK-20250624-0009','PLG0001',1,'2025-06-24 15:30:27','2025-06-24 15:31:30'),
(37,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250624-0009 telah diterima dan menunggu konfirmasi.','BK-20250624-0009',NULL,1,'2025-06-24 15:30:56','2025-06-24 15:31:22'),
(38,'booking_baru','Booking Baru','Booking baru oleh Alex Putra dengan kode BK-20250625-0001','BK-20250625-0001','PLG0003',1,'2025-06-25 23:05:38','2025-06-25 23:07:07'),
(39,'booking_baru','Booking Baru','Booking baru oleh Alex Putra dengan kode BK-20250625-0002','BK-20250625-0002','PLG0003',1,'2025-06-25 23:08:38','2025-06-25 23:09:28'),
(40,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250625-0002 telah diterima dan menunggu konfirmasi.','BK-20250625-0002',NULL,0,'2025-06-25 23:08:57','2025-06-25 23:08:57'),
(41,'booking_baru','Booking Baru','Booking baru oleh Alex Putra dengan kode BK-20250625-0003','BK-20250625-0003','PLG0003',0,'2025-06-25 23:14:40','2025-06-25 23:14:40'),
(42,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250625-0003 telah diterima dan menunggu konfirmasi.','BK-20250625-0003',NULL,0,'2025-06-25 23:14:57','2025-06-25 23:14:57'),
(43,'booking_baru','Booking Baru','Booking baru oleh Alex Putra dengan kode BK-20250625-0004','BK-20250625-0004','PLG0003',0,'2025-06-25 23:15:13','2025-06-25 23:15:13'),
(44,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250625-0004 telah diterima dan menunggu konfirmasi.','BK-20250625-0004',NULL,0,'2025-06-25 23:15:29','2025-06-25 23:15:29');

/*Table structure for table `otp` */

DROP TABLE IF EXISTS `otp`;

CREATE TABLE `otp` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('registration','reset_password') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'registration',
  `expired_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otp_user_id_foreign` (`user_id`),
  CONSTRAINT `otp_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `otp` */

insert  into `otp`(`id`,`user_id`,`otp_code`,`type`,`expired_at`,`created_at`,`updated_at`) values 
(9,59,'041559','registration','2025-05-14 19:15:14','2025-05-14 19:00:14','2025-05-14 19:00:14');

/*Table structure for table `paket` */

DROP TABLE IF EXISTS `paket`;

CREATE TABLE `paket` (
  `idpaket` char(10) COLLATE utf8mb4_general_ci NOT NULL,
  `namapaket` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `harga` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idpaket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `paket` */

insert  into `paket`(`idpaket`,`namapaket`,`deskripsi`,`harga`,`image`,`created_at`,`updated_at`) values 
('PKT001','Basic Haircut','Potong rambut dasar dengan styling sederhana',50000.00,'1750702351_7bc1235bd8677566f292.jpg','2025-05-14 17:57:47','2025-06-23 18:12:31'),
('PKT002','Premium Package','Potong rambut, keramas, pijat kepala, dan styling',100000.00,'1750702362_1060e3bb1a4bc9ce044f.jpg','2025-05-14 17:57:47','2025-06-23 18:12:42'),
('PKT003','Complete Treatment','Potong rambut, keramas, facial, pijat kepala, dan styling premium',150000.00,'1750702449_a23cda7aec6ed3fbbbfe.jpg','2025-05-14 17:57:47','2025-06-23 18:14:09');

/*Table structure for table `pelanggan` */

DROP TABLE IF EXISTS `pelanggan`;

CREATE TABLE `pelanggan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `idpelanggan` char(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jeniskelamin` enum('Laki-laki','Perempuan','-') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `no_hp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idpelanggan` (`idpelanggan`),
  KEY `pelanggan_user_id_foreign` (`user_id`),
  CONSTRAINT `pelanggan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pelanggan` */

insert  into `pelanggan`(`id`,`user_id`,`idpelanggan`,`nama_lengkap`,`jeniskelamin`,`alamat`,`no_hp`,`tanggal_lahir`,`created_at`,`updated_at`) values 
(107,4,'PLG0001','Budiman','Laki-laki','Padang','083182423488','2025-06-27','2025-06-19 17:52:00','2025-06-19 18:31:26'),
(108,5,'PLG0002','muklis','Laki-laki','Padang','0863547682748','2025-06-24','2025-06-24 03:37:48','2025-06-24 03:38:14'),
(109,6,'PLG0003','Alex Putra','Laki-laki','Padang','083783784783','2013-02-04','2025-06-25 23:01:21','2025-06-25 23:02:29'),
(110,7,'PLG0004','Latif','Laki-laki','Padang','083182423488','2025-06-11','2025-06-25 23:16:44','2025-06-25 23:18:03');

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fakturbooking` char(20) COLLATE utf8mb4_general_ci NOT NULL,
  `total_bayar` double NOT NULL DEFAULT '0',
  `grandtotal` double NOT NULL DEFAULT '0',
  `metode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'cash',
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'paid',
  `jenis` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Lunas',
  `bukti` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_fakturbooking_foreign` (`fakturbooking`),
  CONSTRAINT `pembayaran_fakturbooking_foreign` FOREIGN KEY (`fakturbooking`) REFERENCES `booking` (`kdbooking`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pembayaran` */

insert  into `pembayaran`(`id`,`fakturbooking`,`total_bayar`,`grandtotal`,`metode`,`status`,`jenis`,`bukti`,`created_at`,`updated_at`) values 
(26,'BK-20250624-0006',100000,100000,'transfer','pending','Lunas','BK-20250624-0006_1750750169_72c4105987ccfa26365b.jpg','2025-06-24 14:29:29','2025-06-24 14:29:29'),
(27,'BK-20250624-0007',75000,150000,'transfer','paid','DP','BK-20250624-0007_1750750560_19a40959429bfcc59930.jpg','2025-06-24 14:36:00','2025-06-24 14:36:00'),
(28,'BK-20250624-0009',75000,150000,'qris','paid','DP','BK-20250624-0009_1750753856_9eb1a11e1eb27d8ed874.jpg','2025-06-24 15:30:56','2025-06-24 15:33:30'),
(29,'BK-20250624-0009',75000,150000,'cash','paid','Pelunasan',NULL,'2025-06-24 15:33:30','2025-06-24 15:33:30'),
(31,'BK-20250625-0002',50000,100000,'transfer','paid','DP','BK-20250625-0002_1750867737_03bb55724fa75df571de.jpg','2025-06-25 23:08:57','2025-06-25 23:13:52'),
(32,'BK-20250625-0002',50000,100000,'cash','paid','Pelunasan',NULL,'2025-06-25 23:13:52','2025-06-25 23:13:52'),
(33,'BK-20250625-0002',50000,100000,'cash','paid','Pelunasan',NULL,'2025-06-25 23:13:52','2025-06-25 23:13:52'),
(34,'BK-20250625-0003',100000,100000,'transfer','paid','Lunas','BK-20250625-0003_1750868097_03780d6a194a0fd08735.jpg','2025-06-25 23:14:57','2025-06-25 23:14:57'),
(35,'BK-20250625-0004',25000,50000,'transfer','paid','DP','BK-20250625-0004_1750868129_41c3ec0518a8a47a68a7.jpg','2025-06-25 23:15:29','2025-06-25 23:15:29'),
(36,'BK-20250625-0005',150000,150000,'cash','paid','Lunas',NULL,'2025-06-25 23:18:48','2025-06-25 23:19:26');

/*Table structure for table `pengeluaran` */

DROP TABLE IF EXISTS `pengeluaran`;

CREATE TABLE `pengeluaran` (
  `idpengeluaran` char(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` double NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idpengeluaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengeluaran` */

insert  into `pengeluaran`(`idpengeluaran`,`tgl`,`keterangan`,`jumlah`,`created_at`,`updated_at`) values 
('PG-20250623-0001','2025-06-23','Pembayaran listrik',700000,'2025-06-23 07:07:03','2025-06-23 08:18:39'),
('PG-20250623-0002','2025-06-22','Pembelian alat cukur',750000,'2025-06-23 07:07:03','2025-06-23 07:07:03'),
('PG-20250623-0003','2025-06-23','Gaji Karyawan',10000000,'2025-06-23 08:19:31','2025-06-23 08:19:31');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'admin, user, dll',
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`name`,`role`,`status`,`last_login`,`remember_token`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'admin','admin@admin.com','$2y$10$q0diYPnm5cBnu664cMMd9ulmEgjW6hUZA2gILBEWA56OGygYVpW86','Administrator','admin','active','2025-06-25 22:58:01',NULL,'2025-06-19 09:29:55','2025-06-25 22:58:01',NULL),
(2,'pimpinan','pimpinan@example.com','$2y$10$bTxwlnQ9POewIutfJrho8edKKEuZvzbdesph2kcZ5/cd4//w.xX6u','Pimpinan','pimpinan','active',NULL,NULL,'2025-06-19 09:29:55','2025-06-19 09:29:55',NULL),
(4,'budiman','budiman@pingaja.site','$2y$10$8r86972Ct829.m5iDIyydO4EsHOlqBKpNmAaDtwF0BsaZ81qzBke.','Budiman','pelanggan','active','2025-06-24 14:28:26',NULL,'2025-06-19 17:50:58','2025-06-24 14:28:26',NULL),
(5,'muklis','muklis@pingaja.site','$2y$10$P4TsrM8o4nWA7WqggHD6JekBk7jLuCLb3.IE6we.4muaRgIyIwhvG','muklis','pelanggan','active','2025-06-24 03:37:56',NULL,'2025-06-24 03:37:29','2025-06-24 03:38:14',NULL),
(6,'alex','alexputra@pingaja.site','$2y$10$T4GNNm.7nzl9PvdrN.a7DOQfcHVZn/NaKZMyBvjknbej3X0/lK/Nu','Alex Putra','pelanggan','active','2025-06-25 23:01:31',NULL,'2025-06-25 23:00:56','2025-06-25 23:02:29',NULL),
(7,'latif','latif@gmail.com','$2y$10$nkhJojMfClVr/jMJiYMZW.wlnKzp83yxXpr.MwSMZq9yqeigev7Pq','Latif','pelanggan','active',NULL,NULL,'2025-06-25 23:18:03','2025-06-25 23:18:03',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
