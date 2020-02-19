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
	<form action="aavihis_add1.php" method="post" name="form1">
  <?php				
		$sql_av="select avi_id,avi_matricula from avion order by avi_matricula ";
		$sql_vu="select vue_codigo,vue_codigo v1 from vuelo order by vue_ruta ";
		
		if(!isset($clp2))
		  $vclp2=date("Y-m-d");
		 else
		  $vclp2=$clp2; 
		
		$campo=array(
						array("etiqueta"=>"* Avión","nombre"=>"clp0","tipo_campo"=>"select","sql"=>$sql_av,"valor"=>""),
						array("etiqueta"=>"* Nro Vuelo","nombre"=>"clp1","tipo_campo"=>"select","sql"=>$sql_vu,"valor"=>""),
						array("etiqueta"=>"* Fecha","nombre"=>"clp2","tipo_campo"=>"date","sql"=>"","valor"=>$vclp2),
						array("etiqueta"=>"* Peso Máximo","nombre"=>"clp3","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Volumen Máximo","nombre"=>"clp4","tipo_campo"=>"text","sql"=>"","valor"=>"")
					);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_add($conn,'false',"Añadir Historial de Vuelo","images/360/personwrite.gif","50%",'true'
		,$campo,$campo_hidden);
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
	?>
  <input type="submit" name="Add" value="A&ntilde;adir">
  <input type="button" name="Cancel" value="Regresar" onClick="self.location='<?=$principal?><?=$param_destino?>'">
		<br>			
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
