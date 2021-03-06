<?php

require_once('db_utils.php');
require_once('../controllers/compile.php');

class User
{
	private static $dbh;

	function __construct() {
		  $dbUtils = new DBUtils();
		  self::$dbh = $dbUtils->getDBHandle();
	}

	function getID($username) {
		$id = "";
		foreach (self::$dbh->query("SELECT staffid FROM ".R_TABLE_STAFFS." WHERE username='$username'") as $row) {
			$id = $row['staffid'];
		}
		return $id;
	}

	function getPassword($username) {
		$pass = "invalid";
		$result = self::$dbh->query("SELECT password FROM res_staffs WHERE username='$username'");
		foreach ($result->fetchAll() as $key) {
			$pass = $key['password'];
		}
		return $pass;
	}

	function staffExists($username) {
		$result = self::$dbh->query("SELECT * FROM res_staffs WHERE username='$username'");
		if (count($result->fetchAll()) > 0) {
			return true;
		} else return false;
	}

	function studentExists($adnum) {
		$result = self::$dbh->query("SELECT * FROM ". R_TABLE_STUDENTS . " WHERE admissionno='$adnum'");
		if (count($result->fetchAll()) > 0) {
			return true;
		} else return false;
	}

	function getAccessLevel($staffid) {
		$accesslevel = "";
		foreach(self::$dbh->query("SELECT accesslevel FROM ". R_TABLE_STAFFS ." WHERE staffid='$staffid'") as $row) {
			$accesslevel = $row['accesslevel'];
		}
		return $accesslevel;
	}

	function getClasses(){
        foreach (self::$dbh->query("SELECT class, classid FROM " . R_TABLE_CLASSES) as $key) {
            $classes[$key['class']] = $key['class'];
        }
        return $classes;
    }

	function addStaff($data) {
		$this->hashPassword($data['password']);
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_STAFFS . " VALUES ('', ?, ?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $data['firstname'], PDO::PARAM_STR);
		$stmt->bindParam(2, $data['lastname'], PDO::PARAM_STR);
		$stmt->bindParam(3, $data['position'], PDO::PARAM_STR);
		$stmt->bindParam(4, $data['username'], PDO::PARAM_STR);
		$stmt->bindParam(5, $data['password'], PDO::PARAM_STR);
		$stmt->bindParam(6, $data['level'], PDO::PARAM_INT);
		return $stmt->execute();
	}

	function hashPassword(&$password) {
		$password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
	}

	function getClassID(&$class) {
		foreach (self::$dbh->query("SELECT classid FROM ".R_TABLE_CLASSES." WHERE class='$class'") as $row) {
			$class = $row['classid'];
		}
	}

	function getClassxID($class) {
		foreach (self::$dbh->query("SELECT classid FROM ".R_TABLE_CLASSES." WHERE class='$class'") as $row) {
			$class = $row['classid'];
		}
		return $class;
	}

	function getClassName($classid) {
		foreach (self::$dbh->query("SELECT class FROM ".R_TABLE_CLASSES." WHERE classid='$classid'") as $row) {
			$class = $row['class'];
		}
		return $class;	
	}

    function getSessionName($sessionid) {
        foreach (self::$dbh->query("SELECT session FROM ".R_TABLE_SESSIONS." WHERE sessionid='$sessionid'") as $row) {
            $session = $row['session'];
        }
        return $session;
    }

    function getTermName($termid) {
        foreach (self::$dbh->query("SELECT term FROM ".R_TABLE_TERMS." WHERE termid='$termid'") as $row) {
            $term = $row['term'];
        }
        return $term;
    }

	function getCurrentSession() {
		foreach(self::$dbh->query("SELECT sessionid FROM ". R_TABLE_SESSIONS . " ORDER BY sessionid DESC LIMIT 1") as $row) {
			$id = $row['sessionid'];
		}
		return $id;
	}

	function numberInClass($class, $numinclass) {
		$this->getClassID($class);
		$result = self::$dbh->query("SELECT * FROM ". R_TABLE_STUDENTS . " WHERE classid='$class' AND numberinclass='$numinclass'");
		if (count($result->fetchAll()) > 0) {
			return true;
		} else return false;
	}

