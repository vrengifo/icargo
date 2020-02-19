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
        //$sql_dept="select dept_id,dept_name from departamento order by dept_name ";
        $sqlPerfil="select perfil_id,perfil_nombre from perfil order by perfil_nombre";
		$campo=array(
						array("etiqueta"=>"* Código Usuario","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Clave","nombre"=>"clp1","tipo_campo"=>"password","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Nombre","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>""),
						array("etiqueta"=>"* Perfil","nombre"=>"clp3","tipo_campo"=>"select","sql"=>$sqlPerfil,"valor"=>"")
					);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_add($conn,'false',"Añadir Usuario","images/360/personwrite.gif","50%",'true'
		,$campo,$campo_hidden);
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
	?>
  
		<input type="submit" name="Add" value="Añadir" onClick="validate();return returnVal;">
		<input type="button" name="Cancel" value="Cancelar" onClick="self.location='<?=$principal?><?=$param_destino?>'">
		<br>			
	</form>	
<SCRIPT LANGUAGE="JavaScript">
function valida() {

define('clp0', 'string', 'Código Usuario');
define('clp1', 'string', 'Clave');
define('clp2', 'string', 'Nombre');
//define('clp3', 'string', 'Department');
//define('clp4', 'string', 'E-mail');
//define('clp5', 'string', 'Phone');
//define('clp6', 'string', 'Mobil');
//define('clp7', 'string', 'Address');


}
</script>
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
