<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

	extract($_REQUEST);		
	$vfecha=date("Y-m-d H:i");
	
	$sql_me="insert into manifiesto_desembarque "
			."(manemb_id,mandes_fecharec,mandes_por)"
			." values "
			."($manemb_id,to_date('$vfecha','YYYY-MM-DD HH24:MI'),'$sUsername')";
	$rs = &$conn->Execute($sql_me);
	
	$sql_meid="select mandes_id from manifiesto_desembarque "
			."where  "
			."manemb_id=$manemb_id and to_char(mandes_fecharec,'YYYY-MM-DD HH24:MI')='$vfecha' "
			."and mandes_por='$sUsername' ";
	$rs1 = &$conn->Execute($sql_meid);
	$mandes_id=$rs1->fields[0];
			

  for($i=0;$i<$total;$i++)
  {
	$gui_id=$guia[$i];
	if(isset($chc[$i]))	
		$vok="1";
	  else
	    $vok="0";	
	$sqli="insert into mandes_detalle "
			."(mandes_id,gui_id,mandesdet_ok) values "
			."($mandes_id,$gui_id,'$vok')";
	$rs2 = &$conn->Execute($sqli);
/*		
	$sqlu="update guia set manemb_id=$manemb_id where gui_id=$gui_id ";
	$rs3 = &$conn->Execute($sqlu);
*/		
  }
  
  //actualizar el vuelo_historial a despachado
  $sql_vh="update vuelo_historial set vuehis_receptado='1' where vuehis_id=$vuehis_id ";	
  $rs4 = &$conn->Execute($sql_vh);

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&id=".$id;
header($destino);
?>