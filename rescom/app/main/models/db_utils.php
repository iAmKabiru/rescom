<?php


// --- INCLUDE FILES -----------------------

require_once('../config/res_config.php');

/**
* 
*/
class DBUtils
{
	private static $dbh;

	function __construct()
	{
		try {
			self::$dbh = new PDO("mysql:host=localhost;dbname=res_db", "root", "this.mysql");	
		} catch (PDOException $ex) {
			die($ex->getMessage());	
		}
	}

	function getDBHandle() {
		return DBUtils::$dbh;
	}

	function __destruct() {
		DBUtils::$dbh = NULL;
	}

}