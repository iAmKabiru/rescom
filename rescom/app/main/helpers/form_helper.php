<?php

//=========================================
// File name 	: dashboard.php
// Description 	: Form Helper functions to create and control forms

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Form Helper functions to create and control forms
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

function form_open($method = "", $class = "form-horizontal", $id = "", $action = "") {
	$form = '<form method="'.$method.'" action="'.$action.'" class="'.$class.'" id="'.$id.'">' . R_NEWLINE;
	return $form;
}

function form_open_upload($method = "", $class = "form-horizontal", $id = "", $action = "") {
	$form = '<form method="'.$method.'" action="'.$action.'" class="'.$class.'" id="'.$id.'" enctype="multipart/form-data">' . R_NEWLINE;
	return $form;
}

function form_group() {
	$form_group = '<div class="form-group">' . R_NEWLINE;
	return $form_group;
}

function form_group_close() {
	return '</div>' . R_NEWLINE;
}

function form_input($name = "", $id = "", $value = "", $placeholder = "", $required = "", $tooltip = "", $type = "text") {
	if (!empty($tooltip)) {
		$form_input = '<input type="'.$type.'" class="form-control" name="'.$name.'" id="'.$id.'" value="'.$value.'" placeholder="'.$placeholder.'" '.$required.' data-toggle="tooltip" data-placement="right" title="'.$tooltip.'" rel="txtToolTip" />' . R_NEWLINE;
	} else {
		$form_input = '<input type="'.$type.'" class="form-control" name="'.$name.'" id="'.$id.'" value="'.$value.'" placeholder="'.$placeholder.'" '.$required.' />' . R_NEWLINE;
	}
	return $form_input;
}


function form_input_number($name = "", $id = "", $value = "", $placeholder = "", $required = "", $tooltip = "", $type = "text") {
	if (!empty($tooltip)) {
		$form_input = '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'" placeholder="'.$placeholder.'" '.$required.' data-toggle="tooltip" data-placement="right" title="'.$tooltip.'" rel="txtToolTip" onkeypress="return isNumberKey(this)" />' . R_NEWLINE;
	} else {
		$form_input = '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'" placeholder="'.$placeholder.'" '.$required.' onkeypress="return isNumberKey(this)" />' . R_NEWLINE;
	}
	return $form_input;
}

function form_password($name = "", $id = "", $value = "", $required = "", $type = "password") {
	$form_input = '<input type="'.$type.'" class="form-control" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.$required.' />' . R_NEWLINE;
	return $form_input;
}

function form_select($name = "", $id = "", $options = array(), $selected = 1) {
	$form_select = '<select name="'.$name.'" id="'.$id.'" class="form-control">' . R_NEWLINE;
		foreach ($options as $key => $value) {
			if ($value == $selected) {
				$form_select .= '<option value="'.$key.'" selected="selected"> '.$key.'' . R_NEWLINE;	
			} else {
				$form_select .= '<option value="'.$key.'"> '.$key.'' . R_NEWLINE;
			}
			$form_select .= '</option>' . R_NEWLINE;
		}
	$form_select .= '</select>' . R_NEWLINE;
	return $form_select;
}

function form_checkbox($name = "", $value = "", $checked = true, $type = "checkbox", $required) {
	if ($checked) {
		$form_checkbox = '<input type="'.$type.'" class="form-control" name="'.$name.'" value="'.$value.'" checked  '.$required.'/>' . R_NEWLINE;	
	} else {
		$form_checkbox = '<input type="'.$type.'" class="form-control" name="'.$name.'" value="'.$value.'" '.$required.'/>' . R_NEWLINE;
	}
	return $form_checkbox;
}

function form_label($value, $for) {
	$label = '<label for="'.$for.'" class="col-md-3 control-label">'.$value.'</label>' . R_NEWLINE;
	return $label;
}

function form_col($num) {
	$col = '<div class="col-sm-'.$num.'">' . R_NEWLINE;
	return $col;
}

function form_date() {
	$form_date = <<<END
	<div class="form-group date required" data-provide="datepicker-inline">
        <label for="dob" class="col-sm-3 control-label">Date of Birth:</label>
        <div class="col-sm-9">
          <input type="text" class="form-control datepicker" id="dob" name="dateofbirth" data-date-format="yyyy/mm/dd" >
        </div>
  	</div>
END;
    return $form_date;
}

function form_number($name, $id, $required = "", $type = "number") {
	$form_num = '<input type="number" name="'.$name.'" id="'.$id.'" class="form-control" '.$required.' onkeypress="return isNumberKey(event)">' . R_NEWLINE;
	return $form_num;
}

function form_textarea($name, $id, $cols, $rows, $text, $required = "") {
	$form_textarea = '<textarea class="form-control" name="'.$name.'" id="'.$id.'" cols="'.$cols.'" rows="'.$rows.'" '.$required.'>' .$text . '</textarea>' . R_NEWLINE;
	return $form_textarea;
}

function form_file($name, $id, $required = "") {
	$form_file = '<input type="file" class="form-control" name="'.$name.'" id="'.$id.'" accept="image/*" '.$required.' >'. R_NEWLINE;
	$form_file .= '<span style="float: left; color: red;">* Should be less than 2 MB in size *</span>' . R_NEWLINE;
	return $form_file;
}

function form_button($name, $btntype, $text, $class = "", $type = "") {
	return '<button class="btn btn-'.$btntype.' btn-md '.$class.'" type="'.$type.'" name="'.$name.'">'.$text.'</button>' . R_NEWLINE;
}

function form_hidden($name, $id, $value, $type = "hidden") {
	return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'" />' . R_NEWLINE;
}

function form_close() {
	return '</form>' . R_NEWLINE;
}

function getAddNewPanel($title) {
	echo '<div class="panel panel-default section-panel">' . R_NEWLINE;
		echo '<div class="panel-heading">' . R_NEWLINE;
			echo '<h1 class="panel-title">'.$title.'</h1>' . R_NEWLINE;
		echo '</div>' . R_NEWLINE;
		echo '<div class="panel-body action-panel">' . R_NEWLINE;
}