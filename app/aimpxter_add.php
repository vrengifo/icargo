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
  
  include_once("class/c_impresionxterminal.php");
  $oObj=new c_impresionxterminal($conn);
  
  echo($oObj->adminAdd("aimpxter_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"A�adir Impresi�n por Terminal",$padre));
  
  buildsubmenufooter();
?>