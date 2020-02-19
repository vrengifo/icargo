<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
/*		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion); */
		///todo  el html como se quiera
		//$fecha=date("Y-m-d");
		//component: com_id:compn
		$componente=explode(":",$component);		
		$com_id=$componente[0];
		$com_pn=$componente[1];
//$rs = &$conn->Execute($sql);
//			while(!$rs->EOF)
//			  $v_ass_id=$rs->fields[0];

if($vaction=="m")
{
  for($i=0;$i<$total;$i++)
  {
	$vdone=$done[$i];
	$vmar_id=$marid[$i];
	//update en mai_ota_asscom_maiact
	$sqlu="update mai_ota_asscom_maiact set "
		."asma_done=$vdone "
		."where asco_id=$serial and mar_id=$vmar_id ";
	$rs = &$conn->Execute($sqlu);
	//update en mai_ota_ass_com
	$sqlu1="update mai_ota_ass_com set "
		."asco_date='$fecha',asco_logref='$log_ref',asco_action='$vaction' "
		."where asco_id=$serial";
	$rs = &$conn->Execute($sqlu1);
  }	
}
if($vaction=="i")
{
//insertar el serial y position en la tabla mai_ota_ass_com
$sqli="insert into mai_ota_ass_com ("
	."ass_id,com_id,asco_sn,asco_date,asco_position,asco_pn,asco_logref,asco_action) values "
	."($assembly,$com_id,'$serial','$fecha','$position','$com_pn','$log_ref','$vaction')";
$rs = &$conn->Execute($sqli);
//echo "sqli = $sqli <br>";	
//recuperar el id asco_id
$sql_asco_id="select asco_id from mai_ota_ass_com "
		."where ass_id=$assembly and com_id=$com_id and asco_sn='$serial' and asco_position='$position' and asco_pn='$com_pn' and asco_action='$vaction' and asco_logref='$log_ref'";
$rs = &$conn->Execute($sql_asco_id);
//echo "sql_asco_id = $sql_asco_id <br>";
$asco_id=$rs->fields[0];
//echo "asco_id = $asco_id <br>";		
//insertar en mai_ota_asscom_maiact los valores del detalle e insertar asco_id,mar_id,asma_valor y asma_done=0
for($i=0;$i<$total;$i++)
{
	$vmarid=$marid[$i];
	$vvalor=$valor[$i];
	if(strlen($vvalor)<=0)
	  $vvalor=0;
	$sqli_ass="insert into mai_ota_asscom_maiact (asco_id,mar_id,asma_valor,asma_done) values "
		."($asco_id,$vmarid,$vvalor,0)";
	//echo "sqli_ass = $sqli_ass <br>";		
	$rs = &$conn->Execute($sqli_ass);
}	
}
if($vaction=="r")
{
  if(strlen($reason)<=0)
    $reason="-";
  $sqlr="insert into mai_ota_asscom_removal (asco_id,assre_ur,assre_reason,assre_date,cost_id) values "
	."($serial,$ur,'$reason','$fecha',$newstatus)";
  $rs = &$conn->Execute($sqlr);
  //update en mai_ota_ass_com
  $sqlu1="update mai_ota_ass_com set "
		."asco_date='$fecha',asco_logref='$log_ref',asco_action='$vaction' "
		."where asco_id=$serial";
  $rs = &$conn->Execute($sqlu1);  
}
//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
header($destino);
?>