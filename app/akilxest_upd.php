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
  
  if($act=="add")
    $titulo="Kilo Equivalencia A�adido";
  else 
    $titulo="Actualizar Kilo Equivalencia";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("akilxest_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>