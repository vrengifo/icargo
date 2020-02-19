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
  
  if($act=="add")
    $titulo="Impresión de Terminal Añadida";
  else 
    $titulo="Actualizar Impresión de Terminal";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("aimpxter_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador,$padre));
  
  buildsubmenufooter();
?>