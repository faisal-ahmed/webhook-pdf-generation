/*
SQLyog Ultimate v8.71 
MySQL - 5.5.24-log : Database - codeigniter
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`codeigniter` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `codeigniter`;

/*Table structure for table `class` */

DROP TABLE IF EXISTS `class`;

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `created` bigint(20) NOT NULL DEFAULT '1395742228',
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `class` */

insert  into `class`(`class_id`,`class`,`section`,`created`) values (1,'Nine','A',1395742228),(2,'One','A',1395742228);

/*Table structure for table `notification` */

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification` (
  `notification_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `message` text,
  `receiver` bigint(20) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `notification` */

/*Table structure for table `payment` */

DROP TABLE IF EXISTS `payment`;

CREATE TABLE `payment` (
  `payment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `month` varchar(255) DEFAULT NULL,
  `fee` int(11) NOT NULL,
  `paid` int(11) DEFAULT '0',
  `discount` int(11) DEFAULT '0',
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `payment` */

insert  into `payment`(`payment_id`,`student_id`,`month`,`fee`,`paid`,`discount`) values (1,1,'January',2000,1500,500),(2,1,'February',2000,1000,0);

/*Table structure for table `result` */

DROP TABLE IF EXISTS `result`;

CREATE TABLE `result` (
  `student_id` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `total_marks` double DEFAULT '0',
  `exam` varchar(255) DEFAULT NULL,
  `objective` double DEFAULT '0',
  `subjective` double DEFAULT '0',
  `practical` double DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `result` */

insert  into `result`(`student_id`,`subject`,`total_marks`,`exam`,`objective`,`subjective`,`practical`,`published`) values (1,'Bangla',50,'Mid Term',25,25,0,0),(1,'Math',40,'Mid Term',0,40,0,0);

/*Table structure for table `student` */

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `student_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(255) DEFAULT NULL,
  `student_roll` int(11) DEFAULT NULL,
  `class_id` bigint(20) DEFAULT NULL,
  `students_mobile` varchar(255) DEFAULT NULL,
  `parents_mobile` varchar(255) NOT NULL,
  `student_created` bigint(20) NOT NULL DEFAULT '1395742228',
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `student` */

insert  into `student`(`student_id`,`student_name`,`student_roll`,`class_id`,`students_mobile`,`parents_mobile`,`student_created`) values (1,'Mohammad Aminul Haque Bulbul Sarkar',1,1,'01940526064','01534836999',1395742238),(2,'Faisal',2,1,'01940526064','01912453679',1395742248);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`user_id`,`username`,`password`) values (1,'Admin','202cb962ac59075b964b07152d234b70');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
