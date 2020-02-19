<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include('class/c_documento.php');
  
  include_once("class/c_stock_tipo.php");
  $oST=new c_stock_tipo($conn);

  //$conn->debug=true;
  
  extract($_REQUEST);
  
  $clase=new c_documento($conn,$sUsername,$sTerminal);

	$clase->stotip_id=$seStockTipo;
	$oST->info($seStockTipo);
	
	$clase->ofi_id=$tOfiId;
	$clase->sto_id=$tStoId;
	$clase->sto_nro=$tNro;
	$clase->doc_fecharec=$fecha." ".$hora.":00";
	$clase->doc_nropiezas=$tNropiezas;
	$clase->doc_peso=$tPeso;
	$clase->doc_volumen=$tVolumen;
	$clase->doc_valordeclarado=$tValorDeclarado;
	$clase->tipcar_id=$seTipoCarga;
	$clase->doc_descripcion=$seDescripcion;	
	$clase->doc_sobredocumento=$seSobreDoc;
  	
  	if(strlen($tObservacion)==0)
  	  $tObservacion="-";
  	$clase->doc_observacion=$tObservacion;
  	    
	$clase->doc_origen=$tDocOrigen;
	$clase->cli_codigo=$seCliente;
	$clase->doc_destino=$seDocDestino;
	$clase->doc_destnombre=$tDestNombre;
	$clase->doc_destciruc=$tDestCiruc;
	$clase->doc_desttelf=$tDestTelf;
	//$clase->doc_formapago=$rFormaPago;
	if($oST->stotip_convenio=="1")
	  $clase->doc_formapago="C";
	else  
	  $clase->doc_formapago="E";
	$clase->doc_costo=$tCosto;
	$clase->doc_seguro=$tSeguro;
	$clase->doc_subtotal=$tSubtotal;
	$clase->doc_iva=$tIva;
	$clase->doc_total=$tTotal;
	
	/*$clase->gui_ced_destinatario=$destinatario_ciruc;
	$clase->gui_dir_destinatario=$destinatario_direccion;
	$clase->gui_telf_destinatario=$destinatario_telf;
	$clase->gui_fletecarga=$flete;
	$clase->gui_entregadomicilio=$entdom=0;
	$clase->gui_transadicional=$tadic=0;
	$clase->gui_servadicional=$sadic=0;
	$clase->gui_correspondencia=$corres;
	$clase->gui_seguros=$seguro=0;
	$clase->gui_embalaje=$embalaje=0;
	$clase->gui_subtotal=$subtotal;
	$clase->gui_descuento=$descuento;
	$clase->gui_iva=$iva;
	$clase->gui_total=$vtotal;
	$clase->ciu_codigo=$destino;
	$clase->gui_sobrepeso=$sobrepeso;
	
	//campos seteados con 0
	$clase->repven_id=0;//reporte de ventas
	$clase->manemb_id=0;//manifiesto de embarque
	$clase->gui_entregada="0";//indica si la guía fue entregada
	*/
	//$clase->mostrar_dato();
	$idp=$clase->add();
	//echo "$idp <br>";

	//destino
	$cextra=explode("|",$campo_extra);
	$t_cextra=count($cextra);
	for ($i=0;$i<$t_cextra;$i++)
	{
	  $c1=$cextra[$i];
	  $cad_dest.=$c1."=".$$c1."&";
}
$cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
$destino="location:documento_view.php?".$cad_dest."&id=".$idp;
//echo $destino;
header($destino);
?>