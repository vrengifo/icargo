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
  
  if($act=="add")
    $titulo="Kilo Equivalencia Añadido";
  else 
    $titulo="Actualizar Kilo Equivalencia";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("kilxcli_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador,$padre));
  
  buildsubmenufooter();
?>