<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion);
		///todo  el html como se quiera
	?>
	
	<br>
	<form action="aavi_del.php" method="post" name="form1">
	<?php
		$principal="aavion.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">	
  <input type="button" name="Add" value="Add" onClick="self.location='aavi_add.php<?=$param_destino?>'">
		<input type="submit" name="Del" value="Del" onClick="return confirmdeletef();">
		<br>			
<?php	
		$sql="select avi_id,avi_matricula,avi_peso_max,avi_vol_max,avi_id "
			."from avion "
			."order by avi_matricula";
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Records found.'));
		else
		{
			//$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
			$mainheaders=array("Del","Avión","Peso Máximo","Volumen Máximo","Modificar");		
			build_table_admin($recordSet,false,$mainheaders,'Administración de Avión ',
			'images/360/yearview.gif','50%','true','chc','aavi_upd.php',$param_destino,"total");
			//variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
			$cextra="id_aplicacion|id_subaplicacion|principal";
		}
?>
		<input type="hidden" name="cextra" value="<?=$cextra?>">
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
