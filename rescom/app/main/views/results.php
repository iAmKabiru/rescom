<?php

//=========================================
// File name 	: results.php
// Description 	: Preview and Print Students Result Sheet and Class Score Sheet

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
 * @file
 * Preview and Print Students Result Sheet and Class Score Sheet
 *
 * @package com.gentech.rescom
 * @brief ResCom
 * @author Ozoka Lucky Orobo
 */

require_once('../helpers/form_helper.php');
require_once('../models/user.php');

$user = new User();

$level = $user->getAccessLevel($user->getID($_SESSION['username']));

echo '<div class="container content">' . R_NEWLINE;
echo '<h3>'.$config['sidebar-texts'][3].'</h3>' . R_NEWLINE;

    echo '<div class="panel panel-default section-panel">' . R_NEWLINE;
        echo '<div class="panel-heading">' . R_NEWLINE;
        echo '<h1 class="panel-title">Result Data</h1>' . R_NEWLINE;
        echo '</div>' . R_NEWLINE;
        echo '<div class="panel-body">' . R_NEWLINE;
            $content = <<<END
            <p>
                This section provides functionalities to <b>Preview</b> and <b>Print</b> Students' <strong>Result Sheet</strong> and Class <strong>Score Sheet</strong>
            </p>
            <p>
                To begin select the type of Result Data you want to work with; Student's Result Sheet or Class Score Sheet; and fill out the required fields. Click on get Get Result to get the list of result for the specified field data;
            </p>
END;
            echo $content;
            getAddNewPanel("Result");
            $options = array(
                "Select Type" => 1,
                "Student Result Sheet" => 0,
                "Class Score Sheet" => 0
            );
                echo form_open();
                echo form_group();
                echo form_label("Type: ", "resulttype");
                echo form_col(9);
                echo form_select("type", "resulttype", $options);
                echo '</div>' . R_NEWLINE;
                echo form_group_close();
                echo form_close();

                echo getBasicForm();


                echo '<div class="container-fluid studentsresult-list">' . R_NEWLINE;

                echo '</div>' . R_NEWLINE;

            echo '</div>' .R_NEWLINE;
        echo '</div>' .R_NEWLINE;
    echo '</div>' .R_NEWLINE;
echo '</div>' .R_NEWLINE;

function getBasicForm() {
    global $user;
    $form =  form_open("post", "form-horizontal", "getstdresults-form");
    $form .=  '<div class="form-inline getstd">' . R_NEWLINE;
    $form .=  form_group();
    $form .=  form_label("Class: ", "class");
    $form .=  form_col(9);
    $form .=  '<select style="width: 165px;" name="class" id="class" class="form-control">' . R_NEWLINE;
    $form .=  '<option selected="selected">Select Class</option>' . R_NEWLINE;
    foreach($user->getClasses() as $row) {
        $form .=  '<option>'.$row.'</option>' . R_NEWLINE;
    }
    $form .=  '</select>' . R_NEWLINE;
    $form .=  '</div>' . R_NEWLINE;
    $form .=  form_group_close();
    $form .=  form_group();
    $form .=  form_label("Session: ", "session");
    $form .=  form_col(9);
    $form .=  '<select style="width: 165px;" name="session" id="session" class="form-control" disabled>' . R_NEWLINE;
    $form .=  '</select>' . R_NEWLINE;
    $form .=  '</div>' . R_NEWLINE;
    $form .=  form_group_close();
    $form .=  '<div class="form-inline getstd">' . R_NEWLINE;
    $form .=  form_group();
    $form .=  form_label("Term: ", "term");
    $form .=  form_col(9);
    $form .=  '<select style="width: 165px;" name="term" id="term" class="form-control">' . R_NEWLINE;
    $form .=  '<option value="1st">1st</option>' . R_NEWLINE;
    $form .=  '<option value="2nd">2nd</option>' . R_NEWLINE;
    $form .=  '<option value="3rd">3rd</option>' . R_NEWLINE;
    $form .=  '</select>' . R_NEWLINE;
    $form .=  '</div>' . R_NEWLINE;
    $form .=  form_group_close();
    $form .=  form_group();
    $form .=  form_label("Year: ", "year");
    $form .=  form_col(9);
    $form .=  '<select style="width: 165px;" name="year" id="year" class="form-control" disabled>' . R_NEWLINE;
    $form .=  '</select>' . R_NEWLINE;
    $form .=  '</div>' . R_NEWLINE;
    $form .=  form_group_close();
    $form .=  '</div>' . R_NEWLINE;
    $form .=  '</div>' . R_NEWLINE;
    $form .=  form_hidden("get-studentsresult", "get-results", "std");
    $form .=  '<div class="form-inline getstd">' . R_NEWLINE;
    $form .=  form_button("getresults", "primary", "Get Results", "right");
    $form .=  '</div>' . R_NEWLINE;

    $form .=  form_close();
    return $form;
}