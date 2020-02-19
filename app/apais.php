<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_pais.php");
  $oObj=new c_pais($conn);
  echo ($oObj->adminAdmin("apais_del.php","apais.php",$id_aplicacion,$id_subaplicacion,"apais_add.php","apais_upd.php","Administración de Paises"));
  
  buildsubmenufooter();		
?>