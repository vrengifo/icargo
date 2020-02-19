<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_tarjeta.php");
  $oObj=new c_tarjeta($conn);
  echo ($oObj->adminAdmin("atar_del.php","atarjeta.php",$id_aplicacion,$id_subaplicacion,"atar_add.php","atar_upd.php","Administración de Tarjeta"));
  
  buildsubmenufooter();		
?>