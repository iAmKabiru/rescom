<?php

function sanitize_str($str) {
	$str = strip_tags($str);
	$str = trim($str);
	$str = stripslashes($str);
	if (is_string($str)) {
		$str = filter_var($str, FILTER_SANITIZE_STRING);
	} else if (is_int($str)) {
		$str = filter_var($str, FILTER_VALIDATE_INT);
	}
	return $str;
}

function sanitize_array($array) {
	$ret = array();
	foreach($array as $key => $value) {
		$ret[$key] = sanitize_str($value);
	}
	return $ret;
}