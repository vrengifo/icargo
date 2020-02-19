<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_oficina.php");
  $oObj=new c_oficina($conn);
  
  if($act=="add")
    $titulo="Oficina Añadida";
  else 
    $titulo="Actualizar Oficina";
  $identificador=$id;
  
  echo($oObj->adminUpd("aofi_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>