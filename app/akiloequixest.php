<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_kiloequivalenciaxest.php");
  $oObj=new c_kiloequivalenciaxest($conn,$sUsername);
  echo ($oObj->adminAdmin("akilxest_del.php","akiloequixest.php",$id_aplicacion,$id_subaplicacion,"akilxest_add.php","akilxest_upd.php","Administración de Equivalencias de Kilos - Estaciones"));
  
  buildsubmenufooter();		
?>