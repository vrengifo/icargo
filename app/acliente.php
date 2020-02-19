<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_cliente.php");
  $oObj=new c_cliente($conn);
  echo ($oObj->adminAdmin("acli_del.php","acliente.php",$id_aplicacion,$id_subaplicacion,"acli_add.php","acli_upd.php","Administración de Clientes"));
  
  buildsubmenufooter();		
?>