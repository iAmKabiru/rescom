<?php

require_once('db_utils.php');

class Manage
{
	private static $dbh;

	function __construct() {
		  $dbUtils = new DBUtils();
		  self::$dbh = $dbUtils->getDBHandle();
	}

	function sessionExists($data) {
		$session = $data['session'];
		$result = self::$dbh->query("SELECT * FROM " . R_TABLE_SESSIONS ." WHERE session='$session'");
		if (count($result->fetchAll()) > 0) {
			return true;
		} else {
			return false;
		}
	}

	function addTerms($sessionid) {
		$stmt = self::$dbh->prepare("INSERT INTO ". R_TABLE_TERMS . " VALUES ('', ?, ?)");
		$stmt->bindParam(1, $sessionid, PDO::PARAM_INT);
		$stmt->bindParam(2, $term, PDO::PARAM_STR);
		$term = "1st";
		$stmt->execute();
		$term = "2nd";
		$stmt->execute();
		$term = "3rd";
		return $stmt->execute();
	}

	function addSession($data) {
		$session = $data['session'];
		$year = $data['year'];
		$classes = "1,2,3,4,5,6";
		$stmt = self::$dbh->prepare("INSERT INTO ". R_TABLE_SESSIONS . " VALUES ('', ?, ?, ?)");
		$stmt->bindParam(1, $classes, PDO::PARAM_INT);
		$stmt->bindParam(2, $session, PDO::PARAM_STR);
		$stmt->bindParam(3, $year, PDO::PARAM_STR);
		$stmt->execute();
		if ($this->addTerms(self::$dbh->lastInsertId())) {
			return true;
		} else return false;
	}

}