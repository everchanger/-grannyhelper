<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Granny Display</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<!-- CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

		<nav class="navbar navbar-default no-margin-bottom" id='main_navbar'>
		  <div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			  <a class="navbar-brand" href="?controller=home">Granny Display</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">
			  	<?php if(isAuthenticated()): ?>
				<li>
				  	<a href="?controller=home&action=display">Show screen</a>
				</li>
				<li>
				  	<a href="?controller=organization&action=show">Organization</a>
				</li>
				<?php else: ?>
				<li>
				  	<a href="?controller=home&action=show_register">Register</a>
				</li>
				<?php endif; ?>
			  </ul>			

			  <ul class="nav navbar-nav navbar-right">
			  	<?php if(isAuthenticated()): ?>
				<li>
				  	<a href="?controller=user&action=logout">Logout</a>
				</li>
				<?php endif; ?>
			  </ul>
			</div>
		  </div>
		</nav>
		
		<!-- Message handling -->
		<?php 
			$panel_type = "";
			if(isset($_SESSION['message_to_user'])) 
			{
				switch($_SESSION['message_to_user']->type) 
				{
						case USER_MESSAGE_SUCCESS:
							$panel_type = "panel-success";
						break;
						case USER_MESSAGE_WARNING:
							$panel_type = "panel-warning";
						break;
						case USER_MESSAGE_ERROR:
							$panel_type = "panel-danger";
						break;
				}
			}
		?>

	<div class="panel no-margin-bottom <?=$panel_type?> hidden-elm" id="message_field">
			<div class="panel-heading">
				<div id="user_message">
				<?=isset($_SESSION['message_to_user']) ? $_SESSION['message_to_user']->message : '' ?><span id="close-message-btn" class="pull-right glyphicon glyphicon-remove clickable"></span>
				</div>
				
			</div>
		</div>
		
		<div class="container-fluid" id="content">
			<!-- INCLUDE THE SELECTED VIEW! -->
			<?php include $view_file_name; ?>
		</div>

		<!-- JAVASCRIPT -->
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-datetimepicker.sv.js" charset="UTF-8"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/messages.js"></script>
	</body>
</html>

<?php

	if(isset($_SESSION['message_to_user'])) 
	{
		unset($_SESSION['message_to_user']);
		
	}

?>