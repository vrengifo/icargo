<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_tarjeta.php");
  $oObj=new c_tarjeta($conn);
  
  if($act=="add")
    $titulo="Tarjeta A�adida";
  else 
    $titulo="Actualizar Tarjeta";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("atar_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>