<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);		
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  include_once("class/c_manifiesto_embarque.php");
  $oObj=new c_manifiesto_embarque($conn,$sUsername);
  
  echo ($oObj->adminAdmin("","embarque.php",$id_aplicacion,$id_subaplicacion,"embarque_add.php","embarque_upd.php","Manifiesto de Embarque",$sEstacion));
  
  buildsubmenufooter();
?>