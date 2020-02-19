<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  //$conn->debug=true;
  
  /*
  include_once("class/c_manembxbulto.php");
  $oMEXB=new c_manembxbulto($conn,$sUsername);
  
  include_once("class/c_manembxbultoqueda.php");
  $oMEXBQ=new c_manembxbultoqueda($conn,$sUsername);
  */
  
  include_once("class/c_manembxbultoreal.php");
  $oMEXBR=new c_manembxbultoreal($conn,$sUsername);
  
  //$idp --- manemb_id	
  /*
  $sql=<<<va
	INSERT INTO MANEMBXBULTOREAL
	(MANEMB_ID,BUL_REF,USU_AUDIT,USU_FAUDIT)
	select manemb_id,bul_ref,'$sUsername',to_date('$oMEXB->usu_faudit','$oMEXB->fechaLarga')
	from manembxbulto 
	where manemb_id=$idp 
	and manemb_id||':'||bul_ref not in
	(select manemb_id||':'||bul_ref from manembxbultoqueda where manemb_id=$idp )  
va;
  $rs=&$conn->Execute($sql);*/
  $oMEXBR->crear($idp);
  
  include_once("class/c_manifiesto_embarque.php");
  $oME=new c_manifiesto_embarque($conn,$sUsername);
  $oME->aEnviado($idp);

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp;
header($destino);
?>