<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php
  extract($_REQUEST);
  include_once("class/c_im_usuxprov.php");

  $clase=new c_im_usuxprov($session_username,$conn);
	
  $clase->delxprov($id);
  
  for($i=0;$i<$total;$i++)
  {
	if(isset($chc[$i]))
	{
	  $clase->pro_id=$id;
	  $clase->username=$chc[$i];
	  //$clase->mostrar_dato();
	  $clase->add();
	}
  }	

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&id=".$id;
header($destino);
?>