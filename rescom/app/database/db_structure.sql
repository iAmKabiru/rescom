-- 

-- File name 	: db_structure.sql
-- Description	: Creates and prepares the databse for app

-- Author		: Ozoka Lucky Orobo

-- (c) Copyright:
--				GenTech

--

--
--	Create and Use Database
--

create database res_db;
	use res_db;

--
--	Table Structure for Staffs (Staff Schema)
--

CREATE TABLE IF NOT EXISTS `res_staffs` (
	`staffid` int(11) NOT NULL AUTO_INCREMENT,
	`firstname` varchar(50) NOT NULL,
	`lastname` varchar(50) NOT NULL,
	`position` varchar(20) NOT NULL,
	`username` varchar(50) NOT NULL,
	`password` varchar(255) NOT NULL,
	`accesslevel` int(11) NOT NULL,
	PRIMARY KEY (`staffid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for SuperAdmin (Admin Schema)
--

CREATE TABLE IF NOT EXISTS `res_superadmin` (
	`superid` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(50) NOT NULL,
	`password` varchar(255) NOT NULL,
	`accesslevel` int(11) NOT NULL,
	PRIMARY KEY (`superid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Students (Students Schema)
--

CREATE TABLE IF NOT EXISTS `res_students` (
	`admissionno` varchar(20) NOT NULL,
	`firstname` varchar(50) NOT NULL,
	`lastname` varchar(50) NOT NULL,
	`middlename` varchar(50) NULL,
	`sex` varchar(10) NOT NULL,
	`dateofbirth` date NOT NULL,
	`address` text NULL,
	`numberinclass` int(11) NOT NULL,
	`parentnumber` varchar(20) NULL,
	`classid` int(11) NOT NULL,
	`classidonadmission` int(11) NOT NULL,
	`passport` text NULL,
	PRIMARY KEY (`admissionno`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Classes (Classes Schema)
--

CREATE TABLE IF NOT EXISTS `res_classes` (
	`classid` int(11) NOT NULL AUTO_INCREMENT,
	`class` varchar(10) NOT NULL,
	`category` varchar(10),
	PRIMARY KEY (`classid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Class (Class Schema)
--

CREATE TABLE IF NOT EXISTS `res_class` (
	`c_classid` int(11) NOT NULL AUTO_INCREMENT,
	`class` varchar(10) NOT NULL,
	`category` varchar(10),
	PRIMARY KEY (`c_classid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Subject (Subject Schema)
--

CREATE TABLE IF NOT EXISTS `res_subjects` (
	`subjectid` int(11) NOT NULL AUTO_INCREMENT,
	`subject` varchar(50) NOT NULL,
	`c_classid` int(11) NOT NULL,
	`category` varchar(10),
	PRIMARY KEY (`subjectid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Session (Session Schema)
--

CREATE TABLE IF NOT EXISTS `res_sessions` (
	`sessionid` int(11) NOT NULL AUTO_INCREMENT,
	`classids` varchar(20) NOT NULL,
	`session` varchar(10) NOT NULL,
	`year` varchar(10) NOT NULL,
	PRIMARY KEY (`sessionid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Term (Term Schema)
--

CREATE TABLE IF NOT EXISTS `res_terms` (
	`termid` int(11) NOT NULL AUTO_INCREMENT,
	`sessionid` int(11) NOT NULL,
	`term` varchar(10) NOT NULL,
	PRIMARY KEY (`termid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Student Class and Sessions (ClassSessions Schema)
--

CREATE TABLE IF NOT EXISTS `res_std_class_sessions` (
	`clssesid` int(11) NOT NULL AUTO_INCREMENT,
	`admissionno` varchar(20) NOT NULL,
	`classid` int(11) NOT NULL,
	`sessionid` int(11) NOT NULL,
	PRIMARY KEY (`clssesid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Result_data (Result_data Schema)
--

CREATE TABLE IF NOT EXISTS `result_data` (
	`resultid` int(11) NOT NULL AUTO_INCREMENT,
	`admissionno` varchar(20) NOT NULL,
	`classid` int(11) NOT NULL,
	`sessionid` int(11) NOT NULL,
	`termid` int(11) NOT NULL,
	`year` varchar(10) NOT NULL,
	`resultsheet` int(11) NULL,
	`scoresheet` int(11) NULL,
	PRIMARY KEY (`resultid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Skill (Skills Schema)
--

CREATE TABLE IF NOT EXISTS `res_skills` (
	`skillid` int(11) NOT NULL AUTO_INCREMENT,
	`skill` varchar(50) NOT NULL,
	PRIMARY KEY (`skillid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Behaviour (Behaviour Schema)
--

CREATE TABLE IF NOT EXISTS `res_behaviours` (
	`behaviourid` int(11) NOT NULL AUTO_INCREMENT,
	`behaviour` varchar(50) NOT NULL,
	PRIMARY KEY (`behaviourid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Result Sheet (Result Sheet Schema)
--

CREATE TABLE IF NOT EXISTS `result_sheet` (
	`sheetid` int(11) NOT NULL AUTO_INCREMENT,
	`admissionno` varchar(20) NOT NULL,
	`substakenid` int(11) NOT NULL,
	`extraid` int(11) NOT NULL,
	`commentid` int(11) NOT NULL,
	PRIMARY KEY (`sheetid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Subjects Taken (Subjects Taken Schema)
--

CREATE TABLE IF NOT EXISTS `res_subjectstaken` (
	`substakenid` int(11) NOT NULL AUTO_INCREMENT,
	`admissionno` varchar(20) NOT NULL,
	`subjectids` text NOT NULL,
	PRIMARY KEY (`substakenid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Scores (Scores Schema)
--

CREATE TABLE IF NOT EXISTS `res_scores` (
	`scoreid` int(11) NOT NULL AUTO_INCREMENT,
	`substakenid` int(11) NOT NULL,
	`subjectid` int(11) NOT NULL,
	`firsttest` int(11) NOT NULL,
	`secondtest` int(11) NOT NULL,
	`quiz` int(11) NOT NULL,
	`project` int(11) NOT NULL,
	`exam` int(11) NOT NULL,
	`total` int(11) NOT NULL,
	`average` double NOT NULL,
	`grade` varchar(10) NOT NULL,
	`interpretation` varchar(20) NOT NULL,
	PRIMARY KEY (`scoreid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;


--
-- Table Structure for Extra Curricula (Extra Curricula Schema)
--

CREATE TABLE IF NOT EXISTS `res_extracurr` (
	`extraid` int(11) NOT NULL AUTO_INCREMENT,
	`admissionno` varchar(20) NOT NULL,
	`skillids` text NOT NULL,
	`behaviourids` text NOT NULL,
	PRIMARY KEY (`extraid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Skill Set (Skill Set Schema)
--

CREATE TABLE IF NOT EXISTS `res_skillset` (
	`setid` int(11) NOT NULL AUTO_INCREMENT,
	`extraid` int(11) NOT NULL,
	`skillid` int(11) NOT NULL,
	`rating` text NOT NULL,
	PRIMARY KEY (`setid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Behaviour Set (Behaviour Set Schema)
--

CREATE TABLE IF NOT EXISTS `res_behaviourset` (
	`setid` int(11) NOT NULL AUTO_INCREMENT,
	`extraid` int(11) NOT NULL,
	`behaviourid` int(11) NOT NULL,
	`rating` text NOT NULL,
	PRIMARY KEY (`setid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Table Structure for Comment (Comment Schema)
--

CREATE TABLE IF NOT EXISTS `res_comments` (
	`commentid` int(11) NOT NULL AUTO_INCREMENT,
	`formcomment` text NOT NULL,
	`formsignature` varchar(10) NULL,
	`housecomment` text NULL,
	`housesignature` varchar(10) NULL,
	`guidancecomment` text NULL,
	`guidancesignature` varchar(10) NULL,
	`principalcomment` text NOT NULL,
	`principalsignature` varchar(10) NULL,
	PRIMARY KEY (`commentid`)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--
-- Creating data for superadmin in superadmin table
--

INSERT INTO `res_superadmin` (`superid`, `username`, `password`, `accesslevel`)
VALUES ('1', 'superadmin', '$2y$12$hx0LIl.LrGUaa0u12GgMrOIxtPux/IWoA0Wkcrby2vA.7QJFjmOQ.', '5');

--
-- Creating data for superadmin in staffs table
--

INSERT INTO `res_staffs` (`staffid`, `firstname`, `lastname`, `position`, `username`, `password`, `accesslevel`)
VALUES ('1', 'admin', 'admin', 'administrator', 'superadmin', '$2y$12$hx0LIl.LrGUaa0u12GgMrOIxtPux/IWoA0Wkcrby2vA.7QJFjmOQ.', '5');

--
-- Creating data for classes in classes table
--

INSERT INTO `res_classes` (`classid`, `class`, `category`)
VALUES	('1', 'J.S.S 1', 'junior'),
		('2', 'J.S.S 2', 'junior'),
		('3', 'J.S.S 3', 'junior'),
		('4', 'S.S.S 1', 'senior'),
		('5', 'S.S.S 2', 'senior'),
		('6', 'S.S.S 3', 'senior');


--
-- Creating data for class in class table
--

INSERT INTO `res_class` (`c_classid`, `class`, `category`)
VALUES	('1', 'J.S.S', 'junior'),
		('2', 'S.S.S', 'senior'),
		('3', 'ALL', 'ALL');
--
-- Creating data for subjects in subject table
--

INSERT INTO `res_subjects` (`subjectid`, `subject`, `c_classid`, `category`)
VALUES	('1', 'Basic Science', '1', 'science'),
		('2', 'Social Studies', '1', 'arts'),
		('3', 'Igbo', '1', 'arts'),
		('4', 'Christian Religious Studies', '3', 'arts'),
		('5', 'English Language', '3', 'arts'),
		('6', 'Mathematics', '3', 'science'),
		('7', 'Further Mathematics', '2', 'science'),
		('8', 'Biology', '2', 'science'),
		('9', 'Chemistry', '2', 'science'),
		('10', 'Physics', '2', 'science'),
		('11', 'Literature in English', '3', 'arts'),
		('12', 'Geography', '2', 'science'),
		('13', 'Government', '2', 'arts'),
		('14', 'Economics', '2', 'arts'),
		('15', 'Commerce', '2', 'arts'),
		('16', 'Computer Studies', '3', 'science'),
		('17', 'Home Economics', '1', 'arts'),
		('18', 'Business Studies', '1', 'arts'),
		('19', 'Basic Technology', '1', 'science'),
		('20', 'Music', '1', 'arts'),
		('21', 'Agricultural Science', '3', 'science'),
		('22', 'Physical & Health Education', '1', 'science');


--
-- Creating data for skills in skills table
--

INSERT INTO `res_skills` (`skillid`, `skill`)
VALUES 	('1', 'Handwriting'),
		('2', 'Fluency'),
		('3', 'Games'),
		('4', 'Sports'),
		('5', 'Gymnastics'),
		('6', 'Handling of Tools'),
		('7', 'Lab & Workshop'),
		('8', 'Drawing & Painting'),
		('9', 'Crafts'),
		('10', 'Music Skills');

--
-- Creating data for behaviour in behaviour table
--

INSERT INTO `res_behaviours` (`behaviourid`, `behaviour`)
VALUES 	('1', 'Punctuality'),
		('2', 'Attendance at Class'),
		('3', 'Reliability'),
		('4', 'Neatness'),
		('5', 'Politeness'),
		('6', 'Honesty'),	
		('7', 'Relationship with other students'),
		('8', 'Self Control'),
		('9', 'Spirit of Co-operation'),
		('10', 'Sense of Responsibility'),
		('11', 'Attentiveness'),
		('12', 'Initiative'),
		('13', 'Org. Ability'),
		('14', 'Perseverance');

--
-- Indexes for Dumped Table
--

--
-- Indexes for res_subjects table
--

ALTER TABLE `res_subjects` ADD FOREIGN KEY (`c_classid`) REFERENCES `res_class` (`c_classid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_students table
--

ALTER TABLE `res_students`
	ADD FOREIGN KEY (`classid`) REFERENCES `res_classes` (`classid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `res_students`
	ADD FOREIGN KEY (`classidonadmission`) REFERENCES `res_classes` (`classid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_sessions table
--

ALTER TABLE `res_terms`
	ADD FOREIGN KEY (`sessionid`) REFERENCES `res_sessions` (`sessionid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_std_class_sessions table
--

ALTER TABLE `res_std_class_sessions`
	ADD FOREIGN KEY (`admissionno`) REFERENCES `res_students` (`admissionno`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `res_std_class_sessions`
	ADD FOREIGN KEY (`classid`) REFERENCES `res_classes` (`classid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `res_std_class_sessions`
	ADD FOREIGN KEY (`sessionid`) REFERENCES `res_sessions` (`sessionid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for result_data table
--

ALTER TABLE `result_data`
	ADD FOREIGN KEY (`admissionno`) REFERENCES `res_students` (`admissionno`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `result_data`
	ADD FOREIGN KEY (`classid`) REFERENCES `res_classes` (`classid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `result_data`
	ADD FOREIGN KEY (`sessionid`) REFERENCES `res_sessions` (`sessionid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `result_data`
	ADD FOREIGN KEY (`termid`) REFERENCES `res_terms` (`termid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for result_sheet table
--

ALTER TABLE `result_sheet`
	ADD FOREIGN KEY (`admissionno`) REFERENCES `res_students` (`admissionno`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `result_sheet`
	ADD FOREIGN KEY (`substakenid`) REFERENCES `res_subjectstaken` (`substakenid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `result_sheet`
	ADD FOREIGN KEY (`extraid`) REFERENCES `res_extracurr` (`extraid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `result_sheet`
	ADD FOREIGN KEY (`commentid`) REFERENCES `res_comments` (`commentid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_subjectstaken table
--

ALTER TABLE `res_subjectstaken`
	ADD FOREIGN KEY (`admissionno`) REFERENCES `res_students` (`admissionno`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_scores table
--

ALTER TABLE `res_scores`
	ADD FOREIGN KEY (`substakenid`) REFERENCES `res_subjectstaken` (`substakenid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `res_scores`
	ADD FOREIGN KEY (`subjectid`) REFERENCES `res_subjects` (`subjectid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_extracurr table
--

ALTER TABLE `res_extracurr`
	ADD FOREIGN KEY (`admissionno`) REFERENCES `res_students` (`admissionno`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_skillset table
--

ALTER TABLE `res_skillset`
	ADD FOREIGN KEY (`extraid`) REFERENCES `res_extracurr` (`extraid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `res_skillset`
	ADD FOREIGN KEY (`skillid`) REFERENCES `res_skills` (`skillid`) ON DELETE no action ON UPDATE cascade;

--
-- Indexes for res_behaviourset table
--

ALTER TABLE `res_behaviourset`
	ADD FOREIGN KEY (`extraid`) REFERENCES `res_extracurr` (`extraid`) ON DELETE no action ON UPDATE cascade;
ALTER TABLE `res_behaviourset`
	ADD FOREIGN KEY (`behaviourid`) REFERENCES `res_behaviours` (`behaviourid`) ON DELETE no action ON UPDATE cascade;