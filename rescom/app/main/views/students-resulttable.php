<?php

//=========================================
// File name 	: students-resulttable.php
// Description 	: Fetches the students results for the specified field data

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
 * @file
 * Fetches the students results for the specified field data
 *
 * @package com.gentech.rescom
 * @brief ResCom
 * @author Ozoka Lucky Orobo
 */

require_once('../helpers/form_helper.php');
require_once('../models/user.php');
require_once('../controllers/utility.php');
require_once('../controllers/compile.php');

session_start();

$user = new User();
$compile = new Compile();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['get-studentsresult'])) {

    if ($_POST['type'] == "Student Result Sheet") {
        $data = sanitize_array($_POST);
        $data['term'] = $compile->getTermID($data['session'], $data['term']);
        $table = '<h4>'.$data['class'].' Students Result Sheet</h4>' . R_NEWLINE;
        $table .= '<table class="table table-bordered table-hover" id="student-table">' . R_NEWLINE;
        $table .= '<thead>' . R_NEWLINE;
        $table .= '<th>Admission Number</th>' . R_NEWLINE;
        $table .= '<th>First Name</th>' . R_NEWLINE;
        $table .= '<th>Last Name</th>' . R_NEWLINE;
        $table .= '<th>Sex</th>' . R_NEWLINE;
        $table .= '<th>Number in Class</th>' . R_NEWLINE;
        $table .= '<th>Class</th>' . R_NEWLINE;
        $table .= '<th>Result</th>' . R_NEWLINE;
        $table .= '<tbody style="color: #000;">' . R_NEWLINE;
        $class = $data['class'];
        $user->getClassID($data['class']);
        $students = $user->getStudents($data['class'], $data['session']);
        //$result_data = $getResultData()
        if (empty($students)) {
            $table .= '<tr>' . R_NEWLINE;
            $table .= '<td style="text-align: center;" colspan="7"><h3>No Result Sheet for the selected class / session</h3></td>';
            $table .= '</tr>' . R_NEWLINE;
        } else {
            foreach($students as $student) {
                $table .= '<tr>' . R_NEWLINE;
                $data['admissionno'] = $student['admissionno'];
                if ($user->isResultCompiled($data)) {
                    $table .= '<td>'.$student['admissionno'].'</td>' . R_NEWLINE;
                    $table .= '<td>'.$student['firstname'].'</td>' . R_NEWLINE;
                    $table .= '<td>'.$student['lastname'].'</td>' . R_NEWLINE;
                    $table .= '<td>'.$student['sex'].'</td>' . R_NEWLINE;
                    $table .= '<td>'.$student['numberinclass'].'</td>' . R_NEWLINE;
                    $table .= '<td>'.$class.'</td>' . R_NEWLINE;
                    $table .= "<td class=\"compiled\"><span id='preview'><a href='../controllers/print-preview-result.php?admissionno=".$data['admissionno']."&class=".$data['class']."&session=".$data['session']."&term=".$data['term']."&type=resultsheet&year=".$data['year']."&action=preview' target='_blank'><button type=\"button\" class=\"btn btn-primary btn-md\" title=\"Click here to preview student's result sheet for selected session\" data-toggle=\"tooltip\" data-placement=\"right\" rel=\"txtToolTip\">Preview Result</button></a></span> <span id=\"print\"><a href='../controllers/print-preview-result.php?admissionno=".$data['admissionno']."&class=".$data['class']."&session=".$data['session']."&term=".$data['term']."&type=resultsheet&year=".$data['year']."&action=download' target='_blank'<button type=\"button\" class=\"btn btn-primary btn-md\" title=\"Click here to print student's result sheet for selected session\" data-toggle=\"tooltip\" data-placement=\"right\" rel=\"txtToolTip\">Dowload Result</button></a></span></td>" . R_NEWLINE;
                }
                $table .= '</tr>' . R_NEWLINE;
            }
        }
        $table .= '</tbody>' . R_NEWLINE;
        $table .= '</table>' . R_NEWLINE;
        echo $table;
    } else if ($_POST['type'] == "Class Score Sheet") {
        $data = sanitize_array($_POST);
        $data['term'] = $compile->getTermID($data['session'], $data['term']);
        $table = '<h4>'.$data['class'].' Class Result Sheet</h4>' . R_NEWLINE;
        $table .= '<table class="table table-bordered table-hover" id="student-table">' . R_NEWLINE;
        $table .= '<thead>' . R_NEWLINE;
        $table .= '<th>Class</th>' . R_NEWLINE;
        $table .= '<th>Session</th>' . R_NEWLINE;
        $table .= '<th>Term</th>' . R_NEWLINE;
        $table .= '<th>Year</th>' . R_NEWLINE;
        $table .= '<th>Result</th>' . R_NEWLINE;
        $table .= '<tbody style="color: #000;">' . R_NEWLINE;
        $class = $data['class'];
        $user->getClassID($data['class']);
        //print_r($data);
        if ($user->isScoreSheet($data)) {
            //$students = $user->getStudents($data['class'], $data['session']);
            $table .= '<td>'.$class.'</td>' . R_NEWLINE;
            $table .= '<td>'.$data['session'].'</td>' . R_NEWLINE;
            $table .= '<td>'.$data['term'].'</td>' . R_NEWLINE;
            $table .= '<td>'.$data['year'].'</td>' . R_NEWLINE;
            $table .= "<td class=\"compiled\"><span id='preview'><a href='../controllers/print-preview-result.php?class=".$data['class']."&session=".$data['session']."&term=".$data['term']."&type=scoresheet&year=".$data['year']."&action=preview' target='_blank'><button type=\"button\" class=\"btn btn-primary btn-md\" title=\"Click here to preview student's result sheet for selected session\" data-toggle=\"tooltip\" data-placement=\"right\" rel=\"txtToolTip\">Preview Result</button></a></span> <span id=\"print\"><a href='../controllers/print-preview-result.php?class=".$data['class']."&session=".$data['session']."&term=".$data['term']."&type=scoresheet&year=".$data['year']."&action=download' target='_blank'<button type=\"button\" class=\"btn btn-primary btn-md\" title=\"Click here to print student's result sheet for selected session\" data-toggle=\"tooltip\" data-placement=\"right\" rel=\"txtToolTip\">Download Sheet</button></a></span></td>" . R_NEWLINE;
        } else {
            $table .= '<tr>' . R_NEWLINE;
            $table .= '<td style="text-align: center;" colspan="7"><h3>No Score Sheet for the selected class / session</h3></td>';
            $table .= '</tr>' . R_NEWLINE;
        }
        echo $table;
    }

}