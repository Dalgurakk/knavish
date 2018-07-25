/*
SQLyog Ultimate v8.5 
MySQL - 5.7.19 : Database - royal-manager
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`royal-manager` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `royal-manager`;

/*Table structure for table `car_brands` */

DROP TABLE IF EXISTS `car_brands`;

CREATE TABLE `car_brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `car_category_id` int(10) unsigned NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=269 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `car_brands` */

insert  into `car_brands`(`id`,`name`,`description`,`active`,`created_at`,`updated_at`,`car_category_id`,`image`) values (230,'Model 9','Model 9',1,'2018-06-10 21:14:29','2018-06-10 21:14:29',9,'5b1d94b5b3348.jpg'),(231,'Model 10','Model 10',1,'2018-06-10 21:14:47','2018-06-10 21:14:47',12,'5b1d94c7cad3c.jpg'),(232,'Model 11','Model 11',0,'2018-06-10 21:15:47','2018-06-10 21:21:21',8,'5b1d9503d4655.jpg'),(233,'Model 12','Model 12',1,'2018-06-10 21:15:57','2018-06-10 21:15:57',6,'5b1d950dd6073.jpg'),(234,'Model 13','Model 13',0,'2018-06-10 21:16:10','2018-06-10 21:21:29',5,'5b1d951a78d7f.jpg'),(235,'Model 14','Model 14',1,'2018-06-10 21:16:22','2018-06-10 21:16:22',12,'5b1d9526da761.jpg'),(236,'Model 15','Model 15',1,'2018-06-10 21:16:36','2018-06-10 21:17:06',9,'5b1d9552a1e88.jpg'),(254,'Model 2','Model 2',0,'2018-06-10 22:54:15','2018-07-19 01:43:33',6,'5b1dac176649d.jpg'),(225,'Model 4','Model 4',0,'2018-06-10 21:12:22','2018-06-10 21:21:52',9,'5b1d94369dcd5.jpg'),(226,'Model 5','Model 5',1,'2018-06-10 21:12:34','2018-06-10 21:12:34',12,'5b1d944245311.jpg'),(227,'Model 6','Model 6',1,'2018-06-10 21:12:47','2018-06-10 21:22:09',8,'5b1d944f1930e.jpg'),(228,'Model 7','Model 7',1,'2018-06-10 21:13:20','2018-06-10 21:13:20',6,'5b1d9470638f9.jpg'),(229,'Model 8','Model 8',0,'2018-06-10 21:13:48','2018-06-10 21:21:10',5,'5b1d948cc4b7e.jpg'),(224,'Model 3','Model 3',1,'2018-06-10 21:12:09','2018-06-10 21:21:45',5,'5b1d94295419a.jpg'),(222,'Model 1','Model 1',1,'2018-06-10 21:11:02','2018-06-10 21:11:02',8,'5b1d93e646292.jpg');

/*Table structure for table `car_categories` */

DROP TABLE IF EXISTS `car_categories`;

CREATE TABLE `car_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `car_categories` */

insert  into `car_categories`(`id`,`name`,`description`,`active`,`created_at`,`updated_at`) values (9,'Category 4','Category 4',1,'2018-05-21 02:03:24','2018-05-21 02:03:24'),(8,'Category 1','Category 1',1,'2018-05-21 01:57:03','2018-07-19 01:41:27'),(5,'Category 3','Category 3',1,'2018-05-20 05:46:12','2018-05-21 02:03:16'),(6,'Category 2','Category 2',1,'2018-05-20 05:51:01','2018-05-21 02:03:08'),(12,'Category 5','Category 5',1,'2018-06-09 19:49:11','2018-06-09 19:49:11');

/*Table structure for table `hotel_board_types` */

DROP TABLE IF EXISTS `hotel_board_types`;

CREATE TABLE `hotel_board_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_board_types` */

insert  into `hotel_board_types`(`id`,`name`,`description`,`code`,`active`,`created_at`,`updated_at`) values (9,'BED & BREAKFAST',NULL,'BB',1,'2018-07-17 22:58:19','2018-07-22 14:09:59'),(8,'ALL INCLUSIVE',NULL,'AI',1,'2018-07-17 22:57:58','2018-07-17 22:57:58');

/*Table structure for table `hotel_contract_board_type` */

DROP TABLE IF EXISTS `hotel_contract_board_type`;

CREATE TABLE `hotel_contract_board_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_contract_id` int(10) unsigned NOT NULL,
  `hotel_board_type_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_contract_board_type` */

insert  into `hotel_contract_board_type`(`id`,`hotel_contract_id`,`hotel_board_type_id`,`created_at`,`updated_at`) values (76,12,9,'2018-07-23 23:46:26','2018-07-23 23:46:26'),(80,16,8,'2018-07-24 05:48:06','2018-07-24 05:48:06'),(82,17,9,'2018-07-24 06:47:10','2018-07-24 06:47:10'),(78,14,8,'2018-07-24 04:41:50','2018-07-24 04:41:50');

/*Table structure for table `hotel_contract_pax_type` */

DROP TABLE IF EXISTS `hotel_contract_pax_type`;

CREATE TABLE `hotel_contract_pax_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_contract_id` int(10) unsigned NOT NULL,
  `hotel_pax_type_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_contract_pax_type` */

insert  into `hotel_contract_pax_type`(`id`,`hotel_contract_id`,`hotel_pax_type_id`,`created_at`,`updated_at`) values (88,16,21,'2018-07-24 05:48:06','2018-07-24 05:48:06'),(91,17,21,'2018-07-24 06:46:10','2018-07-24 06:46:10'),(90,17,20,'2018-07-24 06:46:10','2018-07-24 06:46:10'),(85,14,19,'2018-07-23 22:09:17','2018-07-23 22:09:17'),(83,12,21,'2018-07-23 07:38:08','2018-07-23 07:38:08'),(87,16,20,'2018-07-24 05:48:06','2018-07-24 05:48:06');

/*Table structure for table `hotel_contract_room_type` */

DROP TABLE IF EXISTS `hotel_contract_room_type`;

CREATE TABLE `hotel_contract_room_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_contract_id` int(10) unsigned NOT NULL,
  `hotel_room_type_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_contract_room_type` */

insert  into `hotel_contract_room_type`(`id`,`hotel_contract_id`,`hotel_room_type_id`,`created_at`,`updated_at`) values (79,17,2,'2018-07-24 06:46:10','2018-07-24 06:46:10'),(69,12,22,'2018-07-23 07:38:08','2018-07-23 07:38:08'),(77,17,22,'2018-07-24 06:46:10','2018-07-24 06:46:10'),(80,14,22,'2018-07-24 21:12:38','2018-07-24 21:12:38'),(75,16,22,'2018-07-24 05:48:06','2018-07-24 05:48:06'),(78,17,4,'2018-07-24 06:46:10','2018-07-24 06:46:10'),(73,12,4,'2018-07-23 23:47:01','2018-07-23 23:47:01'),(72,14,19,'2018-07-23 22:09:17','2018-07-23 22:09:17'),(70,12,19,'2018-07-23 07:38:08','2018-07-23 07:38:08');

/*Table structure for table `hotel_contracts` */

DROP TABLE IF EXISTS `hotel_contracts`;

CREATE TABLE `hotel_contracts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hotel_id` int(10) unsigned NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_contracts` */

insert  into `hotel_contracts`(`id`,`name`,`hotel_id`,`valid_from`,`valid_to`,`active`,`created_at`,`updated_at`,`status`) values (12,'Contrato Melia Varadero',1,'2018-01-01','2018-12-31',0,'2018-07-23 07:38:08','2018-07-24 05:58:58',0),(14,'Contrato Club Kawama',64,'2018-07-01','2018-07-30',0,'2018-07-23 22:09:17','2018-07-24 21:12:38',0),(16,'Contrato Melia Santiago',8,'2018-01-25','2018-11-01',0,'2018-07-24 05:48:06','2018-07-24 05:53:17',0),(17,'Iberoestar 2018',6,'2018-01-01','2018-12-01',0,'2018-07-24 06:46:10','2018-07-24 06:47:10',0);

/*Table structure for table `hotel_hotels_chain` */

DROP TABLE IF EXISTS `hotel_hotels_chain`;

CREATE TABLE `hotel_hotels_chain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_hotels_chain` */

insert  into `hotel_hotels_chain`(`id`,`name`,`description`,`active`,`created_at`,`updated_at`) values (2,'Sol Meliá',NULL,1,'2018-06-26 03:58:10','2018-06-26 03:58:10'),(3,'Habaguanex',NULL,0,'2018-06-26 03:58:22','2018-06-26 05:16:54'),(4,'Gran Caribe',NULL,1,'2018-06-26 03:58:33','2018-06-26 03:58:33'),(5,'Occidental Hotels & Resorts',NULL,1,'2018-06-26 03:59:14','2018-06-26 04:00:36'),(6,'BLAU Hotels & Resorts',NULL,1,'2018-06-26 04:00:22','2018-06-26 05:19:15'),(7,'Iberoestar Hotels & Resorts',NULL,1,'2018-06-26 04:01:13','2018-06-26 04:01:13'),(8,'Barceló Hotels & Resorts',NULL,1,'2018-06-26 04:01:31','2018-06-26 19:51:20'),(9,'Hoteles NH',NULL,0,'2018-06-26 04:01:43','2018-06-26 05:17:04'),(10,'Oásis',NULL,1,'2018-06-26 04:01:53','2018-06-26 04:01:53'),(11,'Hotetur',NULL,0,'2018-06-26 04:02:02','2018-06-26 05:17:09'),(12,'Sandals',NULL,1,'2018-06-26 04:02:10','2018-06-26 04:02:10'),(13,'Memories Resorts & SPA',NULL,1,'2018-06-26 04:02:49','2018-06-26 05:19:02'),(14,'Cubanacan',NULL,1,'2018-06-26 04:02:56','2018-06-26 04:02:56'),(15,'Hoteles C',NULL,0,'2018-06-26 04:03:08','2018-06-26 05:16:58'),(16,'H10 Hotels',NULL,1,'2018-06-26 04:03:24','2018-06-26 04:03:24'),(17,'ROC Hotels',NULL,0,'2018-06-26 04:03:34','2018-06-26 05:17:45'),(18,'Be Live Hotels',NULL,0,'2018-06-26 04:04:04','2018-06-26 05:16:41'),(19,'Gaviota',NULL,1,'2018-06-26 04:04:12','2018-06-26 04:04:12'),(20,'Valentin Hotels',NULL,0,'2018-06-26 04:04:20','2018-06-26 05:17:53'),(21,'Islazul',NULL,1,'2018-06-26 04:04:28','2018-06-26 04:04:28'),(22,'Accor',NULL,0,'2018-06-26 04:04:40','2018-06-26 05:16:09'),(23,'Superclubs',NULL,0,'2018-06-26 04:04:50','2018-06-26 05:18:01'),(24,'Golden Tulip Hotels',NULL,0,'2018-06-26 04:05:41','2018-06-26 04:05:41'),(25,'Pestana Hoteles y Resorts',NULL,0,'2018-06-26 04:06:24','2018-06-26 04:06:24'),(26,'RIU Hoteles & Resorts',NULL,0,'2018-06-26 04:06:45','2018-06-26 05:27:34'),(27,'Kempinski Hotels S.A.',NULL,0,'2018-06-26 04:06:58','2018-06-26 04:06:58'),(28,'Starwood Hotels & Resorts',NULL,0,'2018-06-26 04:07:15','2018-06-26 04:07:15'),(29,'Hotusa',NULL,0,'2018-06-26 04:07:42','2018-06-26 04:07:42'),(30,'Eurostars Hoteles',NULL,0,'2018-06-26 04:08:01','2018-06-26 04:08:01');

/*Table structure for table `hotel_images` */

DROP TABLE IF EXISTS `hotel_images`;

CREATE TABLE `hotel_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(10) unsigned NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `size` int(11) NOT NULL,
  `mime` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=271 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_images` */

insert  into `hotel_images`(`id`,`hotel_id`,`image`,`name`,`created_at`,`updated_at`,`size`,`mime`) values (267,1,'5b5501a183083.jpg','3.jpg','2018-07-22 22:13:53','2018-07-22 22:13:53',87081,'image/jpeg'),(266,7,'5b55017e499a6.jpg','8.jpg','2018-07-22 22:13:18','2018-07-22 22:13:18',107718,'image/jpeg'),(265,60,'5b54ca8f6d3fa.jpg','7.jpg','2018-07-22 18:18:55','2018-07-22 18:18:55',238431,'image/jpeg'),(264,60,'5b54ca8f68a2d.jpg','5.jpg','2018-07-22 18:18:55','2018-07-22 18:18:55',172653,'image/jpeg'),(263,60,'5b54ca7ea3b18.jpg','8.jpg','2018-07-22 18:18:38','2018-07-22 18:18:38',107718,'image/jpeg'),(262,9,'5b54a33055889.jpg','5.jpg','2018-07-22 15:30:56','2018-07-22 15:30:56',172653,'image/jpeg'),(261,9,'5b54a33049634.jpg','8.jpg','2018-07-22 15:30:56','2018-07-22 15:30:56',107718,'image/jpeg'),(260,1,'5b54a3211c40b.jpg','9.jpg','2018-07-22 15:30:41','2018-07-22 15:30:41',527546,'image/jpeg'),(258,5,'5b54a3154f3ee.jpg','7.jpg','2018-07-22 15:30:29','2018-07-22 15:30:29',238431,'image/jpeg'),(259,1,'5b54a3211ce37.jpg','8.jpg','2018-07-22 15:30:41','2018-07-22 15:30:41',107718,'image/jpeg'),(257,5,'5b54a315430e5.jpg','8.jpg','2018-07-22 15:30:29','2018-07-22 15:30:29',107718,'image/jpeg'),(256,8,'5b54a307c8939.jpg','7.jpg','2018-07-22 15:30:15','2018-07-22 15:30:15',238431,'image/jpeg'),(255,8,'5b54a307c7339.jpg','5.jpg','2018-07-22 15:30:15','2018-07-22 15:30:15',172653,'image/jpeg'),(253,7,'5b54a2f31f8a7.jpg','5.jpg','2018-07-22 15:29:55','2018-07-22 15:29:55',172653,'image/jpeg'),(247,10,'5b54a2c17151c.jpg','8.jpg','2018-07-22 15:29:05','2018-07-22 15:29:05',107718,'image/jpeg'),(248,10,'5b54a2c16dfc1.jpg','9.jpg','2018-07-22 15:29:05','2018-07-22 15:29:05',527546,'image/jpeg'),(249,4,'5b54a2cd17093.jpg','3.jpg','2018-07-22 15:29:17','2018-07-22 15:29:17',87081,'image/jpeg'),(250,4,'5b54a2cd1a3d7.jpg','2.jpg','2018-07-22 15:29:17','2018-07-22 15:29:17',125175,'image/jpeg'),(254,7,'5b54a2f327e31.jpg','4.jpg','2018-07-22 15:29:55','2018-07-22 15:29:55',82416,'image/jpeg'),(246,6,'5b54a2b424d12.jpg','7.jpg','2018-07-22 15:28:52','2018-07-22 15:28:52',238431,'image/jpeg'),(238,49,'5b54a29f0a88f.jpg','2.jpg','2018-07-22 15:28:31','2018-07-22 15:28:31',125175,'image/jpeg'),(239,49,'5b54a29f0abb2.jpg','3.jpg','2018-07-22 15:28:31','2018-07-22 15:28:31',87081,'image/jpeg'),(240,49,'5b54a29f0a896.jpg','4.jpg','2018-07-22 15:28:31','2018-07-22 15:28:31',82416,'image/jpeg'),(241,6,'5b54a2b3b8ac1.jpg','2.jpg','2018-07-22 15:28:51','2018-07-22 15:28:51',125175,'image/jpeg'),(242,6,'5b54a2b3d4ad5.jpg','4.jpg','2018-07-22 15:28:52','2018-07-22 15:28:52',82416,'image/jpeg'),(243,6,'5b54a2b3e2ade.jpg','3.jpg','2018-07-22 15:28:52','2018-07-22 15:28:52',87081,'image/jpeg'),(244,6,'5b54a2b406d76.jpg','8.jpg','2018-07-22 15:28:52','2018-07-22 15:28:52',107718,'image/jpeg'),(245,6,'5b54a2b414c84.jpg','5.jpg','2018-07-22 15:28:52','2018-07-22 15:28:52',172653,'image/jpeg'),(268,1,'5b5501a18a2c2.jpg','2.jpg','2018-07-22 22:13:53','2018-07-22 22:13:53',125175,'image/jpeg'),(269,64,'5b56d231282c8.jpg','2.jpg','2018-07-24 07:16:01','2018-07-24 07:16:01',125175,'image/jpeg'),(270,64,'5b56d2312bf34.jpg','8.jpg','2018-07-24 07:16:01','2018-07-24 07:16:01',107718,'image/jpeg');

/*Table structure for table `hotel_pax_types` */

DROP TABLE IF EXISTS `hotel_pax_types`;

CREATE TABLE `hotel_pax_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agefrom` double NOT NULL DEFAULT '0',
  `ageto` double NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_pax_types` */

insert  into `hotel_pax_types`(`id`,`name`,`description`,`code`,`agefrom`,`ageto`,`active`,`created_at`,`updated_at`) values (21,'Adult',NULL,'AD',12,99,1,'2018-07-17 22:57:16','2018-07-17 22:57:16'),(20,'Children',NULL,'CH',2,11.99,1,'2018-07-17 22:57:01','2018-07-17 22:57:01'),(19,'Infant',NULL,'BB',0,1.99,1,'2018-07-17 22:56:39','2018-07-22 15:41:03');

/*Table structure for table `hotel_room_types` */

DROP TABLE IF EXISTS `hotel_room_types`;

CREATE TABLE `hotel_room_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maxpax` int(11) NOT NULL DEFAULT '0',
  `minpax` int(11) NOT NULL DEFAULT '0',
  `minadult` int(11) NOT NULL DEFAULT '0',
  `minchildren` int(11) NOT NULL DEFAULT '0',
  `maxinfant` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotel_room_types` */

insert  into `hotel_room_types`(`id`,`name`,`description`,`code`,`maxpax`,`minpax`,`minadult`,`minchildren`,`maxinfant`,`active`,`created_at`,`updated_at`) values (2,'BUNGALOW SUPERIOR 2 PAX',NULL,'XH2',2,1,1,1,1,1,'2018-06-18 00:37:26','2018-06-18 00:50:37'),(27,'DOUBLE/TWIN',NULL,'RX8',2,2,2,0,1,1,'2018-07-22 14:24:44','2018-07-22 14:24:44'),(4,'BUNGALOW SUPERIOR 2AD+ 1CHD',NULL,'G7Y',3,3,2,2,1,1,'2018-06-18 00:50:27','2018-06-18 02:49:50'),(6,'BUNGALOW SUPERIOR SINGLE',NULL,'MZU',1,1,1,0,1,1,'2018-06-18 01:16:14','2018-06-18 01:16:14'),(20,'TRIPLE',NULL,'TBL',3,3,3,0,1,1,'2018-07-15 16:23:54','2018-07-22 14:08:20'),(23,'BUNGALOW SUPERIOR 3AD',NULL,'DRL',3,3,3,0,1,1,'2018-07-17 23:00:12','2018-07-17 23:00:12'),(22,'DOUBLE',NULL,'DBL',2,2,2,0,1,1,'2018-07-17 22:58:52','2018-07-22 14:25:10'),(19,'SINGLE',NULL,'SGL',1,1,1,0,1,1,'2018-07-14 22:51:00','2018-07-22 13:59:38'),(28,'DOUBLE BASIC (2AD+1CH)',NULL,'PG3',2,2,2,0,1,1,'2018-07-22 14:28:57','2018-07-22 14:29:03');

/*Table structure for table `hotels` */

DROP TABLE IF EXISTS `hotels`;

CREATE TABLE `hotels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `country_id` int(11) DEFAULT '0',
  `state_id` int(11) DEFAULT '0',
  `city_id` int(11) DEFAULT '0',
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hotel_chain_id` int(11) DEFAULT NULL,
  `admin_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_fax` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_site` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `turistic_licence` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hotels` */

insert  into `hotels`(`id`,`name`,`description`,`active`,`country_id`,`state_id`,`city_id`,`postal_code`,`address`,`category`,`hotel_chain_id`,`admin_phone`,`admin_fax`,`web_site`,`turistic_licence`,`created_at`,`updated_at`,`email`) values (1,'Hotel Meliá Varadero',NULL,1,74,143,144,'50000','Ave 158 e/ 250 and 252','5',2,'53520831','53928992','http://melia.com','1234567890','2018-06-27 01:55:18','2018-07-23 18:15:15','melia@gmail.com'),(4,'Hotel Iberostar Grand Hotel Trinidad',NULL,1,74,143,144,'20400','Fourth Ave 238','5',7,'5353520831','5353928992','http://trinidad.com','1234567890','2018-06-27 04:43:10','2018-07-23 16:23:28','trinidad@gmail.com'),(5,'Hotel Palacio Azul',NULL,1,74,107,111,'60500','Autopista Pinar del Rio km 2 1/2','4',19,'123456789','123456789','http://viñales.com','1234567890','2018-06-27 04:43:57','2018-07-23 16:19:50','viñales@gmail.com'),(6,'Hotel Habana Riviera by Iberostar',NULL,1,74,143,145,'20200','First Avenue','5',7,'53928992','53520831','http://iberoestar.com','123456789','2018-06-27 04:44:21','2018-07-23 16:18:26','iberostar@gmail.com'),(7,'Hotel Iberostar Playa Alameda',NULL,1,74,143,144,'50000','Second Ave 899','5',7,'5353520831','5353928992','http://alameda.com','1234567890','2018-06-27 04:44:56','2018-07-23 16:22:49','alameda@gmail.com'),(8,'Hotel Meliá Santiago',NULL,1,74,159,363,'10500','Ave 958 e/ 250 and 33','5',2,'5353520831','5353928992','http://meliasantiago.com','1234567890','2018-06-27 04:46:08','2018-07-23 16:22:04','meliasantiago@gmail.com'),(9,'Hotel Mercure Sevilla',NULL,1,74,131,135,'30000','Ave 258 e/ 250 and 22','4',4,'53520831','53928992','http://sevilla.com','1234567890','2018-06-27 04:46:40','2018-07-23 16:20:52','sevilla@gmail.com'),(49,'Hotel Abac',NULL,1,74,159,363,'10400','First Ave 238','5',13,'5353520831','5353928992','http://hotelabac.com','1234567890','2018-07-18 16:55:30','2018-07-22 19:56:29','hotelabac@gmail.com'),(64,'Hotel Club Kawama',NULL,1,74,159,163,'20400','Ave 3 #238','3',14,'123456789','123456789','http://clubkawama.com','123456789','2018-07-23 04:21:15','2018-07-23 16:18:19','kawama@gmail.com');

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `_lft` int(10) unsigned NOT NULL DEFAULT '0',
  `_rgt` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned DEFAULT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `locations__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=366 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `locations` */

