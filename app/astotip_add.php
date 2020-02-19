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
  
  echo($oObj->adminAdd("astotip_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Stock Tipo"));
  
  buildsubmenufooter();
?>