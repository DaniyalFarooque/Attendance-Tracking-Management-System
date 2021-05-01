
CREATE DATABASE /*!32312 IF NOT EXISTS*/`attendance` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `attendance`;

/*Table structure for table `tbl_admin` */

DROP TABLE IF EXISTS `tbl_admin`;

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_user_name` varchar(100) NOT NULL,
  `admin_password` varchar(150) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_admin` */

insert  into `tbl_admin`(`admin_id`,`admin_user_name`,`admin_password`) values 
(1,'admin','$2y$10$D74Zy1qMkATvmGRoVeq7hed4ajWof2aqDGnEaD3yPHABA.p.e7f4u'),
(2,'admin123','$2y$10$D74Zy1qMkATvmGRoVeq7hed4ajWof2aqDGnEaD3yPHABA.p.e7f4u');

/*------------------------------------------------------------------------------------*/
/*Table structure for table `tbl_deo` */

DROP TABLE IF EXISTS `tbl_deo`;

CREATE TABLE `tbl_deo` (
  `deo_id` int(11) NOT NULL AUTO_INCREMENT,
  `deo_user_name` varchar(100) NOT NULL,
  `deo_password` varchar(150) NOT NULL,
  PRIMARY KEY (`deo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_deo` */

insert  into `tbl_deo`(`deo_id`,`deo_user_name`,`deo_password`) values 
(1,'deo','$2y$10$D74Zy1qMkATvmGRoVeq7hed4ajWof2aqDGnEaD3yPHABA.p.e7f4u');


/*------------------------------------------------------------------------------------------------------*/


/*Table structure for table `tbl_attendance` */

DROP TABLE IF EXISTS `tbl_attendance`;

CREATE TABLE `tbl_attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `attendance_status` enum('Present','Absent') NOT NULL,
  `attendance_date` date NOT NULL,
  `faculty_id` int(11) NOT NULL,
  PRIMARY KEY (`attendance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_attendance` */

insert  into `tbl_attendance`(`attendance_id`,`student_id`,`attendance_status`,`attendance_date`,`faculty_id`) values 
(1,7,'Present','2020-09-01',3),
(2,8,'Present','2020-09-01',3),
(3,9,'Absent','2020-09-01',3),
(4,10,'Present','2020-09-01',3),
(5,11,'Present','2020-09-01',3),
(6,7,'Absent','2020-09-02',3),
(7,8,'Present','2020-09-02',3),
(8,9,'Present','2020-09-02',3),
(9,10,'Present','2020-09-02',3),
(10,11,'Absent','2020-09-02',3),
(11,1,'Present','2020-09-01',2),
(12,3,'Present','2020-09-01',2),
(13,4,'Present','2020-09-01',2),
(14,5,'Present','2020-09-01',2),
(15,6,'Present','2020-09-01',2),
(16,1,'Present','2020-09-02',2),
(17,3,'Absent','2020-09-02',2),
(18,4,'Present','2020-09-02',2),
(19,5,'Absent','2020-09-02',2),
(20,6,'Present','2020-09-02',2),
(21,1,'Present','2020-09-03',2),
(22,3,'Present','2020-09-03',2),
(23,4,'Absent','2020-09-03',2),
(24,5,'Present','2020-09-03',2),
(25,6,'Present','2020-09-03',2),
(26,1,'Absent','2020-09-04',2),
(27,3,'Present','2020-09-04',2),
(28,4,'Present','2020-09-04',2),
(29,5,'Present','2020-09-04',2),
(30,6,'Present','2020-09-04',2),
(31,1,'Present','2020-09-06',2),
(32,3,'Present','2020-09-06',2),
(33,4,'Present','2020-09-06',2),
(34,5,'Present','2020-09-06',2),
(35,6,'Present','2020-09-06',2),
(36,1,'Present','2020-09-07',2),
(37,3,'Present','2020-09-07',2),
(38,4,'Present','2020-09-07',2),
(39,5,'Present','2020-09-07',2),
(40,6,'Absent','2020-09-07',2),
(41,1,'Present','2020-09-08',2),
(42,3,'Present','2020-09-08',2),
(43,4,'Present','2020-09-08',2),
(44,5,'Absent','2020-09-08',2),
(45,6,'Present','2020-09-08',2),
(46,1,'Present','2020-09-09',2),
(47,3,'Present','2020-09-09',2),
(48,4,'Present','2020-09-09',2),
(49,5,'Present','2020-09-09',2),
(50,6,'Present','2020-09-09',2),
(51,1,'Present','2020-09-10',2),
(52,3,'Absent','2020-09-10',2),
(53,4,'Absent','2020-09-10',2),
(54,5,'Present','2020-09-10',2),
(55,6,'Absent','2020-09-10',2),
(56,1,'Present','2020-09-11',2),
(57,3,'Present','2020-09-11',2),
(58,4,'Absent','2020-09-11',2),
(59,5,'Present','2020-09-11',2),
(60,6,'Absent','2020-09-11',2),
(61,7,'Present','2020-09-03',3),
(62,8,'Present','2020-09-03',3),
(63,9,'Present','2020-09-03',3),
(64,10,'Present','2020-09-03',3),
(65,11,'Present','2020-09-03',3),
(66,7,'Absent','2020-09-04',3),
(67,8,'Present','2020-09-04',3),
(68,9,'Absent','2020-09-04',3),
(69,10,'Present','2020-09-04',3),
(70,11,'Absent','2020-09-04',3),
(71,7,'Present','2020-09-06',3),
(72,8,'Present','2020-09-06',3),
(73,9,'Absent','2020-09-06',3),
(74,10,'Present','2020-09-06',3),
(75,11,'Present','2020-09-06',3),
(76,7,'Present','2020-09-07',3),
(77,8,'Present','2020-09-07',3),
(78,9,'Present','2020-09-07',3),
(79,10,'Present','2020-09-07',3),
(80,11,'Present','2020-09-07',3),
(81,7,'Present','2020-09-08',3),
(82,8,'Present','2020-09-08',3),
(83,9,'Present','2020-09-08',3),
(84,10,'Absent','2020-09-08',3),
(85,11,'Absent','2020-09-08',3),
(86,7,'Present','2020-09-09',3),
(87,8,'Present','2020-09-09',3),
(88,9,'Absent','2020-09-09',3),
(89,10,'Present','2020-09-09',3),
(90,11,'Present','2020-09-09',3),
(91,7,'Absent','2020-09-10',3),
(92,8,'Present','2020-09-10',3),
(93,9,'Present','2020-09-10',3),
(94,10,'Present','2020-09-10',3),
(95,11,'Absent','2020-09-10',3),
(96,7,'Present','2020-09-11',3),
(97,8,'Present','2020-09-11',3),
(98,9,'Present','2020-09-11',3),
(99,10,'Absent','2020-09-11',3),
(100,11,'Present','2020-09-11',3),
(101,12,'Present','2020-09-01',4),
(102,13,'Present','2020-09-01',4),
(103,14,'Present','2020-09-01',4),
(104,15,'Present','2020-09-01',4),
(105,16,'Present','2020-09-01',4),
(106,12,'Present','2020-09-02',4),
(107,13,'Absent','2020-09-02',4),
(108,14,'Present','2020-09-02',4),
(109,15,'Absent','2020-09-02',4),
(110,16,'Present','2020-09-02',4),
(111,12,'Present','2020-09-03',4),
(112,13,'Present','2020-09-03',4),
(113,14,'Present','2020-09-03',4),
(114,15,'Absent','2020-09-03',4),
(115,16,'Present','2020-09-03',4),
(116,12,'Present','2020-09-04',4),
(117,13,'Present','2020-09-04',4),
(118,14,'Present','2020-09-04',4),
(119,15,'Present','2020-09-04',4),
(120,16,'Present','2020-09-04',4),
(121,12,'Present','2020-09-06',4),
(122,13,'Absent','2020-09-06',4),
(123,14,'Absent','2020-09-06',4),
(124,15,'Present','2020-09-06',4),
(125,16,'Present','2020-09-06',4),
(126,12,'Absent','2020-09-07',4),
(127,13,'Present','2020-09-07',4),
(128,14,'Present','2020-09-07',4),
(129,15,'Present','2020-09-07',4),
(130,16,'Absent','2020-09-07',4),
(131,12,'Present','2020-09-08',4),
(132,13,'Absent','2020-09-08',4),
(133,14,'Present','2020-09-08',4),
(134,15,'Present','2020-09-08',4),
(135,16,'Present','2020-09-08',4),
(136,12,'Present','2020-09-09',4),
(137,13,'Present','2020-09-09',4),
(138,14,'Present','2020-09-09',4),
(139,15,'Present','2020-09-09',4),
(140,16,'Absent','2020-09-09',4),
(141,12,'Present','2020-09-10',4),
(142,13,'Absent','2020-09-10',4),
(143,14,'Present','2020-09-10',4),
(144,15,'Present','2020-09-10',4),
(145,16,'Absent','2020-09-10',4),
(146,12,'Present','2020-09-11',4),
(147,13,'Present','2020-09-11',4),
(148,14,'Present','2020-09-11',4),
(149,15,'Present','2020-09-11',4),
(150,16,'Present','2020-09-11',4),
(151,17,'Present','2020-09-01',5),
(152,18,'Present','2020-09-01',5),
(153,19,'Present','2020-09-01',5),
(154,20,'Absent','2020-09-01',5),
(155,21,'Absent','2020-09-01',5),
(156,17,'Present','2020-09-02',5),
(157,18,'Present','2020-09-02',5),
(158,19,'Present','2020-09-02',5),
(159,20,'Present','2020-09-02',5),
(160,21,'Present','2020-09-02',5),
(161,17,'Present','2020-09-03',5),
(162,18,'Present','2020-09-03',5),
(163,19,'Present','2020-09-03',5),
(164,20,'Present','2020-09-03',5),
(165,21,'Absent','2020-09-03',5),
(166,17,'Present','2020-09-04',5),
(167,18,'Present','2020-09-04',5),
(168,19,'Absent','2020-09-04',5),
(169,20,'Present','2020-09-04',5),
(170,21,'Present','2020-09-04',5),
(171,17,'Present','2020-09-06',5),
(172,18,'Present','2020-09-06',5),
(173,19,'Present','2020-09-06',5),
(174,20,'Present','2020-09-06',5),
(175,21,'Present','2020-09-06',5),
(176,17,'Present','2020-09-07',5),
(177,18,'Present','2020-09-07',5),
(178,19,'Present','2020-09-07',5),
(179,20,'Present','2020-09-07',5),
(180,21,'Absent','2020-09-07',5),
(181,17,'Present','2020-09-08',5),
(182,18,'Present','2020-09-08',5),
(183,19,'Present','2020-09-08',5),
(184,20,'Absent','2020-09-08',5),
(185,21,'Present','2020-09-08',5),
(186,17,'Present','2020-09-09',5),
(187,18,'Present','2020-09-09',5),
(188,19,'Absent','2020-09-09',5),
(189,20,'Absent','2020-09-09',5),
(190,21,'Present','2020-09-09',5),
(191,17,'Absent','2020-09-10',5),
(192,18,'Present','2020-09-10',5),
(193,19,'Present','2020-09-10',5),
(194,20,'Present','2020-09-10',5),
(195,21,'Present','2020-09-10',5),
(196,17,'Present','2020-09-11',5),
(197,18,'Present','2020-09-11',5),
(198,19,'Present','2020-09-11',5),
(199,20,'Absent','2020-09-11',5),
(200,21,'Present','2020-09-11',5),
(201,7,'Present','2020-09-13',3),
(202,8,'Present','2020-09-13',3),
(203,9,'Present','2020-09-13',3),
(204,10,'Absent','2020-09-13',3),
(205,11,'Present','2020-09-13',3),
(206,7,'Present','2020-09-14',3),
(207,8,'Present','2020-09-14',3),
(208,9,'Absent','2020-09-14',3),
(209,10,'Present','2020-09-14',3),
(210,11,'Present','2020-09-14',3);

/*----------------------------------------------------------------------------------------------------*/

/*Table structure for table `tbl_course` */

DROP TABLE IF EXISTS `tbl_course`;

CREATE TABLE `tbl_course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_code` varchar(10) NOT NULL,
  `course_name` varchar(10) NOT NULL,
  `course_semester` varchar(10) NOT NULL,
  `course_credit` varchar(10) NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_course` */

insert  into `tbl_course`(`course_id`,`course_code`,`course_name`,`course_semester`,`course_credit`) values 
(1,'CS 101','Course 1',1, 5),
(2,'CS 201','Course 2',2, 5),
(3,'CS 301','Course 3',3, 5),
(4,'CS 401','Course 4',1, 5),
(5,'CS 501','Course 5',4, 5);

/*----------------------------------------------------------------------------------------------------*/

/*Table structure for table `tbl_student` */

DROP TABLE IF EXISTS `tbl_student`;

CREATE TABLE `tbl_student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(150) NOT NULL,
  `student_roll_number` int(11) NOT NULL,
  `student_dob` date NOT NULL,
  `student_course_id` int(11) NOT NULL,
  `student_emailid` varchar(100) NOT NULL,
  `student_password` varchar(100) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_student` */

insert  into `tbl_student`(`student_id`,`student_name`,`student_roll_number`,`student_dob`,`student_course_id`,`student_emailid`,`student_password`) values 
(1,'Edward Hedberg',1,'2003-03-04',1,'edward@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(3,'William Crawford',2,'2003-04-19',1,'william@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(4,'Renee Crowe',3,'2004-01-15',1,'renee@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(5,'Lillian Williams',4,'2003-12-14',1,'lillianw@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(6,'Betty Mayer',5,'2003-07-12',1,'betty@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(7,'Sally Luna',1,'2003-12-19',2,'sally@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(8,'Richard Smith',2,'2002-12-19',2,'richard@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(9,'Phyllis Shoop',3,'2003-04-01',2,'phyllis@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(10,'Earl Perry',4,'2003-08-15',2,'earl@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(11,'Minnie Morris',5,'2003-06-18',2,'minnie@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(12,'Lisa Ochoa',1,'2002-09-01',3,'lisa@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(13,'Marcus Holmes',2,'2002-04-12',3,'marcus@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(14,'Ernesto Arnold',3,'2002-10-12',3,'ernesto@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(15,'Lillian Harris',4,'2002-02-27',3,'lillianh@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(16,'Charles Reed',5,'2002-06-12',3,'charles@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(17,'Lois Gonzales',1,'2002-08-17',4,'lois@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(18,'Mary Floyd',2,'2002-09-18',4,'mary@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(19,'Maria Biggs',3,'2002-07-15',4,'maria@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(20,'Cleo Phillips',4,'2002-01-14',4,'cleo@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(21,'Rafael Royal',5,'2002-12-09',4,'rafeal@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(22,'Jeremy Breawer',1,'2002-04-11',5,'jeremy@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW');

/*---------------------------------------------------------------------------------------------------*/

/*Table structure for table `tbl_faculty` */

DROP TABLE IF EXISTS `tbl_faculty`;

CREATE TABLE `tbl_faculty` (
  `faculty_id` int(11) NOT NULL AUTO_INCREMENT,
  `faculty_name` varchar(150) NOT NULL,
  `faculty_address` text NOT NULL,
  `faculty_emailid` varchar(100) NOT NULL,
  `faculty_password` varchar(100) NOT NULL,
  `faculty_qualification` varchar(100) NOT NULL,
  `faculty_doj` date NOT NULL,
  `faculty_course_id` int(11) NOT NULL,
  PRIMARY KEY (`faculty_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_faculty` */

insert  into `tbl_faculty`(`faculty_id`,`faculty_name`,`faculty_address`,`faculty_emailid`,`faculty_password`,`faculty_qualification`,`faculty_doj`,`faculty_course_id`) values 
(2,'Amy Smith','1810 Kuhl Avenue Gainesville, GA 30901','amy.smith@gmail.com','$2y$10$s2MmR/Ml6ohRRrrFY0SRQ.vWohGvthVsKe59zgLOIvm3Qd0PzavD2','B.Sc, B.Ed','2020-09-01',1),
(3,'Peter Parker','620 Jody Road, Philadelphia, PA 19108','peter_parker@gmail.com','$2y$10$jmgJN1xvteg6XqBnHvT7UerviGNJOSnF8KFzBHnCky0FJWa74Nvmu','M.Sc, B. Ed','2017-12-31',2),
(4,'John Smith','780 University Drive, Chicago, IL 60606','john.smith@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW','B.Sc','2020-09-01',3),
(5,'Jennie Hall','3354 Round Table Drive, Cincinnati, OH 45240','jennie_hall@gmail.com','$2y$10$SVxX4/7lf3pDs1vrpuJexOG7Ue1e1jqIntGmXip3JzxkB753uxBiO','M.Sc','2020-09-01',4);
