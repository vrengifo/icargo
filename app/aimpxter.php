<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  /*
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  */
  
  include_once("class/c_impresionxterminal.php");
  $oObj=new c_impresionxterminal($conn);
  echo ($oObj->adminAdmin("aimpxter_del.php","aimpxter.php",$id_aplicacion,$id_subaplicacion,"aimpxter_add.php","aimpxter_upd.php","Administración de Impresiones por Terminal",$padre));
  
  buildsubmenufooter();		
?>