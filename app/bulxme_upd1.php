<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include_once('class/c_bulto.php');
  include_once('class/c_detalle_bulto.php');
  
  extract($_REQUEST);
  
  //$conn->debug=true;
  
  $oBulto=new c_bulto($conn,$sUsername);
  $oDetBulto=new c_detalle_bulto($conn,$sUsername);
  
  //datos del list
  $cadena = substr($cadena,0,strlen($cadena) - 1);
  $arregloCadena = split(",",$cadena);
  
  $tamArrCad=count($arregloCadena);
  if($tamArrCad>0)
  {
  	//eliminar los antes ingresados
  	$oDetBulto->delAll($idBulto);
  	$oDetBulto->manemb=$idp;
  	//detalle_bulto
  	for($i=0;$i<$tamArrCad;$i++)
  	{
  	  
  	  $oDetBulto->detdoc_ref=$arregloCadena[$i];
  	  $oDetBulto->bul_ref=$idBulto;
  	  $oDetBulto->add();
  	}
  	
  	/*
  	//impresion
  	$oBulto->imprimirCBBulto($sTerminal,$bulId,0,"ICARO",substr($oME->manemb_origen,0,3),substr($oME->manemb_destino,0,3));
  	*/
  }

//destino
$cextra=explode("|",$campo_extra);
$t_cextra=count($cextra);
for ($i=0;$i<$t_cextra;$i++)
{
	$c1=$cextra[$i];
	if($c1=="principal")
	  $destino="location:".$$c1."?";
	else	
	  $cad_dest.=$c1."=".$$c1."&";
}
$cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
$destino=$destino.$cad_dest;

$destino="location:bulxmanemb.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&idp=$idp";

//echo "$destino";
header($destino);
?>