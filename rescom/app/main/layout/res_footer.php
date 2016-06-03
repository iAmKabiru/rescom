<?php
//=========================================
// File name 	: res_footer.php
// Description 	: Outputs Standard Page footer (footer + scripts)

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Outputs Standard Page footer (footer + scripts)
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

echo '<footer class="footer">' . R_NEWLINE;
echo 'Result Compiler &copy; <span id="copyright-year"></span> Powered By - GenTech and FlowMedia' . R_NEWLINE;
echo '</footer>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'jquery.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_RESOURCE .'bootstrap/js/bootstrap.min.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'bootstrap-datepicker.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'blockUI.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'app.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'dashboard.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'adduser.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'compile.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'manage.js"></script>' . R_NEWLINE;
echo '<script type="text/javascript" src="'. R_PATH_JSCRIPTS .'results.js"></script>' . R_NEWLINE;
echo '<script>$(".datepicker").datepicker();</script>' . R_NEWLINE;
echo '</body>' . R_NEWLINE;
echo '</html>' . R_NEWLINE;