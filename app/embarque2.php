<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

	extract($_REQUEST);		
	$vfecha=date("Y-m-d H:i");
	
	$sql_me="insert into manifiesto_embarque "
			."(vuehis_id,manemb_nro,manemb_fecha,manemb_por,manemb_origen,manemb_destino)"
			." values "
			."($vuehis_id,'$manemb_nro',to_date('$vfecha','YYYY-MM-DD HH24:MI'),'$sUsername','$vorigen','$vdestino')";
	$rs = &$conn->Execute($sql_me);
	
	$sql_meid="select manemb_id from manifiesto_embarque "
			."where vuehis_id=$vuehis_id "
			."and manemb_nro='$manemb_nro' and to_char(manemb_fecha,'YYYY-MM-DD HH24:MI')='$vfecha' "
			."and manemb_por='$sUsername' ";
	$rs1 = &$conn->Execute($sql_meid);
	$manemb_id=$rs1->fields[0];
			

  for($i=0;$i<$total;$i++)
  {
	if(isset($chc[$i]))
	{
		$gui_id=$chc[$i];
		$sqli="insert into manemb_detalle "
			."(manemb_id,gui_id) values "
			."($manemb_id,$gui_id)";
		$rs2 = &$conn->Execute($sqli);
		
		$sqlu="update guia set manemb_id=$manemb_id where gui_id=$gui_id ";
		$rs3 = &$conn->Execute($sqlu);
	}		
  }
  
  //actualizar el vuelo_historial a despachado
  $sql_vh="update vuelo_historial set vuehis_despachado='1' where vuehis_id=$vuehis_id ";	
  $rs4 = &$conn->Execute($sql_vh);

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&id=".$id;
header($destino);
?>