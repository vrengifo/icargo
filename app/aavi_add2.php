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
		$principal="aavion.php";
	?>
<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	<br>
	<form action="aavi_upd1.php" method="post" name="form1">
	<?php				
		
		$sql="select avi_id,avi_matricula,avi_peso_max,avi_vol_max "
			."from avion "
			."where avi_id='$id'";
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{
		  $vid=$rs->fields[0];
		  $vmatricula=$rs->fields[1];
		  $vpesomax=$rs->fields[2];
		  $vvolmax=$rs->fields[3];
		}
		$rs->Close();

		$campo=array(
						array("etiqueta"=>"* C�digo","nombre"=>"clp0","tipo_campo"=>"nada","sql"=>"","valor"=>$vid),
						array("etiqueta"=>"* Nro Matr�cula","nombre"=>"clp1","tipo_campo"=>"nada","sql"=>"","valor"=>$vmatricula),
						array("etiqueta"=>"* Peso M�ximo","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vpesomax),
						array("etiqueta"=>"* Volumen M�ximo","nombre"=>"clp3","tipo_campo"=>"text","sql"=>"","valor"=>$vvolmax)
					);

		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_upd($conn,'false',"Avi�n","images/360/memowrite.gif","50%",'true'
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
