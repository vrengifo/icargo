<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  //$conn->debug=true;
  
  include_once("class/c_detalle_bulto.php");
  $oDetBul=new c_detalle_bulto($conn,$sUsername);

  include_once("class/c_des_paquete.php");
  $oDesPaq=new c_des_paquete($conn,$sUsername);

  for($i=0;$i<$total;$i++)
  {
	if(isset($cad[$i]))
	{
	  $oDesPaq->manemb_id=$idp;
	  $oDesPaq->bul_ref=$idBul;
	  
	  $oDetBul->cad2id($cad[$i]);
	  $oDesPaq->detdoc_ref=$oDetBul->detdoc_ref;
	  $oDesPaq->ubicacion=$ubi[$i];
	  
	  //$oDesPaq->mostrar_dato();
	  
	  $res=$oDesPaq->crearActualizar();
		
	}
  }	

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp."&idBul=".$idBul;
//echo($destino);
header($destino);
?>