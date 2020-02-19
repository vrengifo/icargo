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
	<form action="aavihis_del.php" method="post" name="form1">
	<?php
		$principal="aavihistorial.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">	
  <input type="button" name="Add" value="Add" onClick="self.location='aavihis_add.php<?=$param_destino?>'">
		<input type="submit" name="Del" value="Del" onClick="return confirmdeletef();">
		<br>			
<?php	
		$sql="select vh.vuehis_id,vh.avi_id,v.vue_codigo,v.vue_ruta,v.vue_origen,v.vue_destino,to_char(vh.vuehis_fecha,'YYYY-MM-DD'),"
			."vh.vue_peso_max,vh.vue_vol_max,vh.vuehis_id "
			."from vuelo_historial vh,vuelo v "
			."where v.vue_codigo=vh.vue_codigo "
			."order by vh.vuehis_fecha,vh.vuehis_id";
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Records found.'));
		else
		{
			//$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
			$mainheaders=array("Del","Avión","Nro de Vuelo","Ruta","Origen","Destino","Fecha","Origen","Destino","Modificar");		
			build_table_admin($recordSet,false,$mainheaders,'Administración de Historial de Vuelos ',
			'images/360/yearview.gif','50%','true','chc','aavihis_upd.php',$param_destino,"total");
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
