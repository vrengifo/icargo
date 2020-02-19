<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_perfil.php");
  $oObj=new c_perfil($conn);
  echo ($oObj->adminAdmin("aperfil_del.php","aperfil.php",$id_aplicacion,$id_subaplicacion,"aperfil_add.php","aperfil_upd.php","Administración de Perfiles"));
  
  buildsubmenufooter();		
?>