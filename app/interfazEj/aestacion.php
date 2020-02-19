<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_estacion.php");
  $oObj=new c_estacion($conn);
  echo ($oObj->adminAdmin("aest_del.php","aestacion.php",$id_aplicacion,$id_subaplicacion,"aest_add.php","aest_upd.php","Administración de Estaciones"));
  
  buildsubmenufooter();		
?>