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
  echo ($oObj->adminAdmin("stoxofi_del.php","stock.php",$id_aplicacion,$id_subaplicacion,"stoxofi_add.php","stoxofi_upd.php","Administración de Stocks",$sOficina));
  
  buildsubmenufooter();		
?>