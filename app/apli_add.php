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
	<form action="apli_add1.php" method="post" name="form1">
  <?php				
		$campo=array(
						array("etiqueta"=>"* M�dulo","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Archivo","nombre"=>"clp1","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Imagen","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>"")
					);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_add($conn,'false',"A�adir M�dulo","images/360/personwrite.gif","50%",'true'
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
