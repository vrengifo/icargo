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
  
  echo($oObj->adminAdd("akilxest_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"A�adir Kilo Equivalencia"));
  
  buildsubmenufooter();
?>