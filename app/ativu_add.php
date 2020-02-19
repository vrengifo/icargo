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
	<form action="ativu_add1.php" method="post" name="form1">
  <?php				
		$campo=array(
						array("etiqueta"=>"* Código","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Descripción","nombre"=>"clp1","tipo_campo"=>"text","sql"=>"","valor"=>"")						
					);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_add($conn,'false',"Añadir Tipo de Vuelo","images/360/personwrite.gif","50%",'true'
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
