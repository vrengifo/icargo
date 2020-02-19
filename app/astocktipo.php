<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_stock_tipo.php");
  $oObj=new c_stock_tipo($conn);
  echo ($oObj->adminAdmin("astotip_del.php","astocktipo.php",$id_aplicacion,$id_subaplicacion,"astotip_add.php","astotip_upd.php","Administración de Stock Tipo"));
  
  buildsubmenufooter();		
?>