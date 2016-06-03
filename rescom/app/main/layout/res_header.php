<?php

//=========================================
// File name 	: res_header.php
// Description 	: Main header for app's components

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Main header file for app's components
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

echo '<div class="container header">' . R_NEWLINE;
echo '<div class="header-left">' . R_NEWLINE;
echo '<img src="'.R_PATH_IMAGES.'logo.png" height="60" width="60">' . R_NEWLINE;
echo '<span id="sch-name">'.R_SCH_NAME.', Asaba</span>' . R_NEWLINE;
echo '</div>' . R_NEWLINE;
echo '<div class="header-right">' . R_NEWLINE;
echo '<ul>' . R_NEWLINE;
echo '<li><span id="date-time"></span></li>' . R_NEWLINE;
echo '<li id="profile"><span class="glyphicon glyphicon-user"><span class="glyphicon glyphicon-chevron-down"></span>' . R_NEWLINE;
echo '<ul id="sub-profile">' . R_NEWLINE;
echo '<li><a href="?action=editprofile"><span class="glyphicon glyphicon-user"></span> My Account</a></li>' . R_NEWLINE;
echo '<li><a href="?action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>' . R_NEWLINE;
echo '</ul>' . R_NEWLINE;
echo '</li>' . R_NEWLINE;
echo '</ul>' . R_NEWLINE;
echo '</div>' . R_NEWLINE;
echo '</div>' . R_NEWLINE;