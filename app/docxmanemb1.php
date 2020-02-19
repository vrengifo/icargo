<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  //$conn->debug=true;
  
  include_once("class/c_documento.php");
  $oDoc=new c_documento($conn,$sUsername,$sTerminal);

  //eliminar todo lo cargado anteriormente x manemb_id
  $sqlDel=<<<va
  delete from manemb_detalle
  where manemb_id=$idp
va;
  $rsDel=&$conn->Execute($sqlDel);
  
  for($i=0;$i<$total;$i++)
  {
	if(isset($chc[$i]))
	{
	  $manemb=$idp;
	  $oDoc->cad2id($chc[$i]);
	  $sql=<<<va
	  insert into manemb_detalle
	  (manemb_id,sto_id,ofi_id,stotip_id,sto_nro)
	  values
	  ($manemb,'$oDoc->sto_id','$oDoc->ofi_id','$oDoc->stotip_id','$oDoc->sto_nro')  
va;
	  $rsI=&$conn->Execute($sql);
	}
  }	

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp;
header($destino);
?>