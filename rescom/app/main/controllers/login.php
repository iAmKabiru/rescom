<?php

//=========================================
// File name 	: login.php
// Description 	: Login class for user

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Performs login operations on Login View
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

// --- INCLUDE FILES -----------------------

require_once('../models/user.php');
require_once('utility.php');

/**
* 
*/
class Login
{
	private $user;

	function __construct() {
		  $this->user = new User();
	}

	function authUser($username, $password) {
		$staffExists = $this->user->staffExists($username);

		if (!$staffExists) {
			return false;
		}

		$storedPass = $this->user->getPassword($username);
		if ($storedPass == "invalid") {
			return false;
		}
		if (password_verify($password, $storedPass)) {
			return true;
		} else {
			return false;
		}
	}

}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['auth'])) {
	$login = new Login();
	$username = sanitize_str($_POST['username']);
	$password = sanitize_str($_POST['password']);
	if ($login->authUser($username, $password)) {
		if (!isset($_SESSION['username'])) {
			session_start();
			$_SESSION['username'] = $username;
		}
		echo "success";
	} else {
		echo "failed";
	}
}