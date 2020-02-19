<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$session_username;
//		buildmenu($username);
//		buildsubmenu($id_aplicacion,$id_subaplicacion);				
	?>
<html>
<body>	
	<br>
	Maintenance Requirement Accepted!!!
	<br>	
	<input type="button" name="Cancel" value="Ok" onClick="self.opener.location.reload();window.close();">
	<br>			
</body>		
</html>