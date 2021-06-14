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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_attendance` */

insert  into `tbl_attendance`(`attendance_id`,`student_id`,`attendance_status`,`attendance_date`,`faculty_id`) values 
(1,1,'Present','2020-11-01',1),
(2,2,'Present','2020-11-01',1),
(3,3,'Absent','2020-11-01',2),
(4,4,'Present','2020-11-01',2);

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
(1,'SE 101','Course 1',1, 5),
(2,'SE 201','Course 2',2, 5);

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
(1,'Chitra Singla',1,'2003-03-04',1,'chitra@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(2,'Daniyal Farooque',2,'2003-03-04',1,'daniyal@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(3,'Rohit Kumar',3,'2003-04-19',2,'rohit@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW'),
(4,'abc',4,'2004-01-15',2,'abc@gmail.com','$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW');
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
(1,'Bhupender Rana','Rohini, Delhi','bhupender.rana@gmail.com','$2y$10$s2MmR/Ml6ohRRrrFY0SRQ.vWohGvthVsKe59zgLOIvm3Qd0PzavD2','B.Sc, B.Ed','2020-09-01',1),
(2,'Sneha Sharma','Gurugoan','sneha.sharma@gmail.com','$2y$10$s2MmR/Ml6ohRRrrFY0SRQ.vWohGvthVsKe59zgLOIvm3Qd0PzavD2','M.Sc, B. Ed','2017-12-31',2);
