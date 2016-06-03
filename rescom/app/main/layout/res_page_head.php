<?php
//=========================================
// File name 	: res_page_head.php
// Description 	: Outputs HTML 5 header (doctype + head)

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Outputs HTML 5 header (doctype + head)
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

//require_once('../config/res_config.php');

echo '<!DOCTYPE html>' . R_NEWLINE;
echo '<html lang="en-US">' . R_NEWLINE;
echo '<head>'  . R_NEWLINE;
echo '<title>' . R_APP_TITLE . '</title>' . R_NEWLINE;
echo '<meta name="description" content="'. R_APP_DESCRIPTION .'">' . R_NEWLINE;
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . R_NEWLINE;
echo '<meta name="author" content="'. R_APP_AUTHOR .'">' . R_NEWLINE;
echo '<link rel="shortcut icon" href="'.R_APP_ICON.'"> ' . R_NEWLINE;
echo '<link rel="stylesheet" type="text/css" href="'. R_PATH_RESOURCE .'bootstrap/css/bootstrap.min.css">' . R_NEWLINE;
echo '<link rel="stylesheet" type="text/css" href="'. R_PATH_STYLE_SHEETS .'datepicker.css">' . R_NEWLINE;
echo '<link rel="stylesheet" href="'. R_APP_STYLE .'">' . R_NEWLINE;

?>

<script>
        var currenttime = '<?php print date("F d, Y H:i:s", time())?>';
        var montharray = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
        var serverdate = new Date(currenttime);
        function padlength(what){
          var output=(what.toString().length==1)? "0"+what : what;
          return output;
        }

        function displaytime(){
          serverdate.setSeconds(serverdate.getSeconds()+1);
          var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear();
          var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
          document.getElementById("date-time").innerHTML=datestring+" "+timestring;
        }
        window.onload = setInterval("displaytime()", 1000);
    </script>

<?php

echo '</head>' . R_NEWLINE;
echo '<body>' . R_NEWLINE;


$modal = <<<HEREDOC
	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title left" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" style="z-index: 99999;">
                    <div class="row">
                        <div class="col-md-12" style="font-size: medium;">
                            <p class="left" id="msg">
                              
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" id="add" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <span style="float: left;"><b>Click on Add to Continue or Cancel to Close this box</b></span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title left" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" style="z-index: 99999;">
                    <div class="row">
                        <div class="col-md-12" style="font-size: medium;">
                            <p class="left" id="msg">
                              
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
HEREDOC;
echo $modal;