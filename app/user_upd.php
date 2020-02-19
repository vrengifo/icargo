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
		$principal="user.php";
	?>
	
	<br>
	<form action="user_upd1.php" method="post" name="form1">
	<?php				
		include_once("class/c_usuario.php");
		$obj=new c_usuario($conn);
		
		$obj->info($id);
	/*
		$sql="select nombre,password,departamento,mail,telefono,movil,direccion,dept_id "
			."from usuario "
			."where username='$id'";
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{
		  $vnombre=$rs->fields[0];
		  $vpassword=$rs->fields[1];
		  $vdepartamento=$rs->fields[2];
		  $vmail=$rs->fields[3];
		  $vtelefono=$rs->fields[4];
		  $vmovil=$rs->fields[5];
		  $vdireccion=$rs->fields[6];
          $vdept_id=$rs->fields[7];
		}
		$rs->Close();
	*/	
        //$sql_dept="select dept_id,dept_name from departamento order by dept_name ";
        $sqlPerfil="select perfil_id,perfil_nombre from perfil order by perfil_nombre ";

/*        
		$campo=array(
						array("etiqueta"=>"* Username","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$id),
						array("etiqueta"=>"* Password","nombre"=>"clp1","tipo_campo"=>"hidden","sql"=>"","valor"=>$vpassword),
						array("etiqueta"=>"* Name","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vnombre),
						array("etiqueta"=>"* Department","nombre"=>"clp3","tipo_campo"=>"select","sql"=>$sql_dept,"valor"=>$vdept_id),
						array("etiqueta"=>"* E-mail","nombre"=>"clp4","tipo_campo"=>"text","sql"=>"","valor"=>$vmail),
						array("etiqueta"=>"* Phone","nombre"=>"clp5","tipo_campo"=>"text","sql"=>"","valor"=>$vtelefono),
						array("etiqueta"=>"* Mobil Phone","nombre"=>"clp6","tipo_campo"=>"text","sql"=>"","valor"=>$vmovil),
						array("etiqueta"=>"* Address","nombre"=>"clp7","tipo_campo"=>"area","sql"=>"","valor"=>$vdireccion)
					);
*/
		$campo=array(
						array("etiqueta"=>"* Username","nombre"=>"clp0","tipo_campo"=>"nada","sql"=>"","valor"=>$obj->usu_codigo),
						array("etiqueta"=>"* Password","nombre"=>"clp1","tipo_campo"=>"password","sql"=>"","valor"=>$obj->usu_clave),
						array("etiqueta"=>"* Nombre","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$obj->usu_nombre),
						array("etiqueta"=>"* Perfil","nombre"=>"clp3","tipo_campo"=>"select","sql"=>$sqlPerfil,"valor"=>$obj->perfil_id)
					);
					
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		build_upd($conn,'false',"Actualizar Usuario","images/360/memowrite.gif","50%",'true'
		,$campo,$campo_hidden,$id);
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
	?>
  
		<input type="submit" name="Update" value="Actualizar">
		<input type="button" name="Cancel" value="Cancelar" onClick="self.location='<?=$principal?><?=$param_destino?>'">
		<br>
		<br>			
	</form>	
<SCRIPT LANGUAGE="JavaScript">
function valida() {

define('clp0', 'string', 'Username');
define('clp1', 'string', 'Password');
define('clp2', 'string', 'Name');
define('clp3', 'string', 'Perfil');
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
