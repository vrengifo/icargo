<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_oficina.php");
  $oObj=new c_oficina($conn);
  echo ($oObj->adminAdmin("aofi_del.php","aoficina.php",$id_aplicacion,$id_subaplicacion,"aofi_add.php","aofi_upd.php","Administración de Oficinas"));
  
  buildsubmenufooter();		
?>