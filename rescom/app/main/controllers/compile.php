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

// --- INCLUDE FILES -----------------------

require_once('../models/db_utils.php');
require_once('../models/user.php');
require_once('utility.php');
require_once('../helpers/form_helper.php');

/**
* 
*/
class Compile
{
	
	private static $dbh;

	function __construct()
	{
		$dbUtils = new DBUtils();
	  	self::$dbh = $dbUtils->getDBHandle();
	}

	function getSessions() {
		$result = self::$dbh->query("SELECT sessionid, session, year FROM " . R_TABLE_SESSIONS, PDO::FETCH_ASSOC)->fetchAll();
		return $result;
	}

	function getYearBySession($sessionid) {
		foreach(self::$dbh->query("SELECT year FROM " . R_TABLE_SESSIONS . " WHERE sessionid='$sessionid'", PDO::FETCH_ASSOC) as $row) {
			$year = $row['year'];
		}
		return $year;
	}

	function getTermID($sessionid, $term) {
		$termid = "";
		foreach(self::$dbh->query("SELECT termid FROM " . R_TABLE_TERMS . " WHERE sessionid='$sessionid' AND term='$term'", PDO::FETCH_ASSOC) as $row) {
			$termid = $row['termid'];
		}
		return $termid;
	}

	function getTermName($id) {
		foreach(self::$dbh->query("SELECT term FROM ". R_TABLE_TERMS . " WHERE termid='$id'") as $row) {
			$name = $row['term'];
		}
		return $name;
	}

}

$compile = new Compile();
$user = new User();


if ($_SERVER['REQUEST_METHOD'] == "POST") {

	if (isset($_POST['getsessions'])) {
		$data = sanitize_array($_POST);
		//$user->getClassID($data['classid']);
		$sessions = $compile->getSessions();
		echo json_encode($sessions);
	}

	if (isset($_POST['getyear'])) {
		$data = sanitize_array($_POST);
		$year = $compile->getYearBySession($data['sessionid']);
		echo $year;
	}

	if (isset($_POST['compileform'])) {
		if (isset($_SESSION['form1'])) {
			unset($_SESSION['form1']);
		}
		$data = sanitize_array($_POST);
		$subjects = $user->getSubjects($data['classid']);

		$content = <<<_DOC
		<p>
			Enter the student's result data into the fields below and click on continue when done.
		</p>
		<p>
			<strong>Please before continuing, endeavour to double check the student's result data to avoid omissions and/or mistakes.</strong>
		</p>
		<form action="" method="post" class="form-horizontal" id="compile-form1" onsubmit="process_form1(this)">
		<table class="table table-hover" id="form-table">
		<thead>
			<th scope="col">Subject / Max. Score</th>
			<th scope="col">First Test</th>
			<th scope="col">Second Test</th>
			<th scope="col">Quiz</th>
			<th scope="col">Project</th>
			<th scope="col">Exam</th>
			<th scope="col">Total</th>
			<th scope="col">Annual Average</th>
			<th scope="col">Letter Grade</th>
			<th scope="col">Interpretation of Grades</th>
		</thead>
		<tbody>
_DOC;
		$form = $content;

		$form .=  form_hidden("admissionno", "admissionno", $data['admissionno']);
		$form .=  form_hidden("classid", "classid", $data['classid']);
		$form .=  form_hidden("sessionid", "sessionid", $data['sessionid']);
		$form .=  form_hidden("termid", "termid", $data['termid']);
		if ($compile->getTermName($data['termid']) == "3rd") {
			if ($user->canCalcAvg($data['admissionno'], $data['classid'], $data['sessionid'])) {
				$form .=  form_hidden("calcAvg", "calcAvg", "true");
			} else {
				$form .=  form_hidden("calcAvg", "calcAvg", "false");
			}
		} else {
			$form .=  form_hidden("calcAvg", "calcAvg", "false");
		}
		$form .=  form_hidden("year", "year", $data['year']);
		$form .=  form_hidden("compileform1", "compileform1", "form1");

		foreach ($subjects as $subject) {
			$form .=  '<tr>' . R_NEWLINE;
				$form .=  '<td class="subject"><b>'.$subject['subject'].'</b></td>' . R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-firsttest");
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-secondtest");
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-quiz");
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-project");
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-exam");
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-total" class="total" onkeypress="return isNumberKey(this)" onfocus="getTotal(this)" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-average" class="average" onkeypress="return isNumberKey(this)" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-grade" class="grade" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-interpret" class="inter" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
			$form .=  '</tr>' . R_NEWLINE;
		}
		$form .=  '<tr>' . R_NEWLINE;
        /*
			for ($i = 0; $i < 4; $i++) {
				$form .=  '<td></td>' . R_NEWLINE;
			}
			$form .=  '<td colspan="2">' .R_NEWLINE;
				$form .=  '<b>Overall Total: </b>' . R_NEWLINE;
			$form .=  '</td>' .R_NEWLINE;
        /*
			$form .=  '<td>' .R_NEWLINE;
				$form .=  '<input type="text" class="overalltotal" onkeypress="return isNumberKey(this)" onfocus="getOverallTotal(this)" />' . R_NEWLINE;
			$form .=  '</td>' .R_NEWLINE;
        */
		$form .=  '<tr>' . R_NEWLINE;
		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td colspan="10"><button style="float: right;" type="submit" class="btn btn-primary btn-md" id="continue">Continue</button></td>' . R_NEWLINE;
		$form .=  '<tr>' . R_NEWLINE;
		$form .=  '</form>' . R_NEWLINE;
		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;

		echo $form;
	}

	if (isset($_POST['compileform1'])) {

        if (isset($_SESSION['form1'])) {
            unset($_SESSION['form1']);
        }

		$data = $_POST;
		$skills = $user->getSkills();
		$behaviours = $user->getBehaviours();

		$content = <<<_DOC
		<p>
			This form allows you to enter data for students' such as <b>Skills</b>, <b>Behaviour</b>, <b>Regularity and Punctuality</b>, and <b>Staffs' Comments</b>.
			<br />Enter the student's data into the fields below and click on compile when done.
		</p>
		<p>
			<strong>Please before continuing, endeavour to double check the student's result data to avoid omissions and/or mistakes.</strong>
		</p>
_DOC;
		$form = $content;

		$content = <<<_END
		<form action="" method="post" class="form-horizontal" id="compile-form2" onsubmit="process_form2(this)">
			<h3>Skills</h3>
			<table class="table table-hover" id="form-table">
			<thead>
				<th scope="col">Skill</th>
				<th scope="col" style="text-align: center;">A</th>
				<th scope="col" style="text-align: center;">B</th>
				<th scope="col" style="text-align: center;">C</th>
				<th scope="col" style="text-align: center;">D</th>
				<th scope="col" style="text-align: center;">E</th>
			</thead>
			<tbody>
_END;
		$form .= $content;

		foreach ($skills as $skill) {
			$form .=  '<tr>' . R_NEWLINE;
				$form .=  '<td class="skill"><b>'.$skill['skill'].'</b></td>' . R_NEWLINE;
				//$form .=  form_hidden("skillid", "skillid", $skill['skillid']);
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$skill['skill'].'" value="A" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$skill['skill'].'" value="B" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$skill['skill'].'" value="C" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$skill['skill'].'" value="D" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$skill['skill'].'" value="E" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
			$form .=  '</tr>' . R_NEWLINE;
		}
		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;


		$content = <<<_HERE
			<h3>Behaviour</h3>
			<table class="table table-hover" id="form-table">
			<thead>
				<th scope="col">Behaviour</th>
				<th scope="col" style="text-align: center;">A</th>
				<th scope="col" style="text-align: center;">B</th>
				<th scope="col" style="text-align: center;">C</th>
				<th scope="col" style="text-align: center;">D</th>
				<th scope="col" style="text-align: center;">E</th>
			</thead>
			<tbody>
_HERE;
		$form .=  $content;

		foreach ($behaviours as $behaviour) {
			$form .=  '<tr>' . R_NEWLINE;
				$form .=  '<td class="behaviour"><b>'.$behaviour['behaviour'].'</b></td>' . R_NEWLINE;
				//$form .=  form_hidden("behaviourid", "behaviourid", $behaviour['behaviourid']);
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="A" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="B" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="C" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="D" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
				$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="E" required="required" />' . R_NEWLINE;
				$form .=  '</td>'. R_NEWLINE;
			$form .=  '</tr>' . R_NEWLINE;
		}
		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;


		$content = <<<_HERE
			<h3>Staffs' Comments</h3>
			<p>Select a comment from options A - D for each of the staff if applicable</p>
			<table class="table table-hover" id="form-table">
			<thead>
				<th scope="col">Staff</th>
				<th scope="col">Comment</th>
				<th scope="col">Signature</th>
				<th scope="col" style="text-align: center;">A</th>
				<th scope="col" style="text-align: center;">B</th>
				<th scope="col" style="text-align: center;">C</th>
				<th scope="col" style="text-align: center;">D</th>
				<th scope="col" style="text-align: center;">E</th>
			</thead>
			<tbody>
_HERE;
		$form .=  $content;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>Form Master/Mistress\' Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("formcomment", "formcomment", "", "", "required", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("formsignature", "signature", "", "", "required", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>House Master\'s Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("housecomment", "housecomment", "", "", "", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("housesignature", "signature", "", "", "", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>Guidance\'s Counsellor\'s Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("guidancecomment", "guidancecomment", "", "", "", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("guidancesignature", "signature", "", "", "", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>Principal\'s Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("principalcomment", "principalcomment", "", "", "required", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("principalsignature", "signature", "", "", "required", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td colspan="10"><button style="float: right;" type="submit" class="btn btn-primary btn-md" id="compile-result">Compile Result</button></td>' . R_NEWLINE;
		$form .=  '<tr>' . R_NEWLINE;

		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;

		session_start();

		if (isset($_SESSION['form1'])) {
			unset($_SESSION['form1']);
		}

		$_SESSION['form1'] = $data;
		$form .=  form_hidden("compile", "compile", "result");

	$form .=  '</form>' . R_NEWLINE;

	echo $form;
	}

	if (isset($_POST['compile'])) {
		session_start();
		$extra = sanitize_array($_POST);
		$result = $_SESSION['form1'];
		$subjects = $user->getSubjects($result['classid']);
		$skills = $user->getSkills();
		$behaviours = $user->getBehaviours();

		//get the subjectids
		$i = 0;
		foreach($subjects as $subject) {
			//Get the subjectids
			if ($i == 0) {
				$subjectids = $subject['subjectid'] . ', ';
			} else {
				if ($i == sizeof($subjects) - 1) {
					$subjectids .= $subject['subjectid'];	
				} else {
					$subjectids .= $subject['subjectid'] . ', ';
				}
			}
			$i++;
		}

		//get the skillids
		$i = 0;
		foreach($skills as $skill) {
			if ($i == 0) {
				$skillids = $skill['skillid'] . ', ';
			} else {
				if ($i == sizeof($skills) - 1) {
					$skillids .= $skill['skillid'];	
				} else {
					$skillids .= $skill['skillid'] . ', ';
				}
			}
			$i++;
		}


		//get the behaviourids
		$i = 0;
		foreach($behaviours as $behaviour) {
			if ($i == 0) {
				$behaviourids = $behaviour['behaviourid'] . ', ';
			} else {
				if ($i == sizeof($behaviours) - 1) {
					$behaviourids .= $behaviour['behaviourid'];	
				} else {
					$behaviourids .= $behaviour['behaviourid'] . ', ';
				}
			}
			$i++;
		}
		
		//Create data for subjects taken

		$substakenid = $user->addSubjectsTaken($result['admissionno'], $subjectids);

		//Subjects taken has been created
		//...add scores to subjects

		
		foreach (explode(',', $subjectids) as $id) {
			$subject = explode(' ', $user->getSubjectName($id))[0];
			$user->addScores($substakenid, $id, 
							$result[$subject . '-firsttest'],
							$result[$subject . '-secondtest'],
							$result[$subject . '-quiz'],
							$result[$subject . '-project'],
							$result[$subject . '-exam'],
							$result[$subject . '-total'],
							$result[$subject . '-average'],
							$result[$subject . '-grade'],
							$result[$subject . '-interpret']
							);
		}

		//Create data for extra-curricula

		$extraid = $user->addExtraCurr($result['admissionno'], $skillids, $behaviourids);

		//ExtraCurr data has been created
		//...add skills and behaviour of student

		foreach (explode(', ', $skillids) as $id) {
			$skill = $user->getSkillName($id);
			$skillx = str_replace(' ', '_', $skill);
			//echo $extra[$skillx];
			if (isset($extra[$skillx])) {
				$user->addSkillSet($extraid, $id, $extra[$skillx]);
			}
		}

		foreach (explode(', ', $behaviourids) as $id) {
			$behaviour = $user->getBehaviourName($id);
			$behaviourx = str_replace(' ', '_', $behaviour);
			if (isset($extra[$behaviourx])) {
				$user->addBehaviourSet($extraid, $id, $extra[$behaviourx]);
			}
		}

		//Skills set and Behaviour set data created
		//...add comments

		$commentid = $user->addComments($extra['formcomment'],
							$extra['formsignature'],
							$extra['housecomment'],
							$extra['housesignature'],
							$extra['guidancecomment'],
							$extra['guidancesignature'],
							$extra['principalcomment'],
							$extra['principalsignature']
							);
		//Create result sheet data
		
		$resultid = $user->addResultSheet($result['admissionno'], $substakenid, $extraid, $commentid);

		if ($user->addResultData($result['admissionno'], $result['classid'], $result['sessionid'], $result['termid'], $result['year'], $resultid, 0))
		{
			echo "success";
		} else {
			echo "failed";
		}
		

	}

	if (isset($_POST['computeavg'])) {
		$data = sanitize_array($_POST);
		$firstscore = $user->getFirstScore($data);
		$secondscore = $user->getSecondScore($data);
		$average = ($firstscore + $secondscore + $data['score']) / 3;
		echo round($average, 2);
	}

	if (isset($_POST['editresult'])) {
		if (isset($_SESSION['form1'])) {
			unset($_SESSION['form1']);
		}
		$data = sanitize_array($_POST);
		$subjects = $user->getSubjects($data['classid']);

		$content = <<<_DOC
		<p>
			Enter the student's result data into the fields below and click on continue when done.
		</p>
		<p>
			<strong>Please before continuing, endeavour to double check the student's result data to avoid omissions and/or mistakes.</strong>
		</p>
		<form action="" method="post" class="form-horizontal" id="edit-form1" onsubmit="process_edit_form1(this)">
		<table class="table table-hover" id="form-table">
		<thead>
			<th scope="col">Subject / Max. Score</th>
			<th scope="col">First Test</th>
			<th scope="col">Second Test</th>
			<th scope="col">Quiz</th>
			<th scope="col">Project</th>	
			<th scope="col">Exam</th>
			<th scope="col">Total</th>
			<th scope="col">Annual Average</th>
			<th scope="col">Letter Grade</th>
			<th scope="col">Interpretation of Grades</th>
		</thead>
		<tbody>
_DOC;
		$form = $content;

		$form .=  form_hidden("admissionno", "admissionno", $data['admissionno']);
		$form .=  form_hidden("classid", "classid", $data['classid']);
		$form .=  form_hidden("sessionid", "sessionid", $data['sessionid']);
		$form .=  form_hidden("termid", "termid", $data['termid']);
		if ($compile->getTermName($data['termid']) == "3rd") {
			if ($user->canCalcAvg($data['admissionno'], $data['classid'], $data['sessionid'], $data['termid'])) {
				$form .=  form_hidden("calcAvg", "calcAvg", "true");
			} else {
				$form .=  form_hidden("calcAvg", "calcAvg", "false");
			}
		} else {
			$form .=  form_hidden("calcAvg", "calcAvg", "false");
		}
		$form .=  form_hidden("year", "year", $data['year']);
		$form .=  form_hidden("editform1", "editform1", "form1");

		foreach ($subjects as $subject) {
			$scores = $user->getScores($data, $subject['subjectid']);
			foreach ($scores as $score) {
				$form .=  '<tr>' . R_NEWLINE;
				$form .=  '<td class="subject">
						<b>'.$subject['subject'].'</b>
						<input type="hidden" class="subjectid" value="'.$subject['subjectid'].'"
						</td>' . R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-firsttest", '', $score['firsttest']);
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-secondtest", '', $score['secondtest']);
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-quiz", '', $score['quiz']);
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-project", '', $score['project']);
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  form_input_number(explode(" ", $subject['subject'])[0] . "-exam", '', $score['exam']);
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-total" class="total" onkeypress="return isNumberKey(this)" onfocus="getTotal(this)" value="'.$score["total"].'" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-average" class="average" onkeypress="return isNumberKey(this)" value="'.$score["average"].'" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-grade" class="grade" value="'.$score["grade"].'" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
				$form .=  '<td>' .R_NEWLINE;
					$form .=  '<input type="text"  name="'.explode(" ", $subject['subject'])[0].'-interpret" class="inter" value="'.$score["interpretation"].'" />' . R_NEWLINE;
				$form .=  '</td>' .R_NEWLINE;
			$form .=  '</tr>' . R_NEWLINE;	
			}
		}
		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td colspan="10"><button style="float: right;" type="submit" class="btn btn-primary btn-md" id="continue">Continue</button></td>' . R_NEWLINE;
		$form .=  '<tr>' . R_NEWLINE;
		$form .=  '</form>' . R_NEWLINE;
		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;

		echo $form;
	}

	if (isset($_POST['editform1'])) {
        if (isset($_SESSION['form1'])) {
            unset($_SESSION['form1']);
        }
		$data = sanitize_array($_POST);
		$skills = $user->getSkills();
		$behaviours = $user->getBehaviours();

		$content = <<<_DOC
		<p>
			This form allows you to enter data for students' such as <b>Skills</b>, <b>Behaviour</b>, <b>Regularity and Punctuality</b>, and <b>Staffs' Comments</b>.
			<br />Enter the student's data into the fields below and click on compile when done.
		</p>
		<p>
			<strong>Please before continuing, endeavour to double check the student's result data to avoid omissions and/or mistakes.</strong>
		</p>
_DOC;
		$form = $content;

		$content = <<<_END
		<form action="" method="post" class="form-horizontal" id="edit-form2" onsubmit="process_edit_form2(this)">
			<h3>Skills</h3>
			<table class="table table-hover" id="form-table">
			<thead>
				<th scope="col">Skill</th>
				<th scope="col" style="text-align: center;">A</th>
				<th scope="col" style="text-align: center;">B</th>
				<th scope="col" style="text-align: center;">C</th>
				<th scope="col" style="text-align: center;">D</th>
				<th scope="col" style="text-align: center;">E</th>
			</thead>
			<tbody>
_END;
		$form .= $content;

		foreach ($skills as $skill) {
			$skillrating = $user->getSkillRating($data, $skill['skillid']);
			$form .=  '<tr>' . R_NEWLINE;
				$form .=  '<td class="skill"><b>'.$skill['skill'].'</b></td>' . R_NEWLINE;
				if ($skillrating == 'A') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="A" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;	
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="A" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($skillrating == 'B') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="B" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;	
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="B" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($skillrating == 'C') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="C" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;					
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="C" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($skillrating == 'D') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="D" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;					
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="D" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($skillrating == 'E') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="E" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$skill['skill'].'" value="E" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

			$form .=  '</tr>' . R_NEWLINE;
		}
		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;


		$content = <<<_HERE
			<h3>Behaviour</h3>
			<table class="table table-hover" id="form-table">
			<thead>
				<th scope="col">Behaviour</th>
				<th scope="col" style="text-align: center;">A</th>
				<th scope="col" style="text-align: center;">B</th>
				<th scope="col" style="text-align: center;">C</th>
				<th scope="col" style="text-align: center;">D</th>
				<th scope="col" style="text-align: center;">E</th>
			</thead>
			<tbody>
_HERE;
		$form .=  $content;

		foreach ($behaviours as $behaviour) {
			$behaviourrating = $user->getBehaviourRating($data, $behaviour['behaviourid']);
			$form .=  '<tr>' . R_NEWLINE;
				$form .=  '<td class="behaviour"><b>'.$behaviour['behaviour'].'</b></td>' . R_NEWLINE;
				if ($behaviourrating == 'A') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="A" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;	
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="A" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($behaviourrating == 'B') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="B" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;	
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="B" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($behaviourrating == 'C') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="C" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;					
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="C" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($behaviourrating == 'D') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="D" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;					
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="D" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}

				if ($behaviourrating == 'E') {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="E" checked required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				} else {
					$form .=  '<td>' . R_NEWLINE;
						$form .=  '<input type="radio" name="'.$behaviour['behaviour'].'" value="E" required="required" />' . R_NEWLINE;
					$form .=  '</td>'. R_NEWLINE;
				}
			$form .=  '</tr>' . R_NEWLINE;
		}
		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;


		$content = <<<_HERE
			<h3>Staffs' Comments</h3>
			<p>Select a comment from options A - D for each of the staff if applicable</p>
			<table class="table table-hover" id="form-table">
			<thead>
				<th scope="col">Staff</th>
				<th scope="col">Comment</th>
				<th scope="col">Signature</th>
				<th scope="col" style="text-align: center;">A</th>
				<th scope="col" style="text-align: center;">B</th>
				<th scope="col" style="text-align: center;">C</th>
				<th scope="col" style="text-align: center;">D</th>
				<th scope="col" style="text-align: center;">E</th>
			</thead>
			<tbody>
_HERE;
		$form .=  $content;

		$comments = $user->getComments($data);

		foreach ($comments as $comment) {
			$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>Form Master/Mistress\' Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("formcomment", "formcomment", $comment['formcomment'], "", "required", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("formsignature", "signature", $comment['formsignature'], "", "required", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>House Master\'s Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("housecomment", "housecomment", $comment['housecomment'], "", "", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("housesignature", "signature", $comment['housesignature'], "", "", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>Guidance\'s Counsellor\'s Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("guidancecomment", "guidancecomment", $comment['guidancecomment'], "", "", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("guidancesignature", "signature", $comment['guidancesignature'], "", "", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td><b>Principal\'s Comment</b></td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("principalcomment", "principalcomment", $comment['principalcomment'], "", "required", "Select a comment from options A - D");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
				$form .=  form_input("principalsignature", "signature", $comment['principalsignature'], "", "required", "Enter Intials");
			$form .=  '</td>' . R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="A" class="A" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="B" class="B" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="C" class="C" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="D" class="D" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
			$form .=  '<td>' . R_NEWLINE;
					$form .=  '<input type="button" value="E" class="E" onclick="addComment(this)" />' . R_NEWLINE;
			$form .=  '</td>'. R_NEWLINE;
		$form .=  '</tr>' . R_NEWLINE;
		}

		$form .=  '<tr>' . R_NEWLINE;
			$form .=  '<td colspan="10"><button style="float: right;" type="submit" class="btn btn-primary btn-md" id="compile-result">Edit Result</button></td>' . R_NEWLINE;
		$form .=  '<tr>' . R_NEWLINE;

		$form .=  '</tbody>' . R_NEWLINE;
		$form .=  '</table>' . R_NEWLINE;

		if (!isset($_SESSION)) {
            session_start();
        }

		if (isset($_SESSION['form1'])) {
			unset($_SESSION['form1']);
		}

		$_SESSION['form1'] = $data;
		$form .=  form_hidden("edit", "edit", "result");

	$form .=  '</form>' . R_NEWLINE;

	echo $form;
	}

	if (isset($_POST['edit'])) {
		session_start();
		$extra = sanitize_array($_POST);
		$result = $_SESSION['form1'];
		$subjects = $user->getSubjects($result['classid']);
		$skills = $user->getSkills();
		$behaviours = $user->getBehaviours();

		//get the subjectids
		$i = 0;
		foreach($subjects as $subject) {
			//Get the subjectids
			if ($i == 0) {
				$subjectids = $subject['subjectid'] . ', ';
			} else {
				if ($i == sizeof($subjects) - 1) {
					$subjectids .= $subject['subjectid'];	
				} else {
					$subjectids .= $subject['subjectid'] . ', ';
				}
			}
			$i++;
		}

		//get the skillids
		$i = 0;
		foreach($skills as $skill) {
			if ($i == 0) {
				$skillids = $skill['skillid'] . ', ';
			} else {
				if ($i == sizeof($skills) - 1) {
					$skillids .= $skill['skillid'];	
				} else {
					$skillids .= $skill['skillid'] . ', ';
				}
			}
			$i++;
		}


		//get the behaviourids
		$i = 0;
		foreach($behaviours as $behaviour) {
			if ($i == 0) {
				$behaviourids = $behaviour['behaviourid'] . ', ';
			} else {
				if ($i == sizeof($behaviours) - 1) {
					$behaviourids .= $behaviour['behaviourid'];	
				} else {
					$behaviourids .= $behaviour['behaviourid'] . ', ';
				}
			}
			$i++;
		}

		//...update scores of subjects

		
		foreach (explode(',', $subjectids) as $id) {
			$subject = explode(' ', $user->getSubjectName($id))[0];
			$user->updateScores($result, $id, 
							$result[$subject . '-firsttest'],
							$result[$subject . '-secondtest'],
							$result[$subject . '-quiz'],
							$result[$subject . '-project'],
							$result[$subject . '-exam'],
							$result[$subject . '-total'],
							$result[$subject . '-average'],
							$result[$subject . '-grade'],
							$result[$subject . '-interpret']
							);
		}

		//Update data for extra-curricula

		//$extraid = $user->addExtraCurr($result['admissionno'], $skillids, $behaviourids);

		//...update skills and behaviour of student

		foreach (explode(', ', $skillids) as $id) {
			$skill = $user->getSkillName($id);
			$skillx = str_replace(' ', '_', $skill);
			//echo $extra[$skillx];
			if (isset($extra[$skillx])) {
				$user->updateSkillSet($result, $id, $extra[$skillx]);
			}
		}

		foreach (explode(', ', $behaviourids) as $id) {
			$behaviour = $user->getBehaviourName($id);
			$behaviourx = str_replace(' ', '_', $behaviour);
			if (isset($extra[$behaviourx])) {
				$user->updateBehaviourSet($result, $id, $extra[$behaviourx]);
			}
		}

		//Skills set and Behaviour set data updated
		//...update comments

		if ($user->updateComments($result, $extra['formcomment'],
						$extra['formsignature'],
						$extra['housecomment'],
						$extra['housesignature'],
						$extra['guidancecomment'],
						$extra['guidancesignature'],
						$extra['principalcomment'],
						$extra['principalsignature']
						))
		{
			echo "success";
		} else {
			echo "failed";
		}

	}

}