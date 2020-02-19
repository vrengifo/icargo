<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_ciudad.php");
  $oObj=new c_ciudad($conn);
  echo ($oObj->adminAdmin("aciudad_del.php","aciudad.php",$id_aplicacion,$id_subaplicacion,"aciudad_add.php","aciudad_upd.php","Administración de Ciudades"));
  
  buildsubmenufooter();		
?>