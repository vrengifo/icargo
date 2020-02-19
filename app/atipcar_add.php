<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_tipo_carga.php");
  $oObj=new c_tipo_carga($conn);
  
  echo($oObj->adminAdd("atipcar_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Tipo de Carga"));
  
  buildsubmenufooter();
?>