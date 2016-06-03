<?php

//=========================================
// File name 	: students-table.php
// Description 	: Fetches the students for the specified field data

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Fetches the students for the specified field data
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

require_once('../helpers/form_helper.php');
require_once('../models/user.php');
require_once('../controllers/utility.php');
require_once('../controllers/compile.php');

session_start();

$user = new User();
$compile = new Compile();

$level = $user->getAccessLevel($user->getID($_SESSION['username']));

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['get-students'])) {
	$data = sanitize_array($_POST);
	$data['term'] = $compile->getTermID($data['session'], $data['term']);
	$table = '<h4>'.$data['class'].' Students</h4>' . R_NEWLINE;
	$table .= '<table class="table table-bordered table-hover" id="student-table">' . R_NEWLINE;
	$table .= '<thead>' . R_NEWLINE;
	$table .= '<th>Admission Number</th>' . R_NEWLINE;
	$table .= '<th>First Name</th>' . R_NEWLINE;
	$table .= '<th>Last Name</th>' . R_NEWLINE;
	$table .= '<th>Sex</th>' . R_NEWLINE;
	$table .= '<th>Number in Class</th>' . R_NEWLINE;
	$table .= '<th>Class</th>' . R_NEWLINE;
	$table .= '<th>Result</th>' . R_NEWLINE;
	$table .= '<tbody style="color: #000;">' . R_NEWLINE;
	$class = $data['class'];
	$user->getClassID($data['class']);
	$students = $user->getStudents($data['class'], $data['session']);
	if (empty($students)) {
		$table .= '<tr>' . R_NEWLINE;
		$table .= '<td style="text-align: center;" colspan="7"><h3>No Student for the selected class / session</h3></td>';
		$table .= '</tr>' . R_NEWLINE;
	} else {
		foreach($students as $student) {
			$table .= '<tr>' . R_NEWLINE;
			$table .= '<td>'.$student['admissionno'].'</td>' . R_NEWLINE;
			$table .= '<td>'.$student['firstname'].'</td>' . R_NEWLINE;
			$table .= '<td>'.$student['lastname'].'</td>' . R_NEWLINE;
			$table .= '<td>'.$student['sex'].'</td>' . R_NEWLINE;
			$table .= '<td>'.$student['numberinclass'].'</td>' . R_NEWLINE;
			$table .= '<td>'.$class.'</td>' . R_NEWLINE;
			$data['admissionno'] = $student['admissionno'];
			if ($user->isResultCompiled($data)) {
				$table .= "<td class=\"compiled\">Compiled <button id=\"edit\" type=\"button\" onclick=\"edit('".$data['admissionno']."', '".$data['class']."', '".$data['session']."', '".$data['term']."', '".$data['year']."');\" class=\"btn btn-primary btn-md\" title=\"Click here to edit student's result for selected session\" data-toggle=\"tooltip\" data-placement=\"right\" rel=\"txtToolTip\">Edit Result</button></td>" . R_NEWLINE;
			} else {
				$table .= "<td class=\"notcompiled\">Not Compiled <button id=\"compile\" type=\"button\" onclick=\"compile('".$data['admissionno']."', '".$data['class']."', '".$data['session']."', '".$data['term']."', '".$data['year']."');\" class=\"btn btn-primary btn-md\" title=\"Click here to compile student's result for selected session\" data-toggle=\"tooltip\" data-placement=\"right\" rel=\"txtToolTip\">Compile Result</button></td>" . R_NEWLINE;
			}
			$table .= '</tr>' . R_NEWLINE;
		}
	}
	$table .= '</tbody>' . R_NEWLINE;
	$table .= '</table>' . R_NEWLINE;
	echo $table;
}