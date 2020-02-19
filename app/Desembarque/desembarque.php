<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion);
		
		include('class/c_user.php');
		$cusuario=new c_user();
		$origen_usuario=$cusuario->recuperar_ciudad($conn,$username);
		
		$fecha_actual=date("Y-m-d");
		
		///todo  el html como se quiera
	?>
	
	<br>
	<form action="ativu_del.php" method="post" name="form1">
	<?php
		$principal="desembarque.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
  <br>			
<?php	
		$sql="select to_char(vh.vuehis_fecha,'YYYY-MM-DD'),v.vue_codigo,vh.avi_id,vh.vue_peso_max,vh.vue_vol_max,v.vue_origen,v.vue_destino,vh.vuehis_id "
			."from vuelo_historial vh,vuelo v "
			."where v.vue_codigo=vh.vue_codigo "
			."and v.vue_destino='$origen_usuario' "
			."and vh.vuehis_despachado='1' "
			."and vh.vuehis_receptado='0' "
			."and to_char(vh.vuehis_fecha)='$fecha_actual' "			
			."order by vh.vuehis_fecha,v.vue_codigo";
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Records found.'));
		else
		{
			//vh.avi_id,vh.vuehis_fecha,vh.vue_peso_max,vh.vue_vol_max,v.vue_codigo,v.vue_origen,v.vue_destino,vh.vuehis_id
			$mainheaders=array("Fecha","Nro Vuelo","Avión","Peso Máx","Volumen Máx","Origen","Destino","Escoja Vuelo");
			/*
			build_table_sindel(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
			*/
			$titulo="Listado de vuelos para ".$fecha_actual;		
			build_table_sindel($recordSet,false,$mainheaders,$titulo,
			'images/360/yearview.gif','50%','true','desembarque1.php',$param_destino,"total",0);
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
