<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  //$conn->debug=true;
  
  include_once("class/c_manifiesto_desembarque.php");
  $oMD=new c_manifiesto_desembarque($conn,$sUsername);
  $oMD->manemb_id=$id;
  $oMD->mandes_fecharec=date("Y-m-d");
  $oMD->mandes_por=$sUsername;
  $oMD->add();
  
  include_once("class/c_mandesxbulto.php");
  $oMDXB=new c_mandesxbulto($conn,$sUsername);
  $oMDXB->delAll($id);

  for($i=0;$i<$total;$i++)
  {
	if(isset($chc[$i]))
	{
	  $oMDXB->manemb_id=$id;
	  $oMDXB->bul_ref=$chc[$i];
	  $oMDXB->add();
	}
  }	

//destino
//$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp;
$destino="location:desembarque1.php?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&id=".$id;
header($destino);
?>