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
	<form action="user_add1.php" method="post" name="form1">
	<?php				
		//$sql_clp="select clp_id,clp_name from mai_ota_class_part order by clp_name";
		//$sql_app="select distinct ass_aplicability,ass_aplicability as ap1 from mai_ota_assembly order by ass_aplicability";
		//$sql_appl="select appl_id,appl_code from mai_ota_applicability order by appl_code";
		$campo=array(
						array("etiqueta"=>"* Username","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Password","nombre"=>"clp1","tipo_campo"=>"password","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Name","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Department","nombre"=>"clp3","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* E-mail","nombre"=>"clp4","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Phone","nombre"=>"clp5","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Mobil Phone","nombre"=>"clp6","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Address","nombre"=>"clp7","tipo_campo"=>"area","sql"=>"","valor"=>"")
					);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_add($conn,'false',"Add User","images/360/personwrite.gif","50%",'true'
		,$campo,$campo_hidden);
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
	?>
  
		<input type="submit" name="Add" value="Add">
		<input type="button" name="Cancel" value="Return" onClick="self.location='<?=$principal?><?=$param_destino?>'">
		<br>			
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>