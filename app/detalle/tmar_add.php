<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$session_username;
//		buildmenu($username);
//		buildsubmenu($id_aplicacion,$id_subaplicacion);
		///todo  el html como se quiera
		// cambio de destino en variable principal
		//$principal="tmar_ok.php";
	?>
	
	<br>
	<form action="tmar_add1.php" method="post" name="form1">
	<?php				
		$sql_task="select tas_number,tas_description from mai_ota_task where tas_id=$idp ";
		//$sql_oh="select cot_id,cot_description from mai_ota_component_type order by cot_description";
		$sql_interval="select par_id,par_description from mai_ota_parameter order by par_description";
		$sql_typ="select typ_id,typ_descripcion from mai_ota_typ order by typ_descripcion";
		$campo=array(
						array("etiqueta"=>"* Task (Task # - Desc)","nombre"=>"mar0","tipo_campo"=>"hidden","sql"=>$sql_task,"valor"=>$idp),
						//array("etiqueta"=>"* Component Type","nombre"=>"mar1","tipo_campo"=>"select","sql"=>$sql_oh,"valor"=>""),
						array("etiqueta"=>"* Limit","nombre"=>"mar2","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Interval","nombre"=>"mar3","tipo_campo"=>"select","sql"=>$sql_interval,"valor"=>""),
						array("etiqueta"=>"* Typ","nombre"=>"mar4","tipo_campo"=>"select","sql"=>$sql_typ,"valor"=>""),
						array("etiqueta"=>"* Action","nombre"=>"mar5","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Ref","nombre"=>"mar6","tipo_campo"=>"text","sql"=>"","valor"=>"")						
					);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"principal","valor"=>$principal),
							array("nombre"=>"idp","valor"=>$idp)
							
							);
		//construye el html para los campos relacionados
		build_add($conn,'false',"Add Task Maintenance Requirement","images/360/personwrite.gif","50%",'true'
		,$campo,$campo_hidden);
		//$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
	?>



<SCRIPT LANGUAGE="JavaScript">
function valida() {

define('mar0', 'string', 'Task (Task # - Desc)');
define('mar2', 'string', 'Limit');
define('mar3', 'string', 'Interval');
define('mar4', 'string', 'Typ');
define('mar5', 'string', 'Action');
define('mar6', 'string', 'Ref');


}
</script>  
		<input type="submit" name="Add" onClick="validate();return returnVal;"  value="Add">
		<input type="button" name="Cancel" value="Cancel" onClick="window.close();">
		<br>			
	</form>	
<?php
//		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>