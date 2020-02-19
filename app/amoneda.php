<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_moneda.php");
  $oObj=new c_moneda($conn);
  echo ($oObj->adminAdmin("amon_del.php","amoneda.php",$id_aplicacion,$id_subaplicacion,"amon_add.php","amon_upd.php","Administración de Monedas"));
  
  buildsubmenufooter();		
?>