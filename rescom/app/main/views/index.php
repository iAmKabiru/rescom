<?php 

//=========================================
// File name 	: index.php
// Description 	: Main app page

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Main page for the app
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

// --- INCLUDE FILES -----------------------

session_start();

require_once('../config/res_config.php');
require_once('../layout/res_page_head.php');

if (isset($_SESSION['username'])) {
	require_once('../layout/res_header.php');
	require_once('../layout/res_sidebar.php');

	if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['action'])) {
		switch ($_GET['action']) {
			case 'logout':
				require_once('../controllers/logout.php');		
				break;
			case 'dashboard':
				require_once('dashboard.php');
				break;
			case 'newuser':
				require_once('adduser.php');
				break;
			case 'compile':
				require_once('compile_result.php');
				break;
			case 'results':
				require_once('results.php');
				break;
			case 'manage':
				require_once('manage.php');
				break;
			case 'export':
				
				break;
			case 'editprofile':

				break;
			default:
				require_once('dashboard.php');
				break;
		}
	} else {
		require_once('dashboard.php');
	}

} else {
	require_once('../controllers/logout.php');
}

require_once('../layout/res_footer.php');