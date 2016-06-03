<?php

//=========================================
// File name 	: dashboard.php
// Description 	: Interacts with the User View and User Model to perform operations

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Interacts with the User View and User Model to perform operations
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

require_once('../models/user.php');
require_once('utility.php');

$user = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//Add Staff to platform
	if (isset($_POST['add-staff'])) {
		$data = sanitize_array($_POST);
		
		//Check if the username passed-in already exists on the platform
		if ($user->staffExists($data['username'])) {
			echo 'exist';
			exit();
		}

		//Staff does not exist, add staff to platform
		if ($user->addStaff($data)) {
			echo 'success';
			exit();
		} else {
			echo 'failed';
			exit();
		}
	}

	if (isset($_POST['add-student'])) {
		$data = sanitize_array($_POST);

		//Validate the date
		
		if (DateTime::createFromFormat('Y/m/d', $data['dateofbirth']) === FALSE) {
			echo 'invalid_date';
			exit();
		}

		//Validate number in class
		if (filter_var($data['numinclass'], FILTER_VALIDATE_INT) <= 0) {
			echo 'invalid_num';
			exit();
		}

		//Validate phone number
		if (!empty($data['parentnumber'])) {
			if (!preg_match('/[0-9]{5}/', $data['parentnumber'])) {
				echo 'invalid_pnum';
				exit();
			}
		}

		//Validate Class on Admission vs Class Selected
		if ($user->getClassxID($data['classonad']) > $user->getClassxID($data['class'])) {
			echo 'onaderror';
			exit();
		}

		//Check if Student Exist
		if ($user->studentExists($data['admissionno'])) {
			echo "exist";
			exit();
		}

		//Check if Student with the specified number in class and class exists
		if ($user->numberInClass($data['class'], $data['numinclass'])) {
			echo "numexist";
			exit();	
		}

		$class = $data['class'];

		//Check if passport is uploaded
		if (isset($_FILES['passport'])) {
			$filename = $_FILES['passport']['name'];
			$filesize = $_FILES['passport']['size'];
			$filetype = $_FILES['passport']['type'];
			$filetmp = $_FILES['passport']['tmp_name'];
			$fileext = strtolower(end(explode('.', $filename)));

			$ext = array('jpg', 'jpeg', 'png');

			//Check file ext
			if (!in_array($fileext, $ext)) {
				echo 'invalid_ext';
				exit();
			}

			//Check file size
			if ($filesize > 2097152) {
				echo 'invalid_size';
				exit();
			}

			//Save file to disk
			$filename = $data['admissionno'] . '_' . $data['lastname'] . '.' . $fileext;
			$data['passport'] = $filename;
			$data['tmp_name'] = $filetmp;
		}

		//Add Student to platform
		if ($user->addStudent($data)) {
			move_uploaded_file($data['tmp_name'], '../../uploads/'.$class. '/' . $data['passport']);
			echo 'success';
			exit();
		} else {
			echo 'failed';
			exit();
		}


	}

}