insert  into `locations`(`id`,`name`,`code`,`active`,`created_at`,`updated_at`,`_lft`,`_rgt`,`parent_id`,`icon`) values (74,'Cuba','CUB',1,'2018-06-22 05:25:28','2018-06-25 18:30:19',1,370,NULL,'glyphicon glyphicon-globe'),(125,'San Antonio','06',1,'2018-06-22 07:22:25','2018-06-22 07:22:25',37,38,119,'glyphicon glyphicon-globe'),(124,'Bauta','05',1,'2018-06-22 07:22:15','2018-06-22 07:22:15',35,36,119,'glyphicon glyphicon-globe'),(123,'Caimito','04',1,'2018-06-22 07:22:06','2018-06-22 07:22:06',33,34,119,'glyphicon glyphicon-globe'),(122,'Guanajay','03',1,'2018-06-22 07:21:58','2018-06-22 07:21:58',31,32,119,'glyphicon glyphicon-globe'),(121,'Mariel','02',1,'2018-06-22 07:21:48','2018-06-22 07:21:48',29,30,119,'glyphicon glyphicon-globe'),(120,'Bahía Honda','01',1,'2018-06-22 07:21:37','2018-06-22 07:21:37',27,28,119,'glyphicon glyphicon-globe'),(119,'Artemisa',NULL,1,'2018-06-22 07:21:17','2018-06-22 07:21:17',26,49,74,'glyphicon glyphicon-globe'),(118,'Guane','14',1,'2018-06-22 07:20:34','2018-06-22 07:20:34',23,24,107,'glyphicon glyphicon-globe'),(117,'San Juan y Martínez','13',1,'2018-06-22 07:20:25','2018-06-22 07:20:25',21,22,107,'glyphicon glyphicon-globe'),(116,'San Luis','12',1,'2018-06-22 07:20:15','2018-06-22 07:20:15',19,20,107,'glyphicon glyphicon-globe'),(115,'Pinar del Río','11',1,'2018-06-22 07:20:03','2018-06-22 07:20:03',17,18,107,'glyphicon glyphicon-globe'),(114,'Consolación del Sur','10',1,'2018-06-22 07:19:53','2018-06-22 07:19:53',15,16,107,'glyphicon glyphicon-globe'),(113,'Los Palacios','09',1,'2018-06-22 07:19:43','2018-06-22 07:19:43',13,14,107,'glyphicon glyphicon-globe'),(112,'La Palma','05',1,'2018-06-22 07:19:27','2018-06-22 07:19:27',11,12,107,'glyphicon glyphicon-globe'),(111,'Viñales','04',1,'2018-06-22 07:19:17','2018-06-22 07:19:17',9,10,107,'glyphicon glyphicon-globe'),(110,'Minas de Matahambre','03',1,'2018-06-22 07:19:08','2018-06-22 07:19:08',7,8,107,'glyphicon glyphicon-globe'),(109,'Mantua','02',1,'2018-06-22 07:18:58','2018-06-25 03:17:03',5,6,107,'glyphicon glyphicon-globe'),(108,'Sandino','01',1,'2018-06-22 07:18:46','2018-06-22 07:18:46',3,4,107,'glyphicon glyphicon-globe'),(107,'Pinar del Río',NULL,1,'2018-06-22 07:18:23','2018-06-22 07:18:23',2,25,74,'glyphicon glyphicon-globe'),(126,'Güira de Melena','07',1,'2018-06-22 07:22:35','2018-06-22 07:22:35',39,40,119,'glyphicon glyphicon-globe'),(127,'Alquízar','08',1,'2018-06-22 07:22:44','2018-06-22 07:22:44',41,42,119,'glyphicon glyphicon-globe'),(128,'Artemisa','09',1,'2018-06-22 07:22:54','2018-06-22 07:22:54',43,44,119,'glyphicon glyphicon-globe'),(129,'Candelaria','10',1,'2018-06-22 07:23:03','2018-06-22 07:23:03',45,46,119,'glyphicon glyphicon-globe'),(130,'San Cristóbal','11',1,'2018-06-22 07:23:14','2018-06-22 07:23:14',47,48,119,'glyphicon glyphicon-globe'),(131,'Mayabeque',NULL,1,'2018-06-22 07:23:45','2018-06-22 07:23:45',50,73,74,'glyphicon glyphicon-globe'),(132,'Bejucal','01',1,'2018-06-22 07:24:05','2018-06-22 07:24:05',51,52,131,'glyphicon glyphicon-globe'),(133,'San José de las Lajas','02',1,'2018-06-22 07:24:14','2018-06-22 07:24:14',53,54,131,'glyphicon glyphicon-globe'),(134,'Jaruco','03',1,'2018-06-22 07:24:22','2018-06-22 07:24:22',55,56,131,'glyphicon glyphicon-globe'),(135,'Santa Cruz del Norte','04',1,'2018-06-22 07:24:31','2018-06-22 07:24:31',57,58,131,'glyphicon glyphicon-globe'),(136,'Madruga','05',1,'2018-06-22 07:24:45','2018-06-22 07:24:45',59,60,131,'glyphicon glyphicon-globe'),(137,'Nueva Paz','06',1,'2018-06-22 07:24:55','2018-06-22 07:24:55',61,62,131,'glyphicon glyphicon-globe'),(138,'San Nicolás','07',1,'2018-06-22 07:25:05','2018-06-22 07:25:05',63,64,131,'glyphicon glyphicon-globe'),(139,'Güines','08',1,'2018-06-22 07:25:13','2018-06-22 07:25:13',65,66,131,'glyphicon glyphicon-globe'),(140,'Melena del Sur','09',1,'2018-06-22 07:25:27','2018-06-22 07:25:27',67,68,131,'glyphicon glyphicon-globe'),(141,'Batabanó','10',1,'2018-06-22 07:25:36','2018-06-22 07:25:36',69,70,131,'glyphicon glyphicon-globe'),(142,'Quivicán','11',1,'2018-06-22 07:25:46','2018-06-22 07:25:46',71,72,131,'glyphicon glyphicon-globe'),(143,'La Habana',NULL,1,'2018-06-22 07:26:08','2018-06-22 07:26:08',74,105,74,'glyphicon glyphicon-globe'),(144,'Playa','01',1,'2018-06-22 07:26:25','2018-06-22 07:26:25',75,76,143,'glyphicon glyphicon-globe'),(145,'Plaza de la Revolución','02',1,'2018-06-22 07:26:34','2018-06-22 07:26:34',77,78,143,'glyphicon glyphicon-globe'),(146,'Centro Habana','03',1,'2018-06-22 07:26:43','2018-06-22 07:26:43',79,80,143,'glyphicon glyphicon-globe'),(147,'Habana Vieja','04',1,'2018-06-22 07:26:53','2018-06-22 07:26:53',81,82,143,'glyphicon glyphicon-globe'),(148,'Regla','05',1,'2018-06-22 07:27:02','2018-06-22 07:27:02',83,84,143,'glyphicon glyphicon-globe'),(149,'Habana del Este','06',1,'2018-06-22 07:27:12','2018-06-22 07:27:12',85,86,143,'glyphicon glyphicon-globe'),(150,'Guanabacoa','07',1,'2018-06-22 07:27:20','2018-06-22 07:27:20',87,88,143,'glyphicon glyphicon-globe'),(151,'San Miguel del Padrón','08',1,'2018-06-22 07:27:29','2018-06-22 07:27:29',89,90,143,'glyphicon glyphicon-globe'),(152,'Diez de Octubre','09',1,'2018-06-22 07:27:39','2018-06-22 07:27:39',91,92,143,'glyphicon glyphicon-globe'),(153,'Cerro','10',1,'2018-06-22 07:27:48','2018-06-22 07:27:48',93,94,143,'glyphicon glyphicon-globe'),(154,'Marianao','11',1,'2018-06-22 07:27:56','2018-06-22 07:27:56',95,96,143,'glyphicon glyphicon-globe'),(155,'La Lisa','12',1,'2018-06-22 07:28:06','2018-06-22 07:28:06',97,98,143,'glyphicon glyphicon-globe'),(156,'Boyeros','13',1,'2018-06-22 07:28:15','2018-06-22 07:28:15',99,100,143,'glyphicon glyphicon-globe'),(157,'Arroyo Naranjo','14',1,'2018-06-22 07:28:24','2018-06-22 07:28:24',101,102,143,'glyphicon glyphicon-globe'),(158,'Cotorro','15',1,'2018-06-22 07:28:31','2018-06-22 07:28:31',103,104,143,'glyphicon glyphicon-globe'),(159,'Matanzas',NULL,1,'2018-06-22 07:28:56','2018-06-22 07:28:56',106,135,74,'glyphicon glyphicon-globe'),(160,'Matanzas','01',1,'2018-06-22 07:29:04','2018-06-22 07:29:04',107,108,159,'glyphicon glyphicon-globe'),(161,'Cárdenas','02',1,'2018-06-22 07:30:23','2018-06-22 07:30:23',109,112,159,'glyphicon glyphicon-globe'),(162,'Marti','04',1,'2018-06-22 07:30:35','2018-06-22 07:30:35',113,114,159,'glyphicon glyphicon-globe'),(163,'Colón','05',1,'2018-06-22 07:30:45','2018-06-22 07:30:45',115,116,159,'glyphicon glyphicon-globe'),(164,'Perico','06',1,'2018-06-22 07:30:55','2018-06-22 07:30:55',117,118,159,'glyphicon glyphicon-globe'),(165,'Jovellanos','07',1,'2018-06-22 07:31:03','2018-06-22 07:31:03',119,120,159,'glyphicon glyphicon-globe'),(166,'Pedro Betancourt','08',1,'2018-06-22 07:31:12','2018-06-22 07:31:12',121,122,159,'glyphicon glyphicon-globe'),(167,'Limonar','09',1,'2018-06-22 07:31:20','2018-06-22 07:31:20',123,124,159,'glyphicon glyphicon-globe'),(168,'Unión de Reyes','10',1,'2018-06-22 07:31:30','2018-06-22 07:31:30',125,126,159,'glyphicon glyphicon-globe'),(169,'Ciénaga de Zapata','11',1,'2018-06-22 07:31:39','2018-06-22 07:31:39',127,128,159,'glyphicon glyphicon-globe'),(170,'Jagüey Grande','12',1,'2018-06-22 07:31:50','2018-06-22 07:31:50',129,130,159,'glyphicon glyphicon-globe'),(171,'Calimete','13',1,'2018-06-22 07:31:59','2018-06-22 07:31:59',131,132,159,'glyphicon glyphicon-globe'),(172,'Los Arabos','14',1,'2018-06-22 07:32:08','2018-06-22 19:04:51',133,134,159,'glyphicon glyphicon-globe'),(173,'Villa Clara',NULL,1,'2018-06-22 07:32:41','2018-06-24 21:59:40',136,163,74,'glyphicon glyphicon-globe'),(174,'Corralillo','01',1,'2018-06-22 07:32:59','2018-06-22 07:32:59',137,138,173,'glyphicon glyphicon-globe'),(175,'Quemado de Güines','02',1,'2018-06-22 07:33:10','2018-06-22 07:33:10',139,140,173,'glyphicon glyphicon-globe'),(176,'Sagua la Grande','03',1,'2018-06-22 07:33:19','2018-06-22 07:33:19',141,142,173,'glyphicon glyphicon-globe'),(177,'Encrucijada','04',1,'2018-06-22 07:33:29','2018-06-22 07:33:29',143,144,173,'glyphicon glyphicon-globe'),(178,'Camajuaní','05',1,'2018-06-22 07:33:38','2018-06-22 07:33:38',145,146,173,'glyphicon glyphicon-globe'),(179,'Caibarién','06',1,'2018-06-22 07:33:46','2018-06-22 07:33:46',147,148,173,'glyphicon glyphicon-globe'),(180,'Remedios','07',1,'2018-06-22 07:33:55','2018-06-22 07:33:55',149,150,173,'glyphicon glyphicon-globe'),(181,'Placetas','08',1,'2018-06-22 07:34:03','2018-06-22 07:34:03',151,152,173,'glyphicon glyphicon-globe'),(182,'Santa Clara','09',1,'2018-06-22 07:34:13','2018-06-22 07:34:13',153,154,173,'glyphicon glyphicon-globe'),(183,'Cifuentes','10',1,'2018-06-22 07:34:31','2018-06-22 07:34:31',155,156,173,'glyphicon glyphicon-globe'),(184,'Santo Domingo','11',1,'2018-06-22 07:34:56','2018-06-22 07:34:56',157,158,173,'glyphicon glyphicon-globe'),(185,'Ranchuelo','12',1,'2018-06-22 07:35:05','2018-06-22 07:35:05',159,160,173,'glyphicon glyphicon-globe'),(186,'Manicaragua','13',1,'2018-06-22 07:35:14','2018-06-22 07:35:14',161,162,173,'glyphicon glyphicon-globe'),(187,'Cienfuegos',NULL,1,'2018-06-22 07:35:36','2018-06-22 07:35:36',164,181,74,'glyphicon glyphicon-globe'),(188,'Aguada de Pasajeros','01',1,'2018-06-22 07:35:50','2018-06-22 07:35:50',165,166,187,'glyphicon glyphicon-globe'),(189,'Rodas','02',1,'2018-06-22 07:35:59','2018-06-22 07:35:59',167,168,187,'glyphicon glyphicon-globe'),(190,'Palmira','03',1,'2018-06-22 07:36:08','2018-06-22 07:36:08',169,170,187,'glyphicon glyphicon-globe'),(191,'Lajas','04',1,'2018-06-22 07:36:19','2018-06-22 07:36:19',171,172,187,'glyphicon glyphicon-globe'),(192,'Abreus','05',1,'2018-06-22 07:36:28','2018-06-22 07:36:28',173,174,187,'glyphicon glyphicon-globe'),(193,'Cumanayagua','06',1,'2018-06-22 07:36:38','2018-06-22 07:36:38',175,176,187,'glyphicon glyphicon-globe'),(194,'Cienfuegos','07',1,'2018-06-22 07:36:47','2018-06-22 07:36:47',177,178,187,'glyphicon glyphicon-globe'),(195,'Cruces','08',1,'2018-06-22 07:36:56','2018-06-22 07:36:56',179,180,187,'glyphicon glyphicon-globe'),(196,'Sancti Spíritus',NULL,1,'2018-06-22 07:37:11','2018-06-22 07:37:11',182,199,74,'glyphicon glyphicon-globe'),(197,'Yaguajay','01',1,'2018-06-22 07:37:24','2018-06-22 07:37:24',183,184,196,'glyphicon glyphicon-globe'),(198,'Jatibonico','02',1,'2018-06-22 07:37:35','2018-06-22 07:37:35',185,186,196,'glyphicon glyphicon-globe'),(199,'Taguasco','03',1,'2018-06-22 07:37:45','2018-06-22 07:37:45',187,188,196,'glyphicon glyphicon-globe'),(200,'Cabaiguán','04',1,'2018-06-22 07:37:54','2018-06-22 07:37:54',189,190,196,'glyphicon glyphicon-globe'),(201,'Fomento','05',1,'2018-06-22 07:38:03','2018-06-22 07:38:03',191,192,196,'glyphicon glyphicon-globe'),(202,'Trinidad','06',1,'2018-06-22 07:38:11','2018-06-22 07:38:11',193,194,196,'glyphicon glyphicon-globe'),(203,'Sancti Spíritus','07',1,'2018-06-22 07:38:28','2018-06-22 07:38:28',195,196,196,'glyphicon glyphicon-globe'),(204,'La Sierpe','08',1,'2018-06-22 07:38:39','2018-06-22 07:38:39',197,198,196,'glyphicon glyphicon-globe'),(205,'Ciego de Ávila',NULL,1,'2018-06-22 07:38:56','2018-06-22 07:38:56',200,221,74,'glyphicon glyphicon-globe'),(206,'Chambas','01',1,'2018-06-22 07:39:10','2018-06-22 07:39:10',201,202,205,'glyphicon glyphicon-globe'),(207,'Morón','02',1,'2018-06-22 07:39:34','2018-06-22 07:39:34',203,204,205,'glyphicon glyphicon-globe'),(208,'Bolivia','03',1,'2018-06-22 07:39:43','2018-06-22 07:39:43',205,206,205,'glyphicon glyphicon-globe'),(209,'Primero de Enero','04',1,'2018-06-22 07:39:53','2018-06-22 07:39:53',207,208,205,'glyphicon glyphicon-globe'),(210,'Ciro Redondo','05',1,'2018-06-22 07:40:01','2018-06-22 07:40:01',209,210,205,'glyphicon glyphicon-globe'),(211,'Florencia','06',1,'2018-06-22 07:40:08','2018-06-22 07:40:08',211,212,205,'glyphicon glyphicon-globe'),(212,'Majagua','07',1,'2018-06-22 07:40:15','2018-06-22 07:40:15',213,214,205,'glyphicon glyphicon-globe'),(213,'Ciego de Ávila','08',1,'2018-06-22 07:40:24','2018-06-22 07:40:24',215,216,205,'glyphicon glyphicon-globe'),(214,'Venezuela','09',1,'2018-06-22 07:40:31','2018-06-22 07:40:31',217,218,205,'glyphicon glyphicon-globe'),(215,'Baraguá','10',1,'2018-06-22 07:40:38','2018-06-22 07:40:38',219,220,205,'glyphicon glyphicon-globe'),(216,'Camagüey',NULL,1,'2018-06-22 07:40:58','2018-06-22 07:40:58',222,249,74,'glyphicon glyphicon-globe'),(217,'Carlos Manuel de Céspedes','01',1,'2018-06-22 07:41:17','2018-06-22 07:41:17',223,224,216,'glyphicon glyphicon-globe'),(218,'Esmeralda','02',1,'2018-06-22 07:41:25','2018-06-22 07:41:25',225,226,216,'glyphicon glyphicon-globe'),(219,'Sierra de Cubitas','03',1,'2018-06-22 07:41:35','2018-06-22 07:41:35',227,228,216,'glyphicon glyphicon-globe'),(220,'Minas','04',1,'2018-06-22 07:41:42','2018-06-22 07:41:42',229,230,216,'glyphicon glyphicon-globe'),(221,'Nuevitas','05',1,'2018-06-22 07:41:49','2018-06-22 07:41:49',231,232,216,'glyphicon glyphicon-globe'),(222,'Guáimaro','06',1,'2018-06-22 07:41:56','2018-06-22 07:41:56',233,234,216,'glyphicon glyphicon-globe'),(223,'Sibanicú','07',1,'2018-06-22 07:42:04','2018-06-22 07:42:04',235,236,216,'glyphicon glyphicon-globe'),(224,'Camagüey','08',1,'2018-06-22 07:42:11','2018-06-22 07:42:11',237,238,216,'glyphicon glyphicon-globe'),(225,'Florida','09',1,'2018-06-22 07:42:22','2018-06-22 07:42:22',239,240,216,'glyphicon glyphicon-globe'),(226,'Vertientes','10',1,'2018-06-22 07:42:31','2018-06-22 07:42:31',241,242,216,'glyphicon glyphicon-globe'),(227,'Jimaguayú','11',1,'2018-06-22 07:42:39','2018-06-22 07:42:39',243,244,216,'glyphicon glyphicon-globe'),(228,'Najasa','12',1,'2018-06-22 07:42:46','2018-06-22 07:42:46',245,246,216,'glyphicon glyphicon-globe'),(229,'Santa Cruz del Sur','13',1,'2018-06-22 07:42:54','2018-06-22 07:42:54',247,248,216,'glyphicon glyphicon-globe'),(230,'Las Tunas',NULL,1,'2018-06-22 07:43:09','2018-06-22 07:43:09',250,267,74,'glyphicon glyphicon-globe'),(231,'Manatí','01',1,'2018-06-22 07:43:23','2018-06-22 07:43:23',251,252,230,'glyphicon glyphicon-globe'),(232,'Puerto Padre','02',1,'2018-06-22 07:43:31','2018-06-22 07:43:31',253,254,230,'glyphicon glyphicon-globe'),(233,'Jesús Menéndez','03',1,'2018-06-22 07:43:40','2018-06-22 07:43:40',255,256,230,'glyphicon glyphicon-globe'),(234,'Majibacoa','04',1,'2018-06-22 07:43:47','2018-06-22 07:43:47',257,258,230,'glyphicon glyphicon-globe'),(235,'Las Tunas','05',1,'2018-06-22 07:43:57','2018-06-22 07:43:57',259,260,230,'glyphicon glyphicon-globe'),(236,'Jobabo','06',1,'2018-06-22 07:44:03','2018-06-22 07:44:03',261,262,230,'glyphicon glyphicon-globe'),(237,'Colombia','07',1,'2018-06-22 07:44:10','2018-06-22 07:44:10',263,264,230,'glyphicon glyphicon-globe'),(238,'Amancio','08',1,'2018-06-22 07:44:17','2018-06-22 07:44:17',265,266,230,'glyphicon glyphicon-globe'),(239,'Holguín',NULL,1,'2018-06-22 07:44:31','2018-06-22 07:44:31',268,297,74,'glyphicon glyphicon-globe'),(240,'Gibara','01',1,'2018-06-22 07:44:48','2018-06-22 07:44:48',269,270,239,'glyphicon glyphicon-globe'),(241,'Rafael Freyre','02',1,'2018-06-22 07:44:57','2018-06-22 07:44:57',271,272,239,'glyphicon glyphicon-globe'),(242,'Banes','03',1,'2018-06-22 07:45:04','2018-06-22 07:45:04',273,274,239,'glyphicon glyphicon-globe'),(243,'Antilla','04',1,'2018-06-22 07:45:12','2018-06-22 07:45:12',275,276,239,'glyphicon glyphicon-globe'),(244,'Báguanos','05',1,'2018-06-22 07:45:20','2018-06-22 07:45:20',277,278,239,'glyphicon glyphicon-globe'),(245,'Holguín','06',1,'2018-06-22 07:45:28','2018-06-22 07:45:28',279,280,239,'glyphicon glyphicon-globe'),(246,'Calixto García','07',1,'2018-06-22 07:45:36','2018-06-22 07:45:36',281,282,239,'glyphicon glyphicon-globe'),(247,'Cacocúm','08',1,'2018-06-22 07:45:43','2018-06-22 07:45:43',283,284,239,'glyphicon glyphicon-globe'),(248,'Urbano Noris','09',1,'2018-06-22 07:45:51','2018-06-22 07:45:51',285,286,239,'glyphicon glyphicon-globe'),(249,'Cueto','10',1,'2018-06-22 07:45:58','2018-06-22 07:45:58',287,288,239,'glyphicon glyphicon-globe'),(250,'Mayarí','11',1,'2018-06-22 07:46:06','2018-06-22 07:46:06',289,290,239,'glyphicon glyphicon-globe'),(251,'Frank País','12',1,'2018-06-22 07:46:15','2018-06-22 07:46:15',291,292,239,'glyphicon glyphicon-globe'),(252,'Sagua de Tánamo','13',1,'2018-06-22 07:46:24','2018-06-22 07:46:24',293,294,239,'glyphicon glyphicon-globe'),(253,'Moa','14',1,'2018-06-22 07:46:32','2018-06-22 07:46:32',295,296,239,'glyphicon glyphicon-globe'),(254,'Granma',NULL,1,'2018-06-22 07:46:46','2018-06-22 07:46:46',298,325,74,'glyphicon glyphicon-globe'),(255,'Río Cauto','01',1,'2018-06-22 07:47:08','2018-06-22 07:47:08',299,300,254,'glyphicon glyphicon-globe'),(256,'Cauto Cristo','02',1,'2018-06-22 07:47:17','2018-06-22 07:47:17',301,302,254,'glyphicon glyphicon-globe'),(257,'Jiguaní','03',1,'2018-06-22 07:47:40','2018-06-22 07:47:40',303,304,254,'glyphicon glyphicon-globe'),(258,'Bayamo','04',1,'2018-06-22 07:47:48','2018-06-22 07:47:48',305,306,254,'glyphicon glyphicon-globe'),(259,'Yara','05',1,'2018-06-22 07:47:57','2018-06-22 07:47:57',307,308,254,'glyphicon glyphicon-globe'),(260,'Manzanillo','06',1,'2018-06-22 07:48:03','2018-06-22 07:48:03',309,310,254,'glyphicon glyphicon-globe'),(261,'Campechuela','07',1,'2018-06-22 07:48:10','2018-06-22 07:48:10',311,312,254,'glyphicon glyphicon-globe'),(262,'Media Luna','08',1,'2018-06-22 07:48:19','2018-06-22 07:48:19',313,314,254,'glyphicon glyphicon-globe'),(263,'Niquero','09',1,'2018-06-22 07:48:27','2018-06-22 07:48:27',315,316,254,'glyphicon glyphicon-globe'),(264,'Pilón','10',1,'2018-06-22 07:48:34','2018-06-22 07:48:34',317,318,254,'glyphicon glyphicon-globe'),(265,'Bartolomé Masó','11',1,'2018-06-22 07:48:43','2018-06-22 07:48:43',319,320,254,'glyphicon glyphicon-globe'),(266,'Buey Arriba','12',1,'2018-06-22 07:48:55','2018-06-22 07:48:55',321,322,254,'glyphicon glyphicon-globe'),(267,'Guisa','13',1,'2018-06-22 07:49:05','2018-06-22 07:49:05',323,324,254,'glyphicon glyphicon-globe'),(268,'Santiago de Cuba',NULL,1,'2018-06-22 07:49:25','2018-06-22 07:49:25',326,345,74,'glyphicon glyphicon-globe'),(269,'Contramaestre','01',1,'2018-06-22 07:49:43','2018-06-22 07:49:43',327,328,268,'glyphicon glyphicon-globe'),(270,'Mella','02',1,'2018-06-22 07:49:49','2018-06-22 07:49:49',329,330,268,'glyphicon glyphicon-globe'),(271,'San Luis','03',1,'2018-06-22 07:49:59','2018-06-22 07:49:59',331,332,268,'glyphicon glyphicon-globe'),(272,'Segundo Frente','04',1,'2018-06-22 07:50:08','2018-06-22 07:50:08',333,334,268,'glyphicon glyphicon-globe'),(273,'Songo - La Maya','05',1,'2018-06-22 07:50:17','2018-06-22 07:50:17',335,336,268,'glyphicon glyphicon-globe'),(274,'Santiago de Cuba','06',1,'2018-06-22 07:50:26','2018-06-22 07:50:26',337,338,268,'glyphicon glyphicon-globe'),(275,'Palma Soriano','07',1,'2018-06-22 07:50:36','2018-06-22 07:50:36',339,340,268,'glyphicon glyphicon-globe'),(276,'Tercer Frente','08',1,'2018-06-22 07:50:44','2018-06-22 07:50:44',341,342,268,'glyphicon glyphicon-globe'),(277,'Guamá','09',1,'2018-06-22 07:50:54','2018-06-22 07:50:54',343,344,268,'glyphicon glyphicon-globe'),(278,'Guantánamo',NULL,1,'2018-06-22 07:51:16','2018-06-22 07:51:16',346,367,74,'glyphicon glyphicon-globe'),(279,'El Salvador','01',1,'2018-06-22 07:51:31','2018-06-22 07:51:31',347,348,278,'glyphicon glyphicon-globe'),(280,'Guantánamo','02',1,'2018-06-22 07:51:39','2018-06-22 07:51:39',349,350,278,'glyphicon glyphicon-globe'),(281,'Yateras','03',1,'2018-06-22 07:51:46','2018-06-22 07:51:46',351,352,278,'glyphicon glyphicon-globe'),(282,'Baracoa','04',1,'2018-06-22 07:51:53','2018-06-22 07:51:53',353,354,278,'glyphicon glyphicon-globe'),(283,'Maisí','05',1,'2018-06-22 07:52:00','2018-06-22 07:52:00',355,356,278,'glyphicon glyphicon-globe'),(284,'Imías','06',1,'2018-06-22 07:52:07','2018-06-22 07:52:07',357,358,278,'glyphicon glyphicon-globe'),(285,'San Antonio del Sur','07',1,'2018-06-22 07:52:17','2018-06-22 07:52:17',359,360,278,'glyphicon glyphicon-globe'),(286,'Manuel Tames','08',1,'2018-06-22 07:52:26','2018-06-22 07:52:26',361,362,278,'glyphicon glyphicon-globe'),(287,'Caimanera','09',1,'2018-06-22 07:52:33','2018-06-22 07:52:33',363,364,278,'glyphicon glyphicon-globe'),(288,'Niceto Pérez','10',1,'2018-06-22 07:52:45','2018-06-22 07:52:45',365,366,278,'glyphicon glyphicon-globe'),(289,'Isla de la Juventud','99',1,'2018-06-22 07:53:22','2018-06-25 00:27:51',368,369,74,'glyphicon glyphicon-globe'),(350,'Madrid','01',1,'2018-06-24 17:04:49','2018-06-24 20:49:33',376,377,349,'glyphicon glyphicon-globe'),(351,'Barcelona','02',0,'2018-06-24 20:47:52','2018-06-24 20:49:51',378,379,349,'glyphicon glyphicon-globe'),(363,'Varadero','01',1,'2018-06-27 23:28:56','2018-06-27 23:28:56',110,111,161,'glyphicon glyphicon-globe'),(364,'Roma','01',1,'2018-06-29 04:07:58','2018-06-29 04:07:58',372,373,348,'glyphicon glyphicon-road'),(349,'España','ESP',0,'2018-06-24 06:11:14','2018-07-14 17:30:05',375,380,NULL,'glyphicon glyphicon-star-empty'),(348,'Italia','ITA',0,'2018-06-24 06:11:05','2018-06-25 18:34:49',371,374,NULL,'glyphicon glyphicon-leaf');

/*Table structure for table `markets` */

DROP TABLE IF EXISTS `markets`;

CREATE TABLE `markets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `markets` */

insert  into `markets`(`id`,`name`,`description`,`code`,`active`,`created_at`,`updated_at`) values (1,'German',NULL,'GER',1,'2018-07-24 22:33:56','2018-07-24 22:34:19'),(5,'Italian',NULL,'ITA',1,'2018-07-24 23:14:56','2018-07-24 23:14:56'),(3,'Spanish',NULL,'ESP',1,'2018-07-24 22:34:58','2018-07-24 22:36:19'),(4,'English',NULL,'ING',1,'2018-07-24 22:35:48','2018-07-24 22:35:48');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (13,'2014_10_12_000000_create_users_table',1),(14,'2014_10_12_100000_create_password_resets_table',1),(15,'2018_05_05_003827_create_roles_table',1),(16,'2018_05_05_004029_create_role_user_table',1),(17,'2018_05_05_104909_add_username_to_users',1),(18,'2018_05_19_021658_add_active_to_users',2),(22,'2018_05_20_001156_create_brands_table',3),(23,'2018_05_20_045836_create_table_categories',4),(25,'2018_05_24_232152_update_description_type',5),(26,'2018_06_06_190644_add_category_id_to_brands',6),(27,'2018_06_06_193221_add_image_to_brands',7),(28,'2018_06_16_050516_rename_car_tables',8),(29,'2018_06_16_053034_car_brands_rename_category_id',9),(30,'2018_06_16_170131_create_table_hotel_pax_types',10),(31,'2018_06_17_042713_create_table_hotel_room_types',11),(36,'2018_06_19_003337_create_table_hotel_board_types',12),(37,'2018_06_19_193826_create_table_locations',12),(38,'2018_06_22_035525_add_icon_to_locations',13),(40,'2018_06_22_061440_update_locations_set_code_nullable',14),(41,'2018_06_26_033222_create_table_hotels_chain',15),(42,'2018_06_26_204953_create_table_hotels',16),(43,'2018_06_27_043328_update_hotels_set_locations_nullable',17),(45,'2018_07_13_051222_update_hotel_pax_types_set_age_to_decimal',18),(49,'2018_07_17_220906_create_hotel_hotel_board_type',19),(50,'2018_07_17_221215_create_hotel_hotel_room_type',19),(51,'2018_07_17_221230_create_hotel_hotel_pax_type',19),(52,'2018_07_19_155155_create_hotel_hotel_image',20),(53,'2018_07_20_051344_add_size_to_hotel_image',21),(54,'2018_07_20_052853_add_mime_to_hotel_image',22),(55,'2018_07_21_053948_add_email_to_hotels',23),(56,'2018_07_22_192447_create_table_hotel_contracts',24),(58,'2018_07_22_211857_rename_tables_associated_with_contracts',25),(60,'2018_07_23_050606_update_contract_id_fields',26),(61,'2018_07_23_180635_update_hotel_contracts_add_status',27),(62,'2018_07_24_221632_create_table_markets',28),(69,'2018_07_24_230512_update_users_add_market_id',29);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_user` */

