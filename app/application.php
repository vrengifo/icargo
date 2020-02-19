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
	<form action="apli_del.php" method="post" name="form1">
	<?php
		$principal="application.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">	
  <input type="button" name="Add" value="Añadir" onClick="self.location='apli_add.php<?=$param_destino?>'">
		<input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
		<br>			
<?php	
		$sql="select id_aplicacion,nombre_aplicacion,file_aplicacion,imagen_aplicacion,id_aplicacion "
			."from aplicacion "
			."order by nombre_aplicacion";
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Records found.'));
		else
		{
			//$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
			$mainheaders=array("Eliminar","Módulo","Archivo","Imagen","Modificar");		
			build_table_admin($recordSet,false,$mainheaders,'Administración de Módulos ',
			'images/360/yearview.gif','50%','true','chc','apli_upd.php',$param_destino,"total");
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
