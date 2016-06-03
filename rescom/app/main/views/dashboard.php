<?php

//=========================================
// File name 	: dashboard.php
// Description 	: Dashboard components for main app

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Dashboard components for main app
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

require_once('../helpers/form_helper.php');
require_once('../models/user.php');

$user = new User();


$level = $user->getAccessLevel($user->getID($_SESSION['username']));

echo '<div class="container content">' . R_NEWLINE;
	echo '<button id="search-trigger" class="btn btn-primary btn-md">Search</button>' . R_NEWLINE;
	echo '<div class="search-box">' . R_NEWLINE;
		echo form_open('POST', 'form-inline', "searchbox");
		echo form_group();
		$options = array(
			"First Name" => 1,
			"Last Name" => 0,
			"Class" => 0
			);
		echo form_select("searchby", "searchby", $options);
		echo '</div>' . R_NEWLINE;
		echo form_group();
		echo form_input("search", $id = "search", "", $placeholder = "Search", $required = "required");
		echo '</div>' . R_NEWLINE;
		echo '<button type="submit" class="btn btn-tertiary btn-md">Search</button>' . R_NEWLINE;
		echo '</form>';
	echo '</div>' . R_NEWLINE;
	echo '<h3>'.R_APP_TITLE.'</h3>' . R_NEWLINE;
	echo '<div class="panel panel-default section-panel">' . R_NEWLINE;
		echo '<div class="panel-heading">' . R_NEWLINE;
			echo '<h1 class="panel-title">Welcome, '.$_SESSION['username'].'</h1>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;	
		echo '<div class="panel-body">' . R_NEWLINE;
			echo '<p>' . R_NEWLINE;
		if ($level == R_SUPER_ADMIN_LEVEL) {
			$addUser = '<h3>Add User</h3>
				<p>
					The <strong>Add User</strong> section provides functionalities for data capture for both <b>Students</b> and <b>Staffs</b>.
					This will be the initial phase before result compilation for any student. Only Staffs with proper priviledges will, however,
					have access to add a student to the platform.
				</p>';
		} else {
			$addUser = '<h3>'.$config['sidebar-texts'][1].'</h3>
				<p>
					The <strong>Add Student</strong> section provides functionalities for data capture for <b>Students</b>.
					This will be the initial phase before result compilation for any student. Only Staffs with proper priviledges will, however,
					have access to add a student to the platform.
				</p>';
		}
		$content = <<<_END
				This platform provides functionalities to <b>Create</b>, <b>Compile</b>, <b>View</b>, and <b>Export</b> students results.<br />
				<h3>Dashboard</h3>
				<p>
					The <strong>Dashboard</strong> provides helpful tips on how to use the platform.<br />It also provides an
					easy way to look up students' individual result sheets or the result sheet for a specified class. <br />
					To look up Result / Score sheets accordingly, please use the search bar above; Enter the <b>First Name</b> or <b>Last Name</b> of a student or a <b>Class</b> to search.
				</p>
				$addUser
				<h3>Compile Result</h3>
				<p>
					The <strong>Compile Result</strong> section provides features for <b>Compiling</b> Students' result sheet for each term.
					The Result Compilation process features three (3) phases which includes;
					<ol>
						<li>Students' Result Information Capture</li>
						<li>Students' Term Information Capture and;</li>
						<li>Teachers' Specific Infomation</li>
					</ol>
				</p>
				<p>
					The first phase includes entering students' result information for each subject such as first test, second test, project e.t.c., while the second phase involves
					entering information about the students' form such as puntuality, neatness, extracurricular activities e.tc., The third phase will be providing teachers' / staffs'
					specific information about each student, for example, Principal's comment, Form Master's comment e.t.c.
				</p>
				<p>
				In case of Student's Result Sheet, the result can be viewed and reviewed and if there is a need to update any
					particular result sheet, this section provides a feature that can perform that operation. <strong>NOTE: Only Staffs with
					required priviledges can update students results sheet from this section.</strong>
				</p>
				<h3>Results</h3>
				<p>
					The <strong>Results</strong> section provides functions that lets you <b>View</b> and <b>Print</b> Compiled Results for each of the following cases;
					<ul>
						<li>Student's Result Sheet</li>
						<li>Class Score Sheet</li>
					</ul>
				</p>
_END;
			echo $content;
			echo '</p>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;	
	echo '</div>' . R_NEWLINE;
echo '</div>' . R_NEWLINE;