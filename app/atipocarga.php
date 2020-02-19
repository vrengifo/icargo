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
  echo ($oObj->adminAdmin("atipcar_del.php","atipocarga.php",$id_aplicacion,$id_subaplicacion,"atipcar_add.php","atipcar_upd.php","Administración de Tipo de Carga"));
  
  buildsubmenufooter();		
?>