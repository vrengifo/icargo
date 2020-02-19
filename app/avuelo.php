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
	<form action="avue_del.php" method="post" name="form1">
	<?php
		$principal="avuelo.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">	
  <input type="button" name="Add" value="Add" onClick="self.location='avue_add.php<?=$param_destino?>'">
		<input type="submit" name="Del" value="Del" onClick="return confirmdeletef();">
		<br>			
<?php	
		$sql="select v.vue_codigo,tv.tipvue_descripcion,v.vue_codigo,v.vue_ruta,v.vue_origen,v.vue_destino,v.vue_codigo "
			."from vuelo v,tipovuelo tv "
			."where tv.tipvue_codigo=v.tipvue_codigo "
			."order by v.vue_codigo";
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Records found.'));
		else
		{
			//$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
			$mainheaders=array("Del","Tipo Vuelo","Nro de Vuelo","Ruta","Origen","Destino","Modificar");		
			build_table_admin($recordSet,false,$mainheaders,'Administración de Vuelo ',
			'images/360/yearview.gif','50%','true','chc','avue_upd.php',$param_destino,"total");
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
