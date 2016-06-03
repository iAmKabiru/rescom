<?php

//=========================================
// File name 	: res_sidebar.php
// Description 	: Left Navigation bar for app's components

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Left Navigation bar for app's components
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

require_once("../models/user.php");

$user = new User();

echo '<div class="container side-bar">' . R_NEWLINE;
echo '<ul class="nav nav-pills nav-stacked">' . R_NEWLINE;
echo '<li class="current"><a href="?action=dashboard"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>' . R_NEWLINE;

$level = $user->getAccessLevel($user->getID($_SESSION['username']));

if ($level == R_SUPER_ADMIN_LEVEL) {
	echo '<li><a href="?action=newuser"><span class="glyphicon glyphicon-user"></span> Add User</a></li>' . R_NEWLINE;	
} else {
	echo '<li><a href="?action=newuser"><span class="glyphicon glyphicon-user"></span> Add Student</a></li>' . R_NEWLINE;
}
echo '<li><a href="?action=compile"><span class="glyphicon glyphicon-cog"></span> Compile Result</a></li>' . R_NEWLINE;
echo '<li><a href="?action=results"><span class="glyphicon glyphicon-stats"></span> Results</a></li>' . R_NEWLINE;
if ($level == R_SUPER_ADMIN_LEVEL) {
	echo '<li><a href="?action=manage"><span class="glyphicon glyphicon-cog"></span> Manage</a></li>' . R_NEWLINE;
}
echo '</ul>' . R_NEWLINE;
echo '</div>' . R_NEWLINE;