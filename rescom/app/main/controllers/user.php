<?php

require_once('db_utils.php');

class User
{
	private static $dbh;

	function __construct() {
		  $dbUtils = new DBUtils();
		  self::$dbh = $dbUtils->getDBHandle();
	}


	function getID($username) {
		$id = "";
		$dbh->query("SELECT ");
	}


	function getPassword($username) {
		$pass = "invalid";
		$result = self::$dbh->query("SELECT password FROM res_staffs WHERE username='$username'");
		foreach ($result->fetchAll() as $key) {
			$pass = $key['password'];
		}
		return $pass;
	}

	function userExists($username) {
		$result = self::$dbh->query("SELECT * FROM res_staffs WHERE username='$username'");
		if (count($result->fetchAll()) > 0) {
			return true;
		} else return false;
	}

}