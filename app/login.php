<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php
  extract($_REQUEST);
  include_once("./class/c_usuario.php");
  $oUsuario=new c_usuario($conn);
  $res=$oUsuario->verificar_usuario($username,$password);
  if($res=="0")
  {
	header("location:index.php?reason=bad");
  }
  else 
  {
    //registrar las variables de sesion necesarias
    session_start();
    $sUsername=$res;
    session_register("sUsername");
    
    
    require_once('includes/header.php');
    buildmenu($username);
    include('includes/footer.php'); 
  }
?>
