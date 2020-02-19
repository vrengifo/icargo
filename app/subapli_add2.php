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
		$principal="subapplication.php";
	?>
<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	<br>
	<form action="subapli_upd1.php" method="post" name="form1">
	<?php				
		
		$sql="select nombre_subaplicacion,file_subaplicacion,imagen_subaplicacion,id_aplicacion "
			."from subaplicacion "
			."where id_subaplicacion=$id";
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{
		  $vnombre=$rs->fields[0];
		  $vfile=$rs->fields[1];
		  $vimagen=$rs->fields[2];
		  $v_id_aplicacion=$rs->fields[3];
		}
		$rs->Close();

		$sql_mod="select id_aplicacion,nombre_aplicacion from aplicacion order by nombre_aplicacion";
		$campo=array(
						array("etiqueta"=>"* Module","nombre"=>"clp0","tipo_campo"=>"select","sql"=>$sql_mod,"valor"=>$v_id_aplicacion),
						array("etiqueta"=>"* SubModule","nombre"=>"clp1","tipo_campo"=>"text","sql"=>"","valor"=>$vnombre),
						array("etiqueta"=>"* File","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vfile),
						array("etiqueta"=>"* Image","nombre"=>"clp3","tipo_campo"=>"text","sql"=>"","valor"=>$vimagen)
					);

		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_upd($conn,'false',"Submódulo Añadido","images/360/memowrite.gif","50%",'true'
		,$campo,$campo_hidden,$id);
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
	?>
  
		<input type="submit" name="Update" value="Actualizar">
		<input type="button" name="Cancel" value="Cancelar" onClick="self.location='<?=$principal?><?=$param_destino?>'">
		<br>
		<br>			
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