insert  into `role_user`(`id`,`role_id`,`user_id`,`created_at`,`updated_at`) values (2,1,2,'2018-05-05 11:03:01','2018-05-05 11:03:01'),(19,2,39,'2018-05-12 02:07:42','2018-05-12 02:07:42'),(20,1,40,'2018-05-12 02:09:13','2018-05-12 02:09:13'),(81,3,97,'2018-07-25 04:12:59','2018-07-25 04:12:59'),(89,3,98,'2018-07-25 05:12:42','2018-07-25 05:12:42'),(46,1,63,'2018-05-14 02:20:49','2018-05-14 02:20:49'),(87,1,83,'2018-07-25 05:08:09','2018-07-25 05:08:09'),(88,3,99,'2018-07-25 05:11:57','2018-07-25 05:11:57'),(72,2,88,'2018-05-19 19:15:30','2018-05-19 19:15:30');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`description`,`created_at`,`updated_at`) values (1,'administrator','Administrator','2018-05-05 11:03:01','2018-05-05 11:03:01'),(2,'commercial','Commercial','2018-05-05 11:03:01','2018-05-05 11:03:01'),(3,'client','Client',NULL,NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `market_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`remember_token`,`created_at`,`updated_at`,`username`,`active`,`market_id`) values (2,'Admin','admin@example.com','$2y$10$FAfFfWPJ62MNRNAgd16oTu3dxsBjkuc14j8k.jy5qIOSh2gQcT63G','akzqxD2GDAh4Po2VnYfv3umueEn4XTmaQvcXXrTP39MZcfrsbTRqz8D0jl6v','2018-05-05 11:03:01','2018-07-22 14:44:42','admin',1,0),(39,'Yairis','yairis@rtt.com','$2y$10$xKCmjhYRiLbqTiEbCAkeJeRv31zYKDNuqeD0HZMv/mVsVHjsLbWsq',NULL,'2018-05-12 02:07:42','2018-07-22 14:44:12','yairis',1,0),(40,'Ariadna','ariadna@rtt.com','$2y$10$Th6szVzluWg852NkXqH3YuYv/d2dqRElowhifMRUIYvOGLOa6kpxe',NULL,'2018-05-12 02:09:13','2018-07-25 05:02:04','ariadna',1,0),(63,'Osvaldo','osvaldo@rtt.com','$2y$10$4ssRxJTzrZjKUdTZV7oUne5QAD63slVkF47.1cRpAxrFScodclVbC',NULL,'2018-05-14 02:20:49','2018-07-22 14:44:25','osvaldo',1,0),(83,'Froylan','nino@rtt.com','$2y$10$8cSMzEtPlIuDxj8SNVsz7exCQvyr.bZm0Rm2i/sbGAIGizOrI68QW',NULL,'2018-05-18 21:56:08','2018-07-25 05:08:55','nino',0,0),(88,'Renier','renier@rtt.com','$2y$10$qOCv4ht0ZQFr5JwPIQjhve1xEPSEbdkAyONUZe95B2rA1thOGCpVi','NzzpGviJlDdmgFJDIdZ1lz0e2dR36G57xGnnNw98AYZSVI3lDrjTOJVos0yp','2018-05-19 19:15:30','2018-07-22 14:44:18','renier',0,0),(97,'Client 1','client1@gmail.com','$2y$10$0AMx/Rit1ZU7AJADVsm6mOanPj1AW/onkMAyZSg./7g2l2zmougA.',NULL,'2018-07-25 04:12:59','2018-07-25 05:10:08','client1',1,4),(98,'Client 2 Gonzalez Peres','client2@gmail.com','$2y$10$l5xLdkqWMkuicVwY/qByGu4osLlNIQSZGrdsWyaQJXFAsm6j73EXC',NULL,'2018-07-25 04:14:17','2018-07-25 05:12:42','client2',1,5),(99,'Client 3','client3@gmail.com','$2y$10$RnMmkq8FwoBWhtPtuBASg.GWKwzj6RcZtuB7Bx/BmN6vI6bwUGJIS',NULL,'2018-07-25 05:11:57','2018-07-25 05:11:57','client3',1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
