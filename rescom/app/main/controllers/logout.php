<?php

//=========================================
// File name 	: logout.php
// Description 	: Logs user out of system

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Logs user out of system
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

// --- INCLUDE FILES -----------------------

require_once('../config/res_config.php');

if (!isset($_SESSION)) {
	session_start();
}

unset($_SESSION['username']);

session_unset();

session_destroy();

header("Location: " . R_PATH_MAIN);