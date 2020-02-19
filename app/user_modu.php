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
	<form action="user_del.php" method="post" name="form1">
	<?php
		$principal="user_modu.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">	
		<br>			
<?php	
		$sql="select usu_codigo u1,usu_codigo u2,usu_nombre,usu_codigo u3 "
			."from usuario "
			."order by usu_codigo,usu_nombre";
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Records found.'));
		else
		{
			//para mostrar o no enventana
			$param_destino.="&enventana=0";
			//usu_codigo u1,usu_codigo u2,usu_nombre,usu_codigo u3
			$mainheaders=array("Eliminar","Código Usuario","Nombre","Acceso a módulos");		
			build_table_admin($recordSet,false,$mainheaders,'Usuarios ',
			'images/360/yearview.gif','50%','true','chc','usmo_upd.php',$param_destino,"total");
			//variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
			$cextra="id_aplicacion|id_subaplicacion|principal";
		}
?>
		<input type="hidden" name="cextra" value="<?=$cextra?>">
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
