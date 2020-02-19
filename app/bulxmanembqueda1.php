<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  //$conn->debug=true;
  
  include_once("class/c_manembxbultoqueda.php");
  $oMEXBQ=new c_manembxbultoqueda($conn,$sUsername);
  $oMEXBQ->delAll($idp);

  for($i=0;$i<$total;$i++)
  {
	if(isset($chc[$i]))
	{
	  $oMEXBQ->manemb_id=$idp;
	  $oMEXBQ->bul_ref=$chc[$i];
	  $oMEXBQ->add();
	}
  }	

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp;
header($destino);
?>