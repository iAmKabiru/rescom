<?php
//=========================================
// File name 	: res_auth.php
// Description 	: Define access levels for each
//					component page in app
//					0 = Anonymous User
//					1 = Basic User
//					5 = System Wide Administrator
// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Configuration file: defines access levels for each component page
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

// ************************************************************
// SECURITY WARNING :
// AFTER MOD, THIS FILE WILL BE READ ONLY
// ************************************************************

/**
* Required user's level to view main index page
*/
define ('R_ANONYMOUS', 0);

/**
* Required user's level to access to read only
*/
define ('R_READ_ONLY_LEVEL', 1);

/**
 * Required user's level to add students only
*/
define ('R_ADD_STUDENTS_LEVEL', 2);

/**
* Required user's level to add / delete students
*/
define ('R_ADD_DELETE_STUDENT_LEVEL', 3);

/**
* Required user's level to compile result
*/
define ('R_COMPILE_RES_LEVEL', 4);

/**
* Required user's level for system wide privildeges
*/
define ('R_SUPER_ADMIN_LEVEL', 5);