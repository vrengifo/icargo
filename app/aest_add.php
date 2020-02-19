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
  
  echo($oObj->adminAdd("aest_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Estación"));
  
  buildsubmenufooter();
?>