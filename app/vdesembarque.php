<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php
error_reporting(E_ALL);
		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion);
		
		include('class/c_usuario.php');
		$cusuario=new c_usuario($conn);
		//$origen_usuario=$cusuario->recuperar_ciudad($conn,$username);
		$origen_usuario=$sEstacion;
		
		$fecha_actual=date("Y-m-d");
		
		///todo  el html como se quiera
	?>
	
	<br>
	<form action="ativu_del.php" method="post" name="form1">
	<?php
		$principal="vembarque.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
  <br>			
<?php	
		$sql="select meb.manemb_nro,to_char(meb.manemb_fecha,'YYYY-MM-DD HH24:MI'),meb.manemb_por,meb.manemb_origen,meb.manemb_destino,to_char(mde.mandes_fecharec,'YYYY-MM-DD HH24:MI'),mde.mandes_por,mde.manemb_id "
			."from manifiesto_embarque meb,manifiesto_desembarque mde "
			."where "
			."meb.manemb_id=mde.manemb_id "
			//."and to_char(mde.mandes_fecharec,'YYYY-MM-DD')='$fecha_actual' "
			."and ( nvl(mde.mandes_finalizado,'0')='0' or (mde.mandes_finalizado='0')) "
			."order by mde.mandes_fecharec ";
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Records found.'));
		else
		{
			//manemb_nro,to_char(manemb_fecha,'YYYY-MM-DD'),manemb_por,manemb_origen,manemb_destino,manemb_id
			$mainheaders=array("Nro Manifiesto","Fecha","Por","Origen","Destino","Fecha de Recepción","Receptado por","Ver Manifiesto");
			/*
			build_table_sindel(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
			*/
			$titulo="Manifiesto de Desembarque de Carga en ".$fecha_actual;		
			build_table_sindel($recordSet,false,$mainheaders,$titulo,
			'images/360/yearview.gif','50%','true','vdesembarque1.php',$param_destino,"total",0);
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
