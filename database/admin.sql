-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: laravel-shop
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.18.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'首页','fa-bar-chart','/',NULL,NULL,'2019-05-09 04:15:30'),(2,0,6,'系统管理','fa-tasks',NULL,NULL,NULL,'2019-06-12 08:19:47'),(3,2,7,'管理员','fa-users','auth/users',NULL,NULL,'2019-06-12 08:19:47'),(4,2,8,'角色','fa-user','auth/roles',NULL,NULL,'2019-06-12 08:19:47'),(5,2,9,'权限','fa-ban','auth/permissions',NULL,NULL,'2019-06-12 08:19:47'),(6,2,10,'菜单','fa-bars','auth/menu',NULL,NULL,'2019-06-12 08:19:48'),(7,2,11,'操作日志','fa-history','auth/logs',NULL,NULL,'2019-06-12 08:19:48'),(8,0,2,'用户管理','fa-users','/users',NULL,'2019-05-09 04:37:22','2019-05-09 05:01:12'),(9,0,3,'商品管理','fa-cubes','/products',NULL,'2019-05-09 10:48:01','2019-05-09 10:49:38'),(10,0,4,'订单管理','fa-rmb','/orders',NULL,'2019-05-17 02:58:14','2019-05-17 02:58:23'),(11,0,5,'优惠券管理','fa-tags','/coupon_codes',NULL,'2019-06-12 08:19:36','2019-06-12 08:19:47');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'All permission','*','','*',NULL,NULL),(2,'Dashboard','dashboard','GET','/',NULL,NULL),(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL),(6,'用户管理','users','','/users*','2019-05-09 06:19:23','2019-05-09 06:19:23'),(7,'商品管理','products','','/products*','2019-06-13 08:21:18','2019-06-13 08:21:18'),(8,'优惠券管理','coupon_codes','','/coupon_codes*','2019-06-13 08:22:13','2019-06-13 08:22:13'),(9,'订单管理','orders','','/orders*','2019-06-13 08:22:44','2019-06-13 08:22:44');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (1,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(2,3,NULL,NULL),(2,4,NULL,NULL),(2,6,NULL,NULL),(2,7,NULL,NULL),(2,8,NULL,NULL),(2,9,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2019-05-09 04:03:01','2019-05-09 04:03:01'),(2,'运营','operation','2019-05-09 06:22:51','2019-05-09 06:22:51');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
INSERT INTO `admin_user_permissions` VALUES (1,1,'2019-05-17 12:32:03','2019-05-17 12:32:06');
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$JYKT/rU0V.DsRmoJmGJmjOoMHD4C6tGQLYwoXYIQmP/4tf2XF72Vy','Administrator',NULL,'rAYRCi8OtKcYYnKJqKnlB7MtiZ6DOkX2eSqU8XIyajfEUxCI3PGgyQ1ePLeJ','2019-05-09 04:03:01','2019-05-09 04:03:01'),(2,'operator','$2y$10$LTy.XzWWCTehVl9iUVJQC.1h..sXQ5njdrpaBH4H26lALyTDHSrji','运营',NULL,'osMdfgGj7IKvwqk996XHxpId9J9JQP31aqJTUq1XYYludmsmNTURZMtuyU62','2019-05-09 06:24:18','2019-05-09 06:24:18');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-13  9:03:08
