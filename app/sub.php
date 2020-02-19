<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<? include_once('adodb/adodb-pager.inc.php');?>
<?php

		extract($_REQUEST);
		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion);
		///todo  el html como se quiera
	?>
<?php	
        $recordSet = &$conn->Execute('select * from aplicaciones');
        if (!$recordSet||$recordSet->EOF) die(texterror('No Records found.'));
		$mainheaders=array("uno","dos","tres");
		//rs2html($recordSet,"border=3 ",$mainheaders);
?>
			
	<a href="test.php" <?=tooltip('TEXTO PARA EL TOLLTIP')?>>ToolTIPS</a><br>
	<form action="sub.php" method="post">
		<textarea name="" cols="" rows=""></textarea>
		<input name="text" type="text" onchange="validateint(this)"  onFocus="highlightField(this,1)" onBlur="normalField(this)">
	</form>
	
<?php
//	build_table($recordSet,HTML CONtrols,$mainheaders,'Titulo de la Tabla',icono,WIDTH,'true')
		//build_table($recordSet,false,$mainheaders,'Titulo de la Tabla','images/360/yearview.gif','50%','true');
		
$pager = new ADODB_Pager($conn,"select * from aplicaciones");
$pager->showPageLinks = true;
$pager->linksPerPage = 3;
$pager->cache = 60;
$pager->Render($rows=3);		
?>
	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
