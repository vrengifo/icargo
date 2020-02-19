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
<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	
	<br>
	<form action="aavihis_upd1.php" method="post" name="form1">
	<?php
		$sql="select avi_id,vue_codigo,to_char(vuehis_fecha,'YYYY-MM-DD'),vue_peso_max,vue_vol_max "
			."from vuelo_historial "
			."where vuehis_id=$id";
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{
		  $vaviid=$rs->fields[0];
		  $vvuecodigo=$rs->fields[1];
		  $vfecha=$rs->fields[2];
		  $vpesomax=$rs->fields[3];
		  $vvolmax=$rs->fields[4];
		}
		$rs->Close();

		
		$sql_av="select avi_id,avi_matricula from avion order by avi_matricula ";
		$sql_vu="select vue_codigo,vue_codigo v1 from vuelo order by vue_ruta ";
				
		$campo=array(
						array("etiqueta"=>"* Avión","nombre"=>"clp0","tipo_campo"=>"select","sql"=>$sql_av,"valor"=>$vaviid),
						array("etiqueta"=>"* Nro Vuelo","nombre"=>"clp1","tipo_campo"=>"select","sql"=>$sql_vu,"valor"=>$vvuecodigo),
						array("etiqueta"=>"* Fecha","nombre"=>"clp2","tipo_campo"=>"date","sql"=>"","valor"=>$vfecha),
						array("etiqueta"=>"* Peso Máximo","nombre"=>"clp3","tipo_campo"=>"text","sql"=>"","valor"=>$vpesomax),
						array("etiqueta"=>"* Volumen Máximo","nombre"=>"clp4","tipo_campo"=>"text","sql"=>"","valor"=>$vvolmax)
					);

		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_upd($conn,'false',"Actualizar Historial de Vuelo","images/360/memowrite.gif","50%",'true'
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
