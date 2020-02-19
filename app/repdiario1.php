<?php 
  session_start();
  include_once("includes/main.php");
  
  extract($_REQUEST);
  //$conn->debug=true;
  
  include_once("class/c_reporte_venta.php");
  $oRepVen=new c_reporte_venta($conn,$sUsername,$sOficina);
  
  if($total_fil>0)
  {
    $oRepVen->repven_fecha=$fecha;
    $oRepVen->repven_por=$sUsername;
    $oRepVen->repven_total_cash=$rtotal_cash;
    $oRepVen->repven_total_collect=$rtotal_collect;
    $oRepVen->repven_total_credito=$rtotal_credito;
    $oRepVen->repven_total=$rtotal;
  
    //$oRepVen->mostrar_dato();
  
    $repId=$oRepVen->add();
  
    include_once("class/c_documento.php");
    $oDoc=new c_documento($conn,$sUsername,$sTerminal);
    for($i=0;$i<$total_fil;$i++)
    {
  	  $oDoc->updRepventas($repId,$doc[$i]);
    }
  }

  //destino
  $cextra=explode("|",$campo_extra);
  $t_cextra=count($cextra);
  for ($i=0;$i<$t_cextra;$i++)
  {
	$c1=$cextra[$i];
	$cad_dest.=$c1."=".$$c1."&";
  }
  $cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
  $destino="location:repdiario.php?".$cad_dest."&id=".$idp;
  
  header($destino);
  
?>