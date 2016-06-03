<?php

//=========================================
// File name 	: res_config.php
// Description 	: Configuration file for app

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Configuration file: defines configuration parameters for in-app usage
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

// --- INCLUDE FILES -----------------------

require_once('res_auth.php');

/**
* App name
*/
define('R_APP_TITLE', 'Result Compiler');

/**
* App description
*/
define('R_APP_DESCRIPTION', 'Result Compilation software for students');

/**
* App author
*/
define('R_APP_AUTHOR', 'Ozoka Lucky Orobo');

/**
* Main directory
*/
define('R_PATH_MAIN', 'http://localhost/rescom/app/');


/**
* Relative path to stylesheets directory
*/
define('R_PATH_STYLE_SHEETS', R_PATH_MAIN . 'styles/');

/**
* Relative path to javascript directory
*/
define('R_PATH_JSCRIPTS', R_PATH_MAIN . 'jscripts/');

/**
* Relative path to images directory
*/
define('R_PATH_IMAGES', R_PATH_MAIN . 'images/');

/**
* Relative path to resource directory
*/
define('R_PATH_RESOURCE', R_PATH_MAIN . 'resources/');

/**
* Login Page
*/
define('R_APP_LOGIN', R_PATH_MAIN . 'main/login.php');

/**
* App icon
*/
define('R_APP_ICON', R_PATH_IMAGES . 'appicon.ico');

/**
* Path to App-Wide CSS stylesheet
*/
define('R_APP_STYLE', R_PATH_STYLE_SHEETS . 'app.css');

/**
* New Line Character
*/
define ('R_NEWLINE', "\n");

/**
* Name of School
*/
define('R_SCH_NAME', '');

/**
* Configuration array
*/
$config = array(
		"info" => array(
			"login-info" => "In order to access the different sections of this app you must log in using the name and password provided to you by the system administrator",
			"login-message" => "Enter your username and password below"
			),
		"sidebar-texts" => array(
			"Dashboard",
			"Add Student",
			"Compile Result",
			"Results",
			"Print/Export"
			),
		"db" => array(
			"host" => "localhost",
			"database" => "res_db",
			"username" => "res_admin",
			"password" => "res_pass"
			)
	);

/**
* Database Table Names
*/

/**
* Admin table name
*/
define('R_TABLE_ADMIN', 'res_superadmin');

/**
* Staffs table name
*/
define('R_TABLE_STAFFS', 'res_staffs');

/**
* Class table name
*/
define('R_TABLE_CLASS', 'res_class');

/**
* Classes table name
*/
define('R_TABLE_CLASSES', 'res_classes');

/**
* Students table name
*/
define('R_TABLE_STUDENTS', 'res_students');

/**
* Subjects table name
*/
define('R_TABLE_SUBJECTS', 'res_subjects');

/**
* Session table name
*/
define('R_TABLE_SESSIONS', 'res_sessions');

/**
* Class Sessions table name
*/
define('R_TABLE_CLASS_SESSIONS', 'res_std_class_sessions');

/**
* Term table name
*/
define('R_TABLE_TERMS', 'res_terms');

/**
* Result data table name
*/
define('R_TABLE_RESULTDATA', 'result_data');

/**
* Skills table name
*/
define('R_TABLE_SKILLS', 'res_skills');

/**
* Behaviour table name
*/
define('R_TABLE_BEHAVIOURS', 'res_behaviours');

/**
* Result Sheet table name
*/
define('R_TABLE_RESULTSHEET', 'result_sheet');

/**
* Score Sheet table name
*/
define('R_TABLE_SCORESHEET', 'res_scoresheet');

/**
* Subjects Taken table name
*/
define('R_TABLE_SUBJECTSTAKEN', 'res_subjectstaken');

/**
* Scores table name
*/
define('R_TABLE_SCORES', 'res_scores');

/**
* Extra Curricula table name
*/
define('R_TABLE_EXTRACURR', 'res_extracurr');

/**
* Skill Set table name
*/
define('R_TABLE_SKILLSET', 'res_skillset');

/**
* Behaviour Set table name
*/
define('R_TABLE_BEHAVIOURSET', 'res_behaviourset');

/**
* Comments table name
*/
define('R_TABLE_COMMENTS', 'res_comments');