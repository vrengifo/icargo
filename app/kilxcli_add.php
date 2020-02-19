<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  /*
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  */
  
  include_once("class/c_kiloequivalenciaxcli.php");
  $oObj=new c_kiloequivalenciaxcli($conn,$sUsername);
  
  echo($oObj->adminAdd("kilxcli_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Kilo Equivalencia",$padre));
  
  buildsubmenufooter();
?>