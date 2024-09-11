/*
 Navicat MySQL Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : localhost:3306
 Source Schema         : db2

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 2/17/2020 10:20:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `enroll`;
DROP TABLE IF EXISTS `enroll2`;
DROP TABLE IF EXISTS `assign`;
DROP TABLE IF EXISTS `mentors`;
DROP TABLE IF EXISTS `mentees`;
DROP TABLE IF EXISTS `material`;
DROP TABLE IF EXISTS `meetings`;
DROP TABLE IF EXISTS `time_slot`;
DROP TABLE IF EXISTS `groups`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `parents`;
DROP TABLE IF EXISTS `users`;

-- ----------------------------
-- Table structure for users
-- ----------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for parents
-- ----------------------------

CREATE TABLE `parents` (
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`parent_id`),
  CONSTRAINT `parent_user` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for students
-- ----------------------------

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`student_id`),
  KEY `student_parent` (`parent_id`),
  CONSTRAINT `student_user` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for admins
-- ----------------------------

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`),
  CONSTRAINT `admins_user` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for groups
-- ----------------------------

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `mentor_grade_req` int(11) NOT NULL,
  `mentee_grade_req` int(11) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for time_slot
-- ----------------------------

CREATE TABLE `time_slot` (
  `time_slot_id` int(11) NOT NULL AUTO_INCREMENT,
  `day_of_the_week` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`time_slot_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for meetings
-- ----------------------------

CREATE TABLE `meetings` (
  `meet_id` int(11) NOT NULL AUTO_INCREMENT,
  `meet_name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `time_slot_id` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `announcement` varchar(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`meet_id`),
  KEY `meeting_group` (`group_id`),
  KEY `meeting_time_slot` (`time_slot_id`),
  CONSTRAINT `meeting_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE,
  CONSTRAINT `meeting_time_slot` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slot` (`time_slot_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for material
-- ----------------------------

CREATE TABLE `material` (
  `material_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `assigned_date` date NOT NULL,
  `notes` text,
  PRIMARY KEY (`material_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mentees
-- ----------------------------

CREATE TABLE `mentees` (
  `mentee_id` int(11) NOT NULL,
  PRIMARY KEY (`mentee_id`),
  CONSTRAINT `mentee_student` FOREIGN KEY (`mentee_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mentors
-- ----------------------------

CREATE TABLE `mentors` (
  `mentor_id` int(11) NOT NULL,
  PRIMARY KEY (`mentor_id`),
  CONSTRAINT `mentor_student` FOREIGN KEY (`mentor_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for enroll
-- ----------------------------

CREATE TABLE `enroll` (
  `meet_id` int(11) NOT NULL,
  `mentee_id` int(11) NOT NULL,
  PRIMARY KEY (`meet_id`,`mentee_id`),
  KEY `enroll_mentee` (`mentee_id`),
  CONSTRAINT `enroll_mentee` FOREIGN KEY (`mentee_id`) REFERENCES `mentees` (`mentee_id`) ON DELETE CASCADE,
  CONSTRAINT `enroll_meetings` FOREIGN KEY (`meet_id`) REFERENCES `meetings` (`meet_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for enroll2
-- ----------------------------

CREATE TABLE `enroll2` (
  `meet_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  PRIMARY KEY (`meet_id`,`mentor_id`),
  KEY `enroll2_mentor` (`mentor_id`),
  CONSTRAINT `enroll2_mentor` FOREIGN KEY (`mentor_id`) REFERENCES `mentors` (`mentor_id`) ON DELETE CASCADE,
  CONSTRAINT `enroll2_meetings` FOREIGN KEY (`meet_id`) REFERENCES `meetings` (`meet_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for assign
-- ----------------------------

CREATE TABLE `assign` (
  `meet_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  PRIMARY KEY (`meet_id`,`material_id`),
  KEY `assign_material` (`material_id`),
  KEY `assign_meetings` (`meet_id`),
  CONSTRAINT `assign_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`) ON DELETE CASCADE,
  CONSTRAINT `assign_meetings` FOREIGN KEY (`meet_id`) REFERENCES `meetings` (`meet_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





insert into users values(1,"admin1@gmail.com","$2y$10$umnjMzhR6zAAkaEerkqiue9G1akWIxoccdRc5OUeKiQgJT.dKKFTu","admin1","0000000000");

insert into users values(19,"parent1@gmail.com","$2y$10$.9upyfd/B3N3TrqIQuXLLug.ihxFLlzQY2hVQ1wHEm9QTKByTmbra","parent1","1111111111");
insert into users values(23,"parent2@gmail.com","$2y$10$5ugYHLVQFw8YfiaLWunyp.zmFFC743degNLgisueGx3kDYB5cJ.9.","parent2","1111111111");
insert into users values(26,"parent3@gmail.com","$2y$10$whOCf5psLmTMwolwcGWu1eSLxNv5PXEGIHx3zeaVhbKiNzUVr6BeK","parent3","3333333333");
insert into users values(27,"parent4@gmail.com","$2y$10$5cwkKDpPvYyP/7zkp265hu05L6Qi3NadLIhWf8xCGjKPNblBO08PO","parent4","4444444444");
insert into users values(28,"parent5@gmail.com","$2y$10$ffvuctOP/5aBFferUJq.4ermEGEz1BeiZt.A601vnvrpTGmoSJthq","parent5","5555555555");
insert into users values(29,"parent6@gmail.com","$2y$10$YT2dx.OdqxZLUAN49B7FfuddVAwM4uqb6W1r8B94/HKUY.hQS7StK","parent6","6666666666");
insert into users values(30,"parent7@gmail.com","$2y$10$c0Ejq0mCr7LdhqpDixL.ue/oYOCLJKc7DRJSbDvAFDON.AvgLRGKa","parent7","7777777777");
insert into users values(31,"parent8@gmail.com","$2y$10$mggn8qV78UTKhqpAC8h6ZeJfsIYV7sc4EbUIUxAj8hEfGiQ4AqoM6","parent8","8888888888");
insert into users values(32,"parent9@gmail.com","$2y$10$eZF7ciDuOcG9Avz5yH7b7OBsuffQZ1bIXC3TOqDP70vu6voGVh2wy","parent9","9999999999");
insert into users values(33,"parent10@gmail.com","$2y$10$jBV.vAROCgwLU6lAym.63OkYFVOpGTSA4Y.jM.pFEgnkP55TUjghC","parent10","1111111111");

insert into users values(20,"student1@gmail.com","$2y$10$mi6IhaTJ0e6KTkkYrea8UeB7hbtbnNtVIl5hV23IGyqp2vgw5htJK","student1","2222222222");
insert into users values(21,"student2@gmail.com","$2y$10$unCvcQcyXQ/5ngdecQ74C.KTk89VtqvQR8gF8O/DPzavmyl6KZmSm","student2","2222222222");
insert into users values(22,"student3@gmail.com","$2y$10$eaEgODA4MG8MVM0IrydAAuYNyorx75A1aBR4V7n4qjaYKyXKU5A9O","student3","3333333333");
insert into users values(24,"student4@gmail.com","$2y$10$U1aXO/G9kiH/9yI.JbkX9enMrP5og2yy1m6s5embJlpm9KjssiRbW","student4","4444444444");
insert into users values(25,"student5@gmail.com","$2y$10$k2gHlw9b0gUURwwrfwUz3OqJGMQlSrurHBs.ijeLRdqMOatwfQWny","student5","5555555555");
insert into users values(34,"student6@gmail.com","$2y$10$f5f1hPkpHs/8qKaLfAe8y.5BgsP62QNTIRayjwl.XalhRGj3nBRVG","student6","6666666666");
insert into users values(35,"student7@gmail.com","$2y$10$wd3MFbiiFri5YxEMACY8kOHmlkCeTpHJszmexkadntZ3znN1DeTUe","student7","7777777777");
insert into users values(36,"student8@gmail.com","$2y$10$v5qDbXfAl3UVKQkVKQj7dejEaE/2tORCSWnJ8tHL.ZuPumAgiSi6G","student8","8888888888");
insert into users values(37,"student9@gmail.com","$2y$10$MdKKuAcBgLpFEKBCOkG5Duy11rJArErkv2th3/Ad5q5mRTvkg/VLW","student9","9999999999");
insert into users values(38,"student10@gmail.com","$2y$10$QV7NcWOuWBao/etKJ61V.O6tBz1cwkRz0edADR.ruGKv6fg.6gsGa","student10","1111111111");



insert into admins(admin_id) values (1);

insert into parents(parent_id) values(19);
insert into parents(parent_id) values(23);
insert into parents(parent_id) values(26);
insert into parents(parent_id) values(27);
insert into parents(parent_id) values(28);
insert into parents(parent_id) values(29);
insert into parents(parent_id) values(30);
insert into parents(parent_id) values(31);
insert into parents(parent_id) values(32);
insert into parents(parent_id) values(33);

insert into students(student_id, grade, parent_id) values(20, 6, 19);
insert into students(student_id, grade, parent_id) values(21, 7, 19);
insert into students(student_id, grade, parent_id) values(22, 8, 19);
insert into students(student_id, grade, parent_id) values(24, 9, 23);
insert into students(student_id, grade, parent_id) values(25, 10, 23);
insert into students(student_id, grade, parent_id) values(34, 10, 29);
insert into students(student_id, grade, parent_id) values(35, 11, 30);
insert into students(student_id, grade, parent_id) values(36, 11, 31);
insert into students(student_id, grade, parent_id) values(37, 12, 33);
insert into students(student_id, grade, parent_id) values(38, 12, 33);



insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(3, "Three", "Third graders", 3, 3);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(4, "Four", "Fourth graders", 4, 4);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(5, "Five", "Fifth graders", 5, 5);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(6, "Six", "Sixth graders", 6, 6);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(7, "Seven", "Seventh graders", 7, 7);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(8, "Eight", "Eighth graders", 8, 8);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(9, "Nine", "Ninth graders", 9, 9);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(10, "Ten", "Tenth graders", 10, 10);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(11, "Eleven", "Eleventh graders", 11, 11);
insert into groups(group_id, name, description, mentor_grade_req, mentee_grade_req) values(12, "Twelve", "Twelfth graders", 12, 12);



insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(9, "Saturday", '09:00:00', '10:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(10, "Saturday", '10:00:00', '11:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(11, "Saturday", '11:00:00', '12:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(12, "Saturday", '12:00:00', '13:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(13, "Saturday", '13:00:00', '14:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(14, "Sunday", '09:00:00', '10:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(15, "Sunday", '10:00:00', '11:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(16, "Sunday", '11:00:00', '12:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(17, "Sunday", '12:00:00', '13:00:00');
insert into time_slot(time_slot_id, day_of_the_week, start_time, end_time) values(18, "Sunday", '13:00:00', '14:00:00');



insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(100, "History", '2021-01-22', 9, 6, "Welcome to history", 6);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(101, "Science", '2021-01-23', 10, 6, "Welcome to science", 7);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(102, "PE", '2021-03-27', 9, 6, "Welcome to PE", 8);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(103, "Reading", '2021-03-27', 10, 6, "Welcome to Reading", 6);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(104, "Biology", '2021-03-27', 11, 6, "Welcome to Biology", 7);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(105, "Animation", '2021-03-27', 12, 6, "Welcome to Animation", 9);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(106, "Music", '2021-03-27', 12, 6, "Welcome to Music", 10);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(107, "Art", '2021-03-27', 13, 6, "Welcome to Art", 10);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(108, "Spanish", '2021-03-28', 14, 6, "Welcome to Spanish", 11);
insert into meetings(meet_id, meet_name, date, time_slot_id, capacity, announcement, group_id) values(109, "Psychology", '2021-03-28', 15, 6, "Welcome to Psychology", 12);



insert into material(material_id, title, author, type, url, assigned_date, notes) values(12, "Ch 1", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 1");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(13, "Ch 2", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 2");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(14, "Ch 3", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 3");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(15, "Ch 4", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 4");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(16, "Ch 5", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 5");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(17, "Ch 6", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 6");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(18, "Ch 7", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 7");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(19, "Ch 8", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 8");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(20, "Ch 9", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 9");
insert into material(material_id, title, author, type, url, assigned_date, notes) values(21, "Ch 10", "Sips", "Reading", "Sips.com", "2021-03-25", "Read ch 10");



insert into mentees(mentee_id) values(20);
insert into mentees(mentee_id) values(21);
insert into mentees(mentee_id) values(22);
insert into mentees(mentee_id) values(24);
insert into mentees(mentee_id) values(25);
insert into mentees(mentee_id) values(34);
insert into mentees(mentee_id) values(35);
insert into mentees(mentee_id) values(36);
insert into mentees(mentee_id) values(37);
insert into mentees(mentee_id) values(38);



insert into mentors(mentor_id) values(20);
insert into mentors(mentor_id) values(21);
insert into mentors(mentor_id) values(22);
insert into mentors(mentor_id) values(24);
insert into mentors(mentor_id) values(25);
insert into mentors(mentor_id) values(34);
insert into mentors(mentor_id) values(35);
insert into mentors(mentor_id) values(36);
insert into mentors(mentor_id) values(37);
insert into mentors(mentor_id) values(38);



insert into enroll(meet_id, mentee_id) values (101, 21);
insert into enroll(meet_id, mentee_id) values (102, 22);
insert into enroll(meet_id, mentee_id) values (103, 20);
insert into enroll(meet_id, mentee_id) values (106, 24);
insert into enroll(meet_id, mentee_id) values (107, 25);
insert into enroll(meet_id, mentee_id) values (107, 34);
insert into enroll(meet_id, mentee_id) values (108, 35);
insert into enroll(meet_id, mentee_id) values (108, 36);
insert into enroll(meet_id, mentee_id) values (109, 37);
insert into enroll(meet_id, mentee_id) values (109, 38);



insert into enroll2(meet_id, mentor_id) values (101, 21);
insert into enroll2(meet_id, mentor_id) values (101, 25);
insert into enroll2(meet_id, mentor_id) values (103, 20);
insert into enroll2(meet_id, mentor_id) values (104, 24);
insert into enroll2(meet_id, mentor_id) values (105, 22);
insert into enroll2(meet_id, mentor_id) values (107, 34);
insert into enroll2(meet_id, mentor_id) values (108, 35);
insert into enroll2(meet_id, mentor_id) values (108, 36);
insert into enroll2(meet_id, mentor_id) values (109, 37);
insert into enroll2(meet_id, mentor_id) values (109, 38);



insert into assign(meet_id, material_id) values(103, 12);
insert into assign(meet_id, material_id) values(104, 13);
insert into assign(meet_id, material_id) values(105, 14);
insert into assign(meet_id, material_id) values(106, 15);
insert into assign(meet_id, material_id) values(107, 16);
insert into assign(meet_id, material_id) values(108, 17);
insert into assign(meet_id, material_id) values(108, 18);
insert into assign(meet_id, material_id) values(108, 19);
insert into assign(meet_id, material_id) values(109, 20);
insert into assign(meet_id, material_id) values(109, 21);
SET FOREIGN_KEY_CHECKS = 1;