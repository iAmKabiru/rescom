<?php

//=========================================
// File name 	: compile_result.php
// Description 	: Compile Result Component for main app

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Compile Result Component for main app
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

require_once('../helpers/form_helper.php');
require_once('../models/user.php');

$user = new User();

$level = $user->getAccessLevel($user->getID($_SESSION['username']));



if ($level < R_COMPILE_RES_LEVEL) {
	echo '<script>',
		'location.href = "http://localhost/rescom/app/main/views/index.php?action=dashboard";',
		'</script>';
}

echo '<div class="container content">' . R_NEWLINE;
echo '<h3>'.$config['sidebar-texts'][2].'</h3>' . R_NEWLINE;

echo '<div class="panel panel-default section-panel">' . R_NEWLINE;
		echo '<div class="panel-heading">' . R_NEWLINE;
			echo '<h1 class="panel-title">Data Capture</h1>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;
		echo '<div class="panel-body">' . R_NEWLINE;

		$content = <<<HEREDOC
			<p>
				This section provides functionalities to <b>Compile</b> and <b>Edit</b> Students' result.<br />
				<strong>Note:</strong> Only Staffs with the appropriate access level can Compile Result for Students. More so, only Staffs with superadmin priviledges can edit already compiled result of students.
			</p>
			<p>
				To begin result compilation processs, start by filling out the required fields below and click on get students to get the list of students for the specified field data;
			</p>
HEREDOC;

		echo $content . R_NEWLINE;

		getAddNewPanel("Compile");
			echo form_open("post", "form-horizontal", "getstudents-form");
				echo '<div class="form-inline getstd">' . R_NEWLINE;
				echo form_group();
						echo form_label("Class: ", "class");
						echo form_col(9);
						echo '<select style="width: 165px;" name="class" id="class" class="form-control">' . R_NEWLINE;
						echo '<option selected="selected">Select Class</option>' . R_NEWLINE;
						foreach($user->getClasses() as $row) {
							echo '<option>'.$row.'</option>' . R_NEWLINE;
						}
						echo '</select>' . R_NEWLINE;
						echo '</div>' . R_NEWLINE;
					echo form_group_close();
					echo form_group();
						echo form_label("Session: ", "session");
						echo form_col(9);
						echo '<select style="width: 165px;" name="session" id="session" class="form-control" disabled>' . R_NEWLINE;
						echo '</select>' . R_NEWLINE;
						echo '</div>' . R_NEWLINE;
					echo form_group_close();
				echo '<div class="form-inline getstd">' . R_NEWLINE;
					echo form_group();
						echo form_label("Term: ", "term");
						echo form_col(9);
						echo '<select style="width: 165px;" name="term" id="term" class="form-control">' . R_NEWLINE;
						echo '<option value="1st">1st</option>' . R_NEWLINE;
						echo '<option value="2nd">2nd</option>' . R_NEWLINE;
						echo '<option value="3rd">3rd</option>' . R_NEWLINE;
						echo '</select>' . R_NEWLINE;
						echo '</div>' . R_NEWLINE;
					echo form_group_close();
					echo form_group();
						echo form_label("Year: ", "year");
						echo form_col(9);
						echo '<select style="width: 165px;" name="year" id="year" class="form-control" disabled>' . R_NEWLINE;
						echo '</select>' . R_NEWLINE;
						echo '</div>' . R_NEWLINE;
					echo form_group_close();
				echo '</div>' . R_NEWLINE;
				echo '</div>' . R_NEWLINE;
				echo form_hidden("get-students", "get-students", "std");
				echo '<div class="form-inline getstd">' . R_NEWLINE;
					echo form_button("getstudents", "primary", "Get Students", "right");
				echo '</div>' . R_NEWLINE;

			echo form_close();

			echo '<div class="container-fluid students-list">' . R_NEWLINE;
				
			echo '</div>' . R_NEWLINE;

		echo '</div>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;
	echo '</div>' . R_NEWLINE;	
echo '</div>' . R_NEWLINE;