	function addStudent($data) {
		$this->getClassID($data['class']);
		$this->getClassID($data['classonad']);
		$stmt = self::$dbh->prepare("INSERT INTO ". R_TABLE_STUDENTS . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $data['admissionno'], PDO::PARAM_STR);
		$stmt->bindParam(2, $data['firstname'], PDO::PARAM_STR);
		$stmt->bindParam(3, $data['lastname'], PDO::PARAM_STR);
		$stmt->bindParam(4, $data['middlename'], PDO::PARAM_STR);
		$stmt->bindParam(5, $data['sex'], PDO::PARAM_STR);
		$stmt->bindParam(6, $data['dateofbirth']);
		$stmt->bindParam(7, $data['address'], PDO::PARAM_STR);
		$stmt->bindParam(8, $data['numinclass'], PDO::PARAM_INT);
		$stmt->bindParam(9, $data['parentnumber'], PDO::PARAM_INT);
		$stmt->bindParam(10, $data['class'], PDO::PARAM_STR);
		$stmt->bindParam(11, $data['classonad'], PDO::PARAM_STR);
		$stmt->bindParam(12, $data['passport'], PDO::PARAM_STR);
		$stmt->execute();
		$sessionid = $this->getCurrentSession();
		if ($this->addClassSession($data['admissionno'], $data['class'], $sessionid)) {
			return true;
		} else return true;
	}

	function addClassSession($admissionno, $classid, $sessionid) {
		$stmt = self::$dbh->prepare("INSERT INTO ". R_TABLE_CLASS_SESSIONS . " VALUES ('', ?, ?, ?)");
		$stmt->bindParam(1, $admissionno, PDO::PARAM_STR);
		$stmt->bindParam(2, $classid, PDO::PARAM_INT);
		$stmt->bindParam(3, $sessionid, PDO::PARAM_INT);
		return $stmt->execute();
	}

	function getStudents($classid, $sessionid) {
		return self::$dbh->query("SELECT * FROM ".R_TABLE_STUDENTS." INNER JOIN " . R_TABLE_CLASS_SESSIONS ." ON ". R_TABLE_STUDENTS . ".admissionno=" . R_TABLE_CLASS_SESSIONS.".admissionno WHERE " . R_TABLE_CLASS_SESSIONS . ".classid='$classid' AND " . R_TABLE_CLASS_SESSIONS . ".sessionid='$sessionid' ORDER BY numberinclass", PDO::FETCH_ASSOC)->fetchAll();
	}

    function getStudentData($admissionno) {
        return self::$dbh->query("SELECT * FROM " . R_TABLE_STUDENTS . " WHERE admissionno = '$admissionno'", PDO::FETCH_ASSOC)->fetchAll();
    }

    function getCompiledStudents($classid, $sessionid, $termid) {
        return self::$dbh->query("SELECT * FROM " . R_TABLE_STUDENTS . " INNER JOIN " . R_TABLE_RESULTDATA . " on " . R_TABLE_STUDENTS . ".admissionno = " . R_TABLE_RESULTDATA . ".admissionno WHERE " . R_TABLE_RESULTDATA . ".classid='$classid' AND " . R_TABLE_RESULTDATA . ".sessionid='$sessionid' AND " . R_TABLE_RESULTDATA . ".termid='$termid'", PDO::FETCH_ASSOC)->fetchAll();
    }

	function isResultCompiled($data) {
		$admissionno = $data['admissionno'];
		$classid = $data['class'];
		$sessionid = $data['session'];
		$termid = $data['term'];
		$result = self::$dbh->query("SELECT COUNT(*) FROM ".R_TABLE_RESULTDATA." WHERE admissionno='$admissionno' AND classid='$classid' AND sessionid='$sessionid' AND termid='$termid'");
		if ($result->fetchColumn() > 0) {
			return true;
		} else return false;
	}

    function isScoreSheet($data) {
        $classid = $data['class'];
        $sessionid = $data['session'];
        $termid = $data['term'];
        $result = self::$dbh->query("SELECT COUNT(*) FROM ".R_TABLE_RESULTDATA." WHERE classid='$classid' AND sessionid='$sessionid' AND termid='$termid'");
        if ($result->fetchColumn() > 0) {
            return true;
        } else return false;
    }

	function getClass() {
		return self::$dbh->query("SELECT c_classid, class FROM ". R_TABLE_CLASS, PDO::FETCH_ASSOC)->fetchAll();	
	}

	function getSubjects($classid) {
		$class = $this->getClass();
		$classname = $this->getClassName($classid);
		$subjects = array();
		foreach($class as $cl) {
			$c_classid = $cl['c_classid'];
			if ($cl['class'] == substr($classname, 0, 5)) {
				$arr1 = self::$dbh->query("SELECT subjectid, subject FROM ". R_TABLE_SUBJECTS . " WHERE c_classid='$c_classid' ORDER BY subjectid DESC", PDO::FETCH_ASSOC)->fetchAll();
			} else if ($cl['class'] == "ALL") {
				if (!empty($arr1)) {
					$subjects = array_merge($arr1, self::$dbh->query("SELECT subjectid, subject FROM ". R_TABLE_SUBJECTS . " WHERE c_classid='$c_classid' ORDER BY subjectid DESC", PDO::FETCH_ASSOC)->fetchAll());
				}
			}
		}
		return $subjects;
	}

    function getAllSubjects() {
        return self::$dbh->query("SELECT * FROM ". R_TABLE_SUBJECTS, PDO::FETCH_ASSOC)->fetchAll();
    }

	function getSkills() {
		return self::$dbh->query("SELECT * FROM ". R_TABLE_SKILLS, PDO::FETCH_ASSOC)->fetchAll();
	}

	function getBehaviours() {
		return self::$dbh->query("SELECT * FROM ". R_TABLE_BEHAVIOURS, PDO::FETCH_ASSOC)->fetchAll();	
	}

	//Insert Functions

	function addSubjectsTaken($admissionno, $subjectids) {
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_SUBJECTSTAKEN . " VALUES ('', ?, ?)");
		$stmt->bindParam(1, $admissionno, PDO::PARAM_STR);
		$stmt->bindParam(2, $subjectids, PDO::PARAM_STR);
		$stmt->execute();
		return self::$dbh->lastInsertId();		
	}

	function getSubjectName($id) {
		foreach(self::$dbh->query("SELECT subject FROM ". R_TABLE_SUBJECTS . " WHERE subjectid='$id'") as $row) {
			$name = $row['subject'];
		}
		return $name;
	}

	function addScores($substakenid, $subjectid, $firsttest, $secondtest, $quiz, $project, $exam, $total, $average, $grade, $interpret) {
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_SCORES . " VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $substakenid, PDO::PARAM_INT);
		$stmt->bindParam(2, $subjectid, PDO::PARAM_INT);
		$stmt->bindParam(3, $firsttest, PDO::PARAM_INT);
		$stmt->bindParam(4, $secondtest, PDO::PARAM_INT);
		$stmt->bindParam(5, $quiz, PDO::PARAM_INT);
		$stmt->bindParam(6, $project, PDO::PARAM_INT);
		$stmt->bindParam(7, $exam, PDO::PARAM_INT);
		$stmt->bindParam(8, $total, PDO::PARAM_INT);
		$stmt->bindParam(9, $average);
		$stmt->bindParam(10, $grade, PDO::PARAM_STR);
		$stmt->bindParam(11, $interpret, PDO::PARAM_STR);
		return $stmt->execute();
	}


	function addExtraCurr($admissionno, $skillids, $behaviourids) {
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_EXTRACURR . " VALUES ('', ?, ?, ?)");
		$stmt->bindParam(1, $admissionno, PDO::PARAM_STR);
		$stmt->bindParam(2, $skillids, PDO::PARAM_STR);
		$stmt->bindParam(3, $behaviourids, PDO::PARAM_STR);
		$stmt->execute();
		return self::$dbh->lastInsertId();		
	}

	function getSkillName($id) {
		foreach(self::$dbh->query("SELECT skill FROM ". R_TABLE_SKILLS . " WHERE skillid='$id'") as $row) {
			$name = $row['skill'];
		}
		return $name;
	}

	function getBehaviourName($id) {
		foreach(self::$dbh->query("SELECT behaviour FROM ". R_TABLE_BEHAVIOURS . " WHERE behaviourid='$id'") as $row) {
			$name = $row['behaviour'];
		}
		return $name;
	}

	function addSkillSet($extraid, $skillid, $rating) {
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_SKILLSET . " VALUES ('', ?, ?, ?)");
		$stmt->bindParam(1, $extraid, PDO::PARAM_INT);
		$stmt->bindParam(2, $skillid, PDO::PARAM_INT);
		$stmt->bindParam(3, $rating, PDO::PARAM_STR);
		return $stmt->execute();
	}

	function addBehaviourSet($extraid, $behaviourid, $rating) {
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_BEHAVIOURSET . " VALUES ('', ?, ?, ?)");
		$stmt->bindParam(1, $extraid, PDO::PARAM_INT);
		$stmt->bindParam(2, $behaviourid, PDO::PARAM_INT);
		$stmt->bindParam(3, $rating, PDO::PARAM_STR);
		return $stmt->execute();
	}

	function addComments($formcomment, $formsignature, $housecomment, $housesignature, $guidancecomment, $guidancesignature, $principalcomment, $principalsignature){
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_COMMENTS . " VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $formcomment, PDO::PARAM_STR);
		$stmt->bindParam(2, $formsignature, PDO::PARAM_STR);
		$stmt->bindParam(3, $housecomment, PDO::PARAM_STR);
		$stmt->bindParam(4, $housesignature, PDO::PARAM_STR);
		$stmt->bindParam(5, $guidancecomment, PDO::PARAM_STR);
		$stmt->bindParam(6, $guidancesignature, PDO::PARAM_STR);
		$stmt->bindParam(7, $principalcomment, PDO::PARAM_STR);
		$stmt->bindParam(8, $principalsignature, PDO::PARAM_STR);
		$stmt->execute();
		return self::$dbh->lastInsertId();
	}


	function addResultSheet($admissionno, $substakenid, $extraid, $commentid) {
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_RESULTSHEET . " VALUES ('', ?, ?, ?, ?)");
		$stmt->bindParam(1, $admissionno, PDO::PARAM_STR);
		$stmt->bindParam(2, $substakenid, PDO::PARAM_INT);
		$stmt->bindParam(3, $extraid, PDO::PARAM_INT);
		$stmt->bindParam(4, $commentid, PDO::PARAM_INT);
		$stmt->execute();
		return self::$dbh->lastInsertId();
	}

	function addResultData($admissionno, $classid, $sessionid, $termid, $year, $sheetid, $scoreid) {
		$stmt = self::$dbh->prepare("INSERT INTO " . R_TABLE_RESULTDATA . " VALUES ('', ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $admissionno, PDO::PARAM_STR);
		$stmt->bindParam(2, $classid, PDO::PARAM_INT);
		$stmt->bindParam(3, $sessionid, PDO::PARAM_INT);
		$stmt->bindParam(4, $termid, PDO::PARAM_INT);
		$stmt->bindParam(5, $year, PDO::PARAM_STR);
		$stmt->bindParam(6, $sheetid, PDO::PARAM_INT);
		$stmt->bindParam(7, $scoreid, PDO::PARAM_INT);
		return $stmt->execute();
	}

	function getSheetId($admissionno, $classid, $sessionid, $termid) {
		foreach(self::$dbh->query("SELECT resultsheet FROM ". R_TABLE_RESULTDATA . " WHERE admissionno='$admissionno' AND classid='$classid' AND sessionid='$sessionid' AND termid='$termid'") as $row){
            $id = $row['resultsheet'];
		}
        return $id;
	}	

	function canCalcAvg($admissionno, $classid, $sessionid) {
		$result = self::$dbh->query("SELECT resultid FROM ". R_TABLE_RESULTDATA . " WHERE admissionno='$admissionno' AND classid='$classid' AND sessionid='$sessionid' AND termid='1';SELECT resultid FROM \". R_TABLE_RESULTDATA . \" WHERE admissionno='$admissionno' AND classid='$classid' AND sessionid='$sessionid' AND termid='2';");
		if (count($result->fetchAll()) > 0) {
			return true;
		} else{
            return false;
        }
	}

	function getSubstakenId($sheetid) {
		foreach(self::$dbh->query("SELECT substakenid FROM ". R_TABLE_RESULTSHEET . " WHERE sheetid='$sheetid'") as $row){
			$id = $row['substakenid'];
		}
        return $id;
	}

	function getExtraId($sheetid) {
		foreach(self::$dbh->query("SELECT extraid FROM ". R_TABLE_RESULTSHEET . " WHERE sheetid='$sheetid'") as $row){
			$id = $row['extraid'];
		}
		return $id;
	}

	function getCommentId($sheetid) {
		foreach(self::$dbh->query("SELECT commentid FROM ". R_TABLE_RESULTSHEET . " WHERE sheetid='$sheetid'") as $row){
			$id = $row['commentid'];
		}
		return $id;
	}

	function getFirstScore($data) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], 1);
		$substakenid = $this->getSubstakenId($sheetid);
		$subjectid = $data['subjectid'];
		foreach(self::$dbh->query("SELECT total FROM ". R_TABLE_SCORES . " WHERE substakenid='$substakenid' AND subjectid='$subjectid'") as $row){
			$total = $row['total'];
		}
		return $total;
	}

	function getSecondScore($data) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], 2);
		$substakenid = $this->getSubstakenId($sheetid);
		$subjectid = $data['subjectid'];
		foreach(self::$dbh->query("SELECT total FROM ". R_TABLE_SCORES . " WHERE substakenid='$substakenid' AND subjectid='$subjectid'") as $row){
			$total = $row['total'];
		}
		return $total;
	}

	function getScores($data, $subjectid) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$substakenid = $this->getSubstakenId($sheetid);
		return self::$dbh->query("SELECT * FROM ". R_TABLE_SCORES . " WHERE substakenid='$substakenid' AND subjectid='$subjectid'")->fetchAll();
	}

    function getTotalScore($data) {
        $sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
        $subjects = $this->getSubjects($data['classid']);
        $totalScores = array();
        $substakenid = $this->getSubstakenId($sheetid);
        foreach ($subjects as $subject) {
            $subjectid = $subject['subjectid'];
            foreach (self::$dbh->query("SELECT total FROM " . R_TABLE_SCORES . " WHERE substakenid='$substakenid' AND subjectid='$subjectid'") as $row) {
                $total = $row['total'];
            }
            $totalScores[$subject['subject']] = $total;
        }
        return $totalScores;
    }

	function getSkillRating($data, $skillid) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$extraid = $this->getExtraId($sheetid);
		foreach (self::$dbh->query("SELECT rating FROM ". R_TABLE_SKILLSET . " WHERE extraid='$extraid' AND skillid='$skillid'") as $row){
			$rating = $row['rating'];
		}
		if (!isset($rating)) {
			return "";
		} else {
			return $rating;
		}
	}

	function getBehaviourRating($data, $behaviourid) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$extraid = $this->getExtraId($sheetid);
		foreach (self::$dbh->query("SELECT rating FROM ". R_TABLE_BEHAVIOURSET . " WHERE extraid='$extraid' AND behaviourid='$behaviourid'") as $row){
			$rating = $row['rating'];
		}
		if (!isset($rating)) {
			return "";
		} else {
			return $rating;
		}
	}

	function getComments($data) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$commentid = $this->getCommentId($sheetid);
		return self::$dbh->query("SELECT * FROM ". R_TABLE_COMMENTS . " WHERE commentid='$commentid'", PDO::FETCH_ASSOC)->fetchAll();
	}

	//Update Functions

	function updateScores($data, $subjectid, $firsttest, $secondtest, $quiz, $project, $exam, $total, $average, $grade, $interpret) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$substakenid = $this->getSubstakenId($sheetid);
		$stmt = self::$dbh->prepare("UPDATE " . R_TABLE_SCORES . " SET firsttest = ?, secondtest = ?, quiz = ?, project = ?, exam = ?, total = ?, average = ?, grade = ?, interpretation = ? WHERE substakenid = ? AND subjectid = ?");
		$stmt->bindParam(1, $firsttest, PDO::PARAM_INT);
		$stmt->bindParam(2, $secondtest, PDO::PARAM_INT);
		$stmt->bindParam(3, $quiz, PDO::PARAM_INT);
		$stmt->bindParam(4, $project, PDO::PARAM_INT);
		$stmt->bindParam(5, $exam, PDO::PARAM_INT);
		$stmt->bindParam(6, $total, PDO::PARAM_INT);
		$stmt->bindParam(7, $average);
		$stmt->bindParam(8, $grade, PDO::PARAM_STR);
		$stmt->bindParam(9, $interpret, PDO::PARAM_STR);
		$stmt->bindParam(10, $substakenid, PDO::PARAM_INT);
		$stmt->bindParam(11, $subjectid, PDO::PARAM_INT);
		$stmt->execute();
	}	

	function skillNotFound($extraid, $skillid) {
		$result = self::$dbh->query("SELECT * FROM ". R_TABLE_SKILLSET . " WHERE extraid = '$extraid' AND skillid = '$skillid'");
		if (count($result->fetchAll()) > 0) {
			return false;
		} else return true;
	}

	function behaviourNotFound($extraid, $behaviourid) {
		$result = self::$dbh->query("SELECT * FROM ". R_TABLE_BEHAVIOURSET . " WHERE extraid = '$extraid' AND behaviourid = '$behaviourid'");
		if (count($result->fetchAll()) > 0) {
			return false;
		} else return true;
	}	

	function updateSkillSet($data, $skillid, $rating) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$extraid = $this->getExtraId($sheetid);
		if ($this->skillNotFound($extraid, $skillid)) {
			$this->addSkillSet($extraid, $skillid, $rating);
		} else {
			$stmt = self::$dbh->prepare("UPDATE " . R_TABLE_SKILLSET . " SET rating = ? WHERE extraid = ? AND skillid = ?");
			$stmt->bindParam(1, $rating, PDO::PARAM_STR);
			$stmt->bindParam(2, $extraid, PDO::PARAM_INT);
			$stmt->bindParam(3, $skillid, PDO::PARAM_INT);
			return $stmt->execute();
		}
	}


	function updateBehaviourSet($data, $behaviourid, $rating) {
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$extraid = $this->getExtraId($sheetid);
		if ($this->behaviourNotFound($extraid, $behaviourid)) {
			$this->addBehaviourSet($extraid, $behaviourid, $rating);
		} else {
			$stmt = self::$dbh->prepare("UPDATE " . R_TABLE_BEHAVIOURSET . " SET rating = ? WHERE extraid = ? AND behaviourid = ?");
			$stmt->bindParam(1, $rating, PDO::PARAM_STR);
			$stmt->bindParam(2, $extraid, PDO::PARAM_INT);
			$stmt->bindParam(3, $behaviourid, PDO::PARAM_INT);
			return $stmt->execute();
		}
	}

	function updateComments($data, $formcomment, $formsignature, $housecomment, $housesignature, $guidancecomment, $guidancesignature, $principalcomment, $principalsignature){
		$sheetid = $this->getSheetId($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid']);
		$commentid = $this->getCommentId($sheetid);
		$stmt = self::$dbh->prepare("UPDATE " . R_TABLE_COMMENTS . " SET formcomment = ?, formsignature = ?, housecomment = ?, housesignature = ?, guidancecomment = ?, guidancesignature = ?, principalcomment = ?, principalsignature = ? WHERE commentid = ?");
		$stmt->bindParam(1, $formcomment, PDO::PARAM_STR);
		$stmt->bindParam(2, $formsignature, PDO::PARAM_STR);
		$stmt->bindParam(3, $housecomment, PDO::PARAM_STR);
		$stmt->bindParam(4, $housesignature, PDO::PARAM_STR);
		$stmt->bindParam(5, $guidancecomment, PDO::PARAM_STR);
		$stmt->bindParam(6, $guidancesignature, PDO::PARAM_STR);
		$stmt->bindParam(7, $principalcomment, PDO::PARAM_STR);
		$stmt->bindParam(8, $principalsignature, PDO::PARAM_STR);
		$stmt->bindParam(9, $commentid, PDO::PARAM_INT);
		return $stmt->execute();
	}

}
