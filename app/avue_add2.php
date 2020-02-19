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
		$principal="avuelo.php";
	?>
<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	<br>
	<form action="avue_upd1.php" method="post" name="form1">
	<?php				
		
		$sql="select vue_codigo,tipvue_codigo,vue_ruta,vue_origen,vue_destino "
			."from vuelo "
			."where vue_codigo='$id'";
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{
		  $vcodigo=$rs->fields[0];
		  $vtipvue=$rs->fields[1];
		  $vruta=$rs->fields[2];
		  $vorigen=$rs->fields[3];
		  $vdestino=$rs->fields[4];
		}
		$rs->Close();

		
		$sql_tv="select tipvue_codigo,tipvue_descripcion from tipovuelo order by tipvue_descripcion ";
		$sql_ciu="select ciu_codigo,ciu_descripcion from ciudad order by pai_codigo,ciu_codigo ";
		$campo=array(
						array("etiqueta"=>"* Tipo de Vuelo","nombre"=>"clp0","tipo_campo"=>"select","sql"=>$sql_tv,"valor"=>$vtipvue),
						array("etiqueta"=>"* Nro Vuelo","nombre"=>"clp1","tipo_campo"=>"nada","sql"=>"","valor"=>$vcodigo),
						array("etiqueta"=>"* Ruta","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vruta),
						array("etiqueta"=>"* Origen","nombre"=>"clp3","tipo_campo"=>"select","sql"=>$sql_ciu,"valor"=>$vorigen),
						array("etiqueta"=>"* Destino","nombre"=>"clp4","tipo_campo"=>"select","sql"=>$sql_ciu,"valor"=>$vdestino)
					);		

		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_upd($conn,'false',"Vuelo","images/360/memowrite.gif","50%",'true'
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
