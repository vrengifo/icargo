<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_usuario.php");
  $oObj=new c_usuario($conn);
  echo ($oObj->adminAdmin("user_del.php","user.php",$id_aplicacion,$id_subaplicacion,"user_add.php","user_upd.php","Administración de Usuarios",$sOficina));
  
  buildsubmenufooter();		
?>