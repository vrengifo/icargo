<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_stockxoficina.php");
  $oObj=new c_stockxoficina($conn,$sUsername);
  
  if($act=="add")
    $titulo="Stock Añadido";
  else 
    $titulo="Actualizar Stock";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("stoxofi_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador,$sOficina));
  
  buildsubmenufooter();
?>