<?php
//=========================================
// File name 	: login.php
// Description 	: Authentication page

// Author 		: Ozoka Lucky Orobo

// (c) Copyright:
//				GenTech

//=========================================

/**
* @file
* Authentication page
*
* @package com.gentech.rescom
* @brief ResCom
* @author Ozoka Lucky Orobo
*/

session_start();

// --- INCLUDE FILES -----------------------

require_once('config/res_config.php');
require_once('layout/res_page_head.php');

if (isset($_SESSION) && isset($_SESSION['username'])) {
	header('Location: views/');
}

?>

<div class="container login-frame">
	<div class="logo-heading">
		<img src="../images/logo.png">
		<div class="sch-name">
		<?php
			echo '<h1>'.R_SCH_NAME.'</h1>' . R_NEWLINE;
			echo '<h3>'.R_APP_TITLE.'</h3>' . R_NEWLINE;
		?>			
		</div>
	</div>
	<div class="panel panel-default login-panel">
		<div class="panel-heading">
			<h1 class="panel-title">Authentication</h1>
		</div>
		<div class="panel-body">
			<div class="login-info">
				<?php
					echo $config["info"]['login-message'];
				?>
			</div>
			<div class="login-form">
				<form action="" method="post" id="login-form">
					<div class="form-group">
						<input type="text" class="form-control block" name="username" id="username" placeholder="Username" required="required" autocomplete="off" />
					</div>
					<div class="form-group">
						<input type="password" class="form-control block" name="password" id="password" placeholder="Password" required="required" />
					</div>
					<input type="hidden" name="auth" value="authenticate">
					<button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="page-info">
	<?php
		echo $config["info"]['login-info'];
	?>
</div>

<?php

	require_once('layout/res_footer.php');

?>