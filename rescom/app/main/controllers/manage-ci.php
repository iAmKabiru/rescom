<?php

//=========================================
// File name 	: dashboard.php
// Description 	: Interacts with the User View and User Model to perform operations

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Interacts with the User View and User Model to perform operations
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

require_once('../models/user.php');
require_once('../models/manage-md.php');
require_once('utility.php');

$user = new User();
$manage = new Manage();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['add-session'])) {
		$data = sanitize_array($_POST);

		if ($manage->sessionExists($data)) {
			echo 'failed';
			exit();
		}

		if ($manage->addSession($data)) {
			echo 'success';
			exit();
		}
	}

}