<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

	extract($_REQUEST);		

	$sqld="delete from perfil_aplicacion "
		."where perfil_id='$id'";
	$rs = &$conn->Execute($sqld);

  for($i=0;$i<$total;$i++)
  {
	if(isset($chc[$i]))
	{
		$core_id=$chc[$i];
		$sqli="insert into perfil_aplicacion "
			."(perfil_id,id_aplicacion) values "
			."('$id',$core_id)";
		$rs = &$conn->Execute($sqli);			
	}		
  }	

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&id=".$id;
header($destino);
?>