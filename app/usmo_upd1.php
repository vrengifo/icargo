<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

	extract($_REQUEST);		

	$sqld="delete from usuario_aplicacion "
		."where usu_codigo='$id'";
	$rs = &$conn->Execute($sqld);

  for($i=0;$i<$total;$i++)
  {
	if(isset($chc[$i]))
	{
		$core_id=$chc[$i];
		$sqli="insert into usuario_aplicacion "
			."(usu_codigo,id_aplicacion) values "
			."('$id',$core_id)";
		$rs = &$conn->Execute($sqli);			
	}		
  }	

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&id=".$id;
header($destino);
?>