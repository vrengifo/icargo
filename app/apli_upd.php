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
	<form action="apli_upd1.php" method="post" name="form1">
	<?php
		$sql="select nombre_aplicacion,file_aplicacion,imagen_aplicacion "
			."from aplicacion "
			."where id_aplicacion=$id";
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{
		  $vnombre=$rs->fields[0];
		  $vfile=$rs->fields[1];
		  $vimagen=$rs->fields[2];
		}
		$rs->Close();

		$campo=array(
						array("etiqueta"=>"* Módulo","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$vnombre),
						array("etiqueta"=>"* Archivo","nombre"=>"clp1","tipo_campo"=>"text","sql"=>"","valor"=>$vfile),
						array("etiqueta"=>"* Imagen","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vimagen)
					);

		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_upd($conn,'false',"Actualizar Módulo","images/360/memowrite.gif","50%",'true'
		,$campo,$campo_hidden,$id);
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
	?>
  
		<input type="submit" name="Update" value="Actualizar">
		<input type="button" name="Cancel" value="Regresar" onClick="self.location='<?=$principal?><?=$param_destino?>'">

		<br>
		<br>			
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
