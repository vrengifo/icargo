<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_terminal.php");
  $oObj=new c_terminal($conn);
  echo ($oObj->adminAdmin("ater_del.php","aterminal.php",$id_aplicacion,$id_subaplicacion,"ater_add.php","ater_upd.php","Administración de Terminales"));
  
  buildsubmenufooter();		
?>