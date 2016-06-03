<?php

if (!(isset($_SESSION) && isset($_SESSION['username'])))
{
  //Anonymous user, redirect to login page for authentication
  if (!isset($_SESSION)) {
  	session_start();
  }
  
  $_SESSION['uri'] = $_SERVER['REQUEST_URI'];
  header('Location: ../.');
}