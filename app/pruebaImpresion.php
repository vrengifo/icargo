<?php
  //no se puede usar variables de sesión
  include_once("includes/main.php");
  extract($_REQUEST);

  include_once("class.ezpdf.php");
  $pdf =& new Cezpdf();
  $pdf->selectFont('../pdf/fonts/Helvetica');
  
  //datos sistema
  include_once("class/c_documento.php");
  $oDoc=new c_documento($conn,$sUsername,$sTerminal);
  $oDoc->info($id);
  
  include_once("class/c_estacion.php");
  $oEst=new c_estacion($conn);
  $oEst->info($oDoc->doc_origen);
  
  include_once("class/c_oficina.php");
  $oOfi=new c_oficina($conn);
  $oOfi->info($oDoc->ofi_id);
  
  include_once("class/c_stock_tipo.php");
  $oStoTip=new c_stock_tipo($conn);
  $oStoTip->info($oDoc->stotip_id);
  
  include_once("class/c_cliente.php");
  $oCliente=new c_cliente($conn);
  $oCliente->info($oDoc->cli_codigo);
  
  include_once("class/c_tipo_carga.php");
  $oTipCar=new c_tipo_carga($conn);
  $oTipCar->info($oDoc->tipcar_id);
  //$oTipCar->tipcar_descripcion
  
  include_once("class/c_verdadfalso.php");
  $oVF=new c_verdadfalso($conn);
  $sql=$oVF->info($oDoc->doc_sobredocumento);
  
  include_once("class/c_usuario.php");
  $oUsuario=new c_usuario($conn);
  $oUsuario->info($usua);
  
   
  
  //Logo
  $pdf->addJpegFromFile('images/logo.jpg',500,770,100);
  //Cabecera
  $pdf->ezText('ICARO AIR',14);
  $pdf->ezText("Principal: Quito-Ecuador.  Palora 125 y Amazonas",10);
  $pdf->ezText("RUC: ".$oEst->est_ruc,10);
  $pdf->ezText("Autorización SRI No. ".$oEst->est_autsri,10);
  $pdf->ezText('',12);
  $pdf->ezText('',12);
  $pdf->line(5,750,590,750);
  
  //datos
  $pdf->ezText($oStoTip->stotip_nombre.' Nro. '.$oOfi->ofi_establecimiento.' '.$oDoc->completarCeros($oDoc->sto_nro,7),12);
  
  $pdf->addTextWrap(400,725,200,11,"FECHA: ".$oDoc->doc_fecharec);
  
  $pdf->ezText('Origen - Destino : '.$oDoc->doc_origen.' - '.$oDoc->doc_destino,12);
  
  //cliente
  $pdf->ezText('',12);
  $pdf->ezText('Cliente:                  '.$oCliente->cli_nombre,12);
  $pdf->ezText('CI / RUC:               '.$oCliente->cli_ciruc,12);
  $pdf->ezText('Dirección Cliente:  '.$oCliente->cli_direccion,12);
  $pdf->ezText('Teléfono Cliente:   '.$oCliente->cli_telefono,12);
  
  //destinatario
  $pdf->ezText('',12);
  $pdf->ezText('Destinatario:             '.$oDoc->doc_destnombre,12);
  $pdf->ezText('CI / RUC Dest.:        '.$oDoc->doc_destciruc,12);
  $pdf->ezText('Telf. Destinatario:     '.$oDoc->doc_desttelf,12);
  
  //datos
  $pdf->line(5,550,590,550);
  
  $pdf->ezText('',12);
  $pdf->ezText('Detalle',12);
  $pdf->ezText('Piezas (nro.): '.$oDoc->doc_nropiezas,12);
  $pdf->ezText('Peso (Kg): '.$oDoc->doc_peso,12);
  $pdf->ezText('Volumen (m3): '.$oDoc->doc_volumen,12);
  $pdf->ezText('Valor Declarado ($): '.$oDoc->doc_valordeclarado,12);
  
  $pdf->ezText('Contenido: '.$oTipCar->tipcar_descripcion,12);
  $pdf->ezText('Descripción Contenido: '.$oDoc->doc_descripcion,12);
  $pdf->ezText('Sobre con Documentos: '.$oVF->vf_texto,12);
  $pdf->ezText('Sin dinero ni joyas.  Viaja a cuenta y riesgo del remitente',12);
  $pdf->ezText('Observación: '.$oDoc->doc_observacion,12);
  
  //costo
  $pdf->ezText('',12);
  $pdf->ezText('Costos de Envío',12);
  $pdf->line(5,398,590,398);
  $pdf->ezText('Carga o Corresp.: '.$oDoc->doc_costo,12);
  $pdf->ezText('Seguro:                '.$oDoc->doc_seguro,12);
  $pdf->ezText('Subtotal:               '.$oDoc->doc_subtotal,12);
  $pdf->ezText('IVA:                      '.$oDoc->doc_iva,12);
  $pdf->line(5,345,200,345);
  $pdf->ezText('TOTAL:                 '.$oDoc->doc_total,12);
  
  //piezas o paquetes
  $pdf->ezText('',12);
  $pdf->ezText('',12);
  $pdf->ezText('Códigos de Barra:',12);
  //cod bar
  $arrCB=explode(" ",$oDoc->CargaDocumento($id," "));
  for($i=0;$i<count($arrCB);$i++)
    $pdf->ezText($arrCB[$i],11);
  
  
  
  //responsable
  $pdf->line(380,220,550,220);
  $pdf->addTextWrap(385,200,200,11," Responsable: ".$oUsuario->usu_nombre);

  $pdf->ezStream();
?>