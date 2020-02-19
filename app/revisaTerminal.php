<?php
  session_start();
  include('includes/main.php');
  //include('adodb/tohtml.inc.php');
  include('class/c_terminal.php');
  
  extract($_REQUEST);
  $oTer=new c_terminal($conn);
  
  $res=$oTer->buscaxTeridTerip($idTer,$ipTer);
  if($res!="0")
  {
    $sTerminal=$res;
    session_register("sTerminal");
  }
  echo($res);
  
?>