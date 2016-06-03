<?php

//=========================================
// File name 	: manage.php
// Description 	: Manage users and result data on app

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Manage users and result data on app
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
	echo '<h3>Manage</h3>' . R_NEWLINE;	
	echo '<div class="panel panel-default section-panel">' . R_NEWLINE;
		echo '<div class="panel-heading">' . R_NEWLINE;
			echo '<h1 class="panel-title">Manage Platform</h1>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;
		echo '<div class="panel-body">' . R_NEWLINE;

			$contents = <<<_
				<p>
					This section provides functionalities to manage <b>Staffs</b>, <b>Students</b>, and <b>Result Data</b> data to the platform.
				</p>
				<p>
					From this section you can add new sessions to a selected class, Remove Staffs or Students from the platform as well as Edit their personal data. This section is only accessible to staffs with super admin priviledges.
				<p>
_;
			echo $contents;

			echo '<div class="panel panel-default section-panel">' . R_NEWLINE;
				echo '<div class="panel-heading">' . R_NEWLINE;
				echo '<h1 class="panel-title">Manage</h1>' . R_NEWLINE;
				echo '</div>' . R_NEWLINE;
			echo '<div class="panel-body action-panel manage-sec">' . R_NEWLINE;

				echo '<div class="manage-sidebar">' . R_NEWLINE;
					echo '<ul class="nav man-nav">' .R_NEWLINE;
						echo '<li><a href="#" id="add-session">Add Session</a></li>' . R_NEWLINE;
						echo '<li><a href="#" id="add-class-ses">Add Student to Session</a></li>' . R_NEWLINE;
						echo '<li><a href="#" id="edit-user">Edit User</a></li>' . R_NEWLINE;
						echo '<li><a href="#" id="remove-user">Remove User</a></li>' . R_NEWLINE;
					echo '</ul>' . R_NEWLINE;
				echo '</div>' . R_NEWLINE;

				echo '<div class="manage-action">' . R_NEWLINE;
					echo '<div class="add-session">' . R_NEWLINE;
						//echo '<p>This section allows you to add a new session to the platform.</p>' . R_NEWLINE;
						echo form_open("post", "form-horizontal", "add-session-form");
						echo form_group();
						echo form_label("Session:", "session");
						echo form_col(9);
						echo form_input("session", "session", "", "Enter Session", "required", "Enter the session e.g 2016/2017") . R_NEWLINE;
						echo '</div>' . R_NEWLINE;
						echo form_group_close();
						echo form_group();
						echo form_label("Year:", "ses-year");
						echo form_col(9);
						echo form_input("year", "ses-year", "", "Enter Year", "required", "Enter the year for the specified session e.g 2016") . R_NEWLINE;
						echo '</div>' . R_NEWLINE;
						echo form_group_close();
						echo form_button("add-session", "primary", "Add Session", "", "submit");
						echo form_hidden("add-session", "add-session", "session");
						echo form_close();
					echo '</div>' . R_NEWLINE;

					echo '<div class="edit-user">' . R_NEWLINE;

                        $options = array(
                            "Select Type" => 1,
                            "Student" => 0,
                            "Staff" => 0
                        );
                        echo form_open();
                        echo form_group();
                        echo form_label("Type: ", "usertype");
                        echo form_col(9);
                        echo form_select("type", "usertype", $options);
                        echo '</div>' . R_NEWLINE;
                        echo '</div>' . R_NEWLINE;
                        echo '</form>' . R_NEWLINE;



					echo '</div>' . R_NEWLINE;

                    echo '<div class="remove-user">' . R_NEWLINE;

                    echo '</div>' . R_NEWLINE;

                    echo '<div class="add-to-session">' . R_NEWLINE;

                    echo '</div>' . R_NEWLINE;

			echo '</div>' . R_NEWLINE;
			echo '</div>' . R_NEWLINE;
			echo '</div>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;
	echo '</div>' . R_NEWLINE;
echo '</div>' . R_NEWLINE;


function getStudentForm($id = "") {
    if (!empty($id)) {
        $content = form_open_upload("post", "form-horizontal", $id);
    } else {
        $content = form_open_upload("post", "form-horizontal", "addstudent-admin");
    }
    $content .= form_group();
    $content .= form_label("Admission Number: ", "admin-no");
    $content .= form_col(9);
    $content .= form_input("admissionno", "admin-no", "", "Enter Admission Number", "required", "Enter Student's Admission Number");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("First Name: ", "firstname");
    $content .= form_col(9);
    $content .= form_input("firstname", "firstname", "", "First Name", "required", "Enter Student's First Name");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Last Name: ", "lastname");
    $content .= form_col(9);
    $content .= form_input("lastname", "lastname", "", "Last Name", "required", "Enter Student's Surname");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Middle Name: ", "middlename");
    $content .= form_col(9);
    $content .= form_input("middlename", "middlename", "", "Middle Name", "", "Enter Student's Middle Name (if any)");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Sex: ", "sex");
    $content .= form_col(9);
    $options = array(
        "Male" => 1,
        "Female" => 0
    );
    $content .= form_select("sex", "sex", $options);
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_date();
    $content .= form_group();
    $content .= form_label("No. In Class: ", "numinclass");
    $content .= form_col(9);
    $content .= form_number("numinclass", "numinclass", "required");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Address: ", "address");
    $content .= form_col(9);
    $content .= form_textarea("address", "address", "10", "5", "", "required");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Parent Number: ", "Parent Number");
    $content .= form_col(9);
    $content .= form_number("parentnumber", "parentnumber", "required");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Class: ", "class");
    $content .= form_col(9);
    $user = new User();
    $content .= form_select("class", "class", $user->getClasses());
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Class on Admission: ", "classonad");
    $content .= form_col(9);
    $user = new User();
    $content .= form_select("classonad", "classonad", $user->getClasses());
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= '<div class="passport-preview">' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= form_group();
    $content .= form_label("Passport: ", "passport");
    $content .= form_col(9);
    $content .= form_file("passport", "passport", "");
    $content .= '</div>' . R_NEWLINE;
    $content .= '</div>' . R_NEWLINE;
    $content .= '<div class="button-group">' . R_NEWLINE;
    $content .= form_button("submit", "primary", "Update Data", "", "submit");
    $content .= form_button("clear", "secondary", "Clear Fields", "clear-fields");
    $content .= '</div>' . R_NEWLINE;
    $content .= form_hidden("update-student", "update-student", "update-student");
    $content .= form_close();
    return $content;
}