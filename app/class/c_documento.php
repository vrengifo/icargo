<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_stockxoficina.php");
include_once("class/c_stock_historial.php");
include_once("class/c_estacion.php");
include_once("class/c_cliente.php");
include_once("class/c_reporte_venta.php");
include_once("class/c_tipo_carga.php");
include_once("class/c_kiloequivalenciaxcli.php");

include_once("class/c_detalledocumento.php");

include_once("class/c_verdadfalso.php");
include_once("class/c_parametro.php");
include_once("class/c_terminal.php");

class c_documento
{
  var $ofi_id;
  var $stotip_id;
  var $sto_id;
  var $sto_nro;
  var $tipcar_id;
  var $cli_codigo;
  var $repven_id;//solo cuando se asigna a un reporte de ventas
  var $doc_origen;
  var $doc_destino;
  
  var $doc_fecharec;
  var $doc_nropiezas;
  var $doc_peso;
  var $doc_volumen;
  var $doc_valordeclarado;
  var $doc_descripcion;
  var $doc_sobredocumento;
  var $doc_observacion;
  var $doc_destnombre;
  var $doc_destciruc;
  var $doc_desttelf;
  var $doc_subtotal;
  var $doc_total;
  var $doc_formapago;
  
  var $doc_costo;
  var $doc_seguro;
  var $doc_iva;
  
  var $doc_estado;//E= Entregado A= Anulado
  var $doc_estado_usu;
  var $doc_estado_usuf;
  
  var $usu_audit;
  var $usu_faudit;

  //objetos
  var $oStockxoficina;
  var $oStockHistorial;
  var $oEstacion;
  var $oCliente;
  var $oReporteVenta;
  var $oTipoCarga;
  var $oKiloequivalenciaxcli;
  var $oVerdadfalso;
  var $oParametro;
  
  var $msg;
  var $separador;
  var $fechaCorta;
  var $fechaLarga;
  var $terminal;//terminal desde donde se va a imprimir
  var $empresa;
  
  
  //tamaños
  var $lenStotip;
  var $lenOfi;
  var $lenSto;
  var $lenNro;
  var $lenPaquete;

  var $con;
  var $debug;

  function c_documento($conDb,$usuario,$terminal)
  {
	  $this->sto_id="";
	  $this->ofi_id="";
	  $this->stotip_id="";
	  $this->tipcar_id="";
	  $this->cli_codigo="";
	  $this->repven_id=0;
	  $this->doc_origen=0;
	  $this->doc_destino=0;

	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");

	  $this->con=&$conDb;

	  //$this->oStockxoficina=new c_stockxoficina($this->con,$this->usu_audit);
  	  //$this->oStockHistorial=new c_stock_historial($this->con,$this->usu_audit);
  	  //$this->oEstacion=new c_estacion($this->con);
  	  //$this->oCliente=new c_cliente($this->con);
  	  //$this->oReporteVenta=new c_reporte_venta($this->con,$this->usu_audit);
  	  //$this->oTipoCarga=new c_tipo_carga($this->con);
  	  //$this->oKiloequivalenciaxcli=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
  	  //$this->oVerdadfalso=new c_verdadfalso($this->con);
  	  
  	  //$this->oImpresion=new c_impresion();
	  
  	  $oParametro=new c_parametro($this->con);
	  $oParametro->info();
	  
	  $this->fechaCorta=$oParametro->fechaCorta;
	  $this->fechaLarga=$oParametro->fechaLarga;

	  $this->msg="";
	  $this->separador=":";
	  
	  $this->terminal=$terminal;
	  $this->empresa="ICARO";
	  
	  $this->lenStotip=1;
  	  $this->lenOfi=3;
  	  $this->lenSto=4;
  	  $this->lenNro=4;
  	  $this->lenPaquete=2;
  	  
  	  $this->debug=0;

  }

  /**
   * Carga los datos a la clase desde un arreglo
   *
   * @param array $dato
   * @param string $iou ingresooupdate
   * @return boolean
   */
  function cargar_dato($dato,$iou="i")
  {
  	if($iou=="i")
  	{
  	  $ncampos=21;
	  if($ncampos==count($dato))
	  {
        $this->sto_nro=$dato[0];
	    $this->tipcar_id=$dato[1];
	    $this->cli_codigo=$dato[2];
	    $this->doc_destino=$dato[3];
	    $this->doc_fecharec=$dato[4];
	    $this->doc_nropiezas=$dato[5];
	    $this->doc_peso=$dato[6];
	    $this->doc_volumen=$dato[7];
	    $this->doc_valordeclarado=$dato[8];
	    $this->doc_descripcion=$dato[9];
	    $this->doc_sobredocumento=$dato[10];
	    $this->doc_observacion=$dato[11];
	    $this->doc_destnombre=$dato[12];
	    $this->doc_destciruc=$dato[13];
	    $this->doc_desttelf=$dato[14];
	    $this->doc_subtotal=$dato[15];
	    $this->doc_total=$dato[16];
	    
	    $this->doc_costo=$dato[17];
	    $this->doc_seguro=$dato[18];
	    $this->doc_iva=$dato[19];
	    $this->doc_formapago=$dato[20];
	    
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	/*
  	if($iou=="u")
  	{
  	  $ncampos=1;
	  if($ncampos==count($dato))
	  {
	    $this->tipcar_id=$dato[0];
	    $this->cli_codigo=$dato[1];
	    $this->repven_id=$dato[2];
	    $this->doc_origen=$dato[3];
	    $this->doc_destino=$dato[4];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	*/
	return($res);
  }

  /*
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;

  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  <br>
va;
  	$cadId=$this->armarSqlId("o.","t.","s.");
  	$sql=<<<va
  	  select $cadId,
	  o.ofi_nombre,t.stotip_nombre,
	  to_char(s.tipcar_id,'$this->oParametro->fechaCorta') fecha,to_char(s.cli_codigo,'$this->oParametro->fechaCorta') fecha exp,
	  s.repven_id,s.doc_origen,s.doc_destino,
	  $cadId
	  from documento s, oficina o, stock_tipo t
	  where o.ofi_id=s.ofi_id
	  and t.stotip_id=s.stotip_id
	  order by o.ofi_nombre,t.stotip_nombre,fecha desc
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF)
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");
	  $mainheaders=array("Del","Oficina","Tipo Doc","Fecha","Fecha Exp.","V. Inicio","V. Fin","V. Actual","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/360/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
	  //variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
	  $cextra="id_aplicacion|id_subaplicacion|principal";
	}

	$cad.=<<<va
	  <input type="hidden" name="cextra" value="$cextra">
	</form>
va;
	return($cad);
  }
  */

  function validaJs()
  {
  	//<script language="JavaScript" src="js/validation.js"></script>
  	    
  	$cad=<<<va

  	<script language="javascript">
	function valida()
	{
  	  define('seStockTipo', 'num', 'Documento',1,3,document);  
	
	  define('tNro', 'num', 'Nro.',1,10,document);
  	  define('seTipoCarga', 'num,', 'Tipo Carga',1,3,document);
  	  define('tCliente', 'string', 'Cliente',1,20,document);
  	  define('seDestino', 'string', 'Destino',1,7,document);
  	  define('tFechaRec', 'date', 'Fecha Recepción',10,10,document);
  	  define('tNropiezas', 'num', 'Nro. paquetes',1,3,document);
  	  define('tPeso', 'num', 'Actual',1,13,document);
  	  
  	  define('tVolumen', 'num', 'Volumen',1,13,document);
  	  define('tValorDeclarado', 'num', 'Valor Declarado',1,13,document);
  	  define('tDescripcion', 'string', 'Descripción',1,500,document);
  	  define('seSobreDoc', 'string', 'Sobre con Documentos',1,1,document);
  	  define('tObservacion', 'string', 'Observación',1,5000,document);
  	  define('tDestNombre', 'string', 'Nombre Destinatario',1,250,document);
  	  define('tDestCiruc', 'string', 'CI/RUC Destinatario',1,100,document);
  	  define('tDestTelf', 'string', 'Telf. Destinatario',1,200,document);
  	  define('tSubtotal', 'num', 'Subtotal',1,15,document);
  	  define('tTotal', 'num', 'Total',1,13,document);
  	  
  	  define('tCosto', 'num', 'Costo',1,13,document);
  	  define('tSeguro', 'num', 'Seguro',1,13,document);
  	  define('tIva', 'num', 'Iva',1,13,document);
  	}

  	function vValidar()
  	{
  	  var res;
  	  res=validate();
  	  return(res);
  	}

  	function vValidarB(forma,urldestino)
  	{
  	  var res;
  	  res=validate();
  	  if(res)
  	  {
  	    cambiar_action(forma,urldestino);
  	    forma.submit();
  	  }
  	}

  	</script>
va;
	return($cad);
  }

  /*
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo)
  {
	$campo=array(
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oOficina->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Tipo Doc.","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fecha Expiración","nombre"=>"tFechaExp","tipo_campo"=>"date","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Desde","nombre"=>"tIni","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Hasta","nombre"=>"tFin","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Actual","nombre"=>"tActual","tipo_campo"=>"tet","sql"=>"","valor"=>"")

				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
					array("nombre"=>"principal","valor"=>$principal)
				);

	$cadForm=build_addCad($this->con,'false',$titulo,'images/360/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;

	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Add" value="Añadir" onClick="return vValidar();">
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
  }
  */

  /*
  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)
  {
    $oAux=new c_documento($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oOficina->sqlSelect(),"valor"=>$oAux->ofi_id),
				array("etiqueta"=>"* Tipo Doc.","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect(),"valor"=>$oAux->stotip_id),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->tipcar_id),
				array("etiqueta"=>"* Fecha Expiración","nombre"=>"tFechaExp","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->cli_codigo),
				array("etiqueta"=>"* Desde","nombre"=>"tIni","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->repven_id),
				array("etiqueta"=>"* Hasta","nombre"=>"tFin","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->doc_origen),
				array("etiqueta"=>"* Actual","nombre"=>"tActual","tipo_campo"=>"tet","sql"=>"","valor"=>$oAux->doc_destino)

				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
					array("nombre"=>"principal","valor"=>$principal)
				);

	$cadForm=build_updCad($this->con,'false',$titulo,'images/360/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;

	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Upd" value="Actualizar" onClick="return vValidar();">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  */

  function mostrar_dato()
  {
	echo "<hr>Class c_documento(conDb)<br>";
	echo "ofi_id:".$this->ofi_id."<br>";
	echo "stotip_id:".$this->stotip_id."<br>";
	echo "sto_id:".$this->sto_id."<br>";
	echo "tipcar_id:".$this->tipcar_id."<br>";
	echo "cli_codigo:".$this->cli_codigo."<br>";
	echo "repven_id:".$this->repven_id."<br>";
	echo "doc_origen:".$this->doc_origen."<br>";
	echo "doc_destino:".$this->doc_destino."<br>";
	
	echo "doc_fecharec:".$this->doc_fecharec."<br>";
	echo "doc_nropiezas:".$this->doc_nropiezas."<br>";
	echo "doc_peso:".$this->doc_peso."<br>";
	echo "doc_volumen:".$this->doc_volumen."<br>";
	echo "doc_valordeclarado:".$this->doc_valordeclarado."<br>";
	echo "doc_descripcion:".$this->doc_descripcion."<br>";
	echo "doc_sobredocumento:".$this->doc_sobredocumento."<br>";
	echo "doc_observacion:".$this->doc_observacion."<br>";
	echo "doc_destnombre:".$this->doc_destnombre."<br>";
	echo "doc_destciruc:".$this->doc_destciruc."<br>";
	echo "doc_desttelf:".$this->doc_desttelf."<br>";
	echo "doc_subtotal:".$this->doc_subtotal."<br>";
	echo "doc_total:".$this->doc_total."<br>";
	echo "doc_formapago:".$this->doc_formapago."<br>";
	
	echo "doc_costo:".$this->doc_costo."<br>";
	echo "doc_seguro:".$this->doc_seguro."<br>";
	echo "doc_iva:".$this->doc_iva."<br>";
	
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "terminal:".$this->terminal."<br>";
	echo "<hr>";
  }

  function id2cad($ofi,$stotip,$stoId,$stoNro)
  {
    $cad=$ofi.$this->separador.$stotip.$this->separador.$stoId.$this->separador.$stoNro;
    return($cad);
  }

  function cad2id($cad)
  {
    list($this->ofi_id,$this->stotip_id,$this->sto_id,$this->sto_nro)=explode($this->separador,$cad);
    return($cad);
  }

  function armarSqlId($prefijo1,$prefijo2,$prefijo3,$prefijo4)
  {
    $cad=$prefijo1."ofi_id||'".$this->separador."'||".$prefijo2."stotip_id||'".$this->separador."'||".$prefijo3."sto_id||'".$this->separador."'||".$prefijo4."sto_nro";
    return($cad);
  }

  function existe($vid)
  {
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	$oAux->cad2id($vid);
  	$cadId=$this->armarSqlId("","","","");
    $sql=<<<va
    select $cadId
    from documento
    where ofi_id='$oAux->ofi_id'
    and stotip_id='$oAux->stotip_id'
    and sto_id='$oAux->sto_id'
    and sto_nro='$oAux->sto_nro'
va;
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else
	  $res=$rs->fields[0];
	return($res);
  }

  /*
  function nuevoStoId($ofi,$stotip)
  {
    $sql=<<<va
    select nvl(max(to_number(sto_id)),0)
    from documento
    where
    ofi_id='$ofi'
    and stotip_id='$stotip'
va;
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */

  function add()
  {
  	//$this->con->debug=true;
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	
  	$oaStockxOficina=new c_stockxoficina($this->con,$this->usu_audit);
  	$oaSHis=new c_stock_historial($this->con,$this->usu_audit);
  	
  	do 
  	{
  	  $cadId=$oaStockxOficina->recuperarIdDocCompleto($this->ofi_id,$this->stotip_id);
  	  $existe=$this->existe($cadId);//existe en documento
  	  if($existe!="0")
  	  {
  	    $oaSHis->cad2id($cadId);
  	  	$oaStockxOficina->actualizarActual($oaSHis->ofi_id,$oaSHis->stotip_id,$oaSHis->sto_id,$oaSHis->sto_nro);
  	  }
  	}while($existe!="0");
  	
  	$oaSHis->cad2id($cadId);
  	
  	//$cadId=$oAux->id2cad($this->ofi_id,$this->stotip_id,$this->sto_id,$this->sto_nro);
    //$existe=$this->existe($cadId);
    
    $this->ofi_id=$oaSHis->ofi_id;
    $this->stotip_id=$oaSHis->stotip_id;
    $this->sto_id=$oaSHis->sto_id;
    $this->sto_nro=$oaSHis->sto_nro;
    
    $fechaCorta=$this->fechaCorta;
    $fechaLarga=$this->fechaLarga;
    
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into documento
	  (
	    sto_id,ofi_id,stotip_id,sto_nro,
	    tipcar_id,cli_codigo,
	    doc_origen,doc_destino,
	    doc_fecharec,doc_nropiezas,
	    doc_peso,doc_volumen,doc_valordeclarado,
	    doc_descripcion,doc_sobredocumento,doc_observacion,
	    doc_destnombre,doc_destciruc,doc_desttelf,
	    doc_subtotal,doc_total,
	    doc_costo,doc_seguro,doc_iva,doc_formapago,
	    usu_audit,usu_faudit
	  )
	  values
	  (
	    '$this->sto_id','$this->ofi_id','$this->stotip_id','$this->sto_nro',
	    '$this->tipcar_id','$this->cli_codigo',
	    '$this->doc_origen','$this->doc_destino',
	    to_date('$this->doc_fecharec','$fechaLarga'),'$this->doc_nropiezas',
	    '$this->doc_peso','$this->doc_volumen','$this->doc_valordeclarado',
	    '$this->doc_descripcion','$this->doc_sobredocumento','$this->doc_observacion',
	    '$this->doc_destnombre','$this->doc_destciruc','$this->doc_desttelf',
	    '$this->doc_subtotal','$this->doc_total',
	    '$this->doc_costo','$this->doc_seguro','$this->doc_iva','$this->doc_formapago',
	    '$this->usu_audit',to_date('$this->usu_faudit','$fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->ofi_id,$this->stotip_id,$this->sto_id,$this->sto_nro);
	    //impresión y generación de detalle
	    
	    $this->addDetalleImprimir();
	    
	    $this->addStockHistorialactStockxOficina($res);
	  }
	  else
	  {
	    $res="0";
	    $this->msg="Error al añadir dato!!!";
	  }
	}
	else
	{
	  $res=$existe;
	  $this->msg="Dato ya existe!!!";
	}
	return($res);
  }

  /*
  function del($id)
  {
 	$oAux=new c_documento($this->con);
  	$oAux->cad2id($id);

    $sql=<<<va
 	delete from documento
	where sto_id='$oAux->sto_id' and ofi_id='$oAux->ofi_id'
	and stotip_id='$oAux->stotip_id'
va;
	$rs = &$this->con->Execute($sql);
	if($rs)
	  $res=$id;
	else
	{
	  $res="0";
	  $this->msg="No se puede eliminar dato!!!";
	}
	return($res);
  }
  
  function update($id)
  {
	$existe=$this->existe($id);
	if($existe!="0")
	{
	  $oAux=new c_documento($this->con);
	  $oAux->cad2id($id);
	  $sql=<<<va
	  UPDATE documento
	  set
	  tipcar_id=to_date('$this->tipcar_id','$this->oParametro->fechaCorta'),
	  cli_codigo=to_date('$this->cli_codigo','$this->oParametro->fechaCorta'),
	  repven_id='$this->repven_id',
	  doc_origen='$this->doc_origen',
	  doc_destino='$this->doc_destino',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$this->oParametro->fechaLarga'),
	  WHERE sto_id='$oAux->sto_id' and ofi_id='$oAux->ofi_id'
	  and stotip_id='$oAux->stotip_id'
va;
      $rs=&$this->con->Execute($sql);
      $res=$existe;
	}
	else
	{
	  $res=$id;
	  $this->msg="Imposible actualizar, dato no existe!!!";
	}
	return($res);
  }
  */

  function info($id)
  {
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
	$oAux->cad2id($id);
	
	$fechaCorta=$this->fechaCorta;
    $fechaLarga=$this->fechaLarga;

    $sql=<<<va
  	select
  	sto_id,ofi_id,stotip_id,sto_nro,
	tipcar_id,cli_codigo,nvl(repven_id,0),
	doc_origen,doc_destino,
  	to_char(doc_fecharec,'$fechaLarga'),doc_nropiezas,
  	doc_peso,doc_volumen,doc_valordeclarado,
  	doc_descripcion,doc_sobredocumento,doc_observacion,
  	doc_destnombre,doc_destciruc,doc_desttelf,
  	doc_subtotal,doc_total,
  	doc_costo,doc_seguro,doc_iva,doc_formapago,
	usu_audit,to_char(usu_faudit,'$fechaLarga'),
	nvl(doc_estado,'0'),nvl(doc_estado_usu,''),to_date(doc_estado_usuf,'$fechaLarga') 
  	from documento
  	where sto_id='$oAux->sto_id' and ofi_id='$oAux->ofi_id'
  	and stotip_id='$oAux->stotip_id'
  	and sto_nro='$oAux->sto_nro'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->sto_id="";
	  $this->ofi_id="";
	  $this->stotip_id="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else
	{
	  $res=$id;
	  
	  $this->sto_id=$rs->fields[0];
	  $this->ofi_id=$rs->fields[1];
	  $this->stotip_id=$rs->fields[2];
	  $this->sto_nro=$rs->fields[3];
	  $this->tipcar_id=$rs->fields[4];
	  $this->cli_codigo=$rs->fields[5];
	  $this->repven_id=$rs->fields[6];
	  $this->doc_origen=$rs->fields[7];
	  $this->doc_destino=$rs->fields[8];
	  $this->doc_fecharec=$rs->fields[9];
	  $this->doc_nropiezas=$rs->fields[10];
	  $this->doc_peso=$rs->fields[11];
	  $this->doc_volumen=$rs->fields[12];
	  $this->doc_valordeclarado=$rs->fields[13];
	  $this->doc_descripcion=$rs->fields[14];
	  $this->doc_sobredocumento=$rs->fields[15];
	  $this->doc_observacion=$rs->fields[16];
	  $this->doc_destnombre=$rs->fields[17];
	  $this->doc_destciruc=$rs->fields[18];
	  $this->doc_desttelf=$rs->fields[19];
	  $this->doc_subtotal=$rs->fields[20];
	  $this->doc_total=$rs->fields[21];
	  $this->doc_costo=$rs->fields[22];
	  $this->doc_seguro=$rs->fields[23];
	  $this->doc_iva=$rs->fields[24];
	  $this->doc_formapago=$rs->fields[25];
	  $this->usu_faudit=$rs->fields[26];
	  $this->usu_faudit=$rs->fields[27];
	  
	  $this->doc_estado=$rs->fields[28];
	  $this->doc_estado_usu=$rs->fields[29];
	  $this->doc_estado_usuf=$rs->fields[30];
	}
	return($res);
  }

  function sqlSelect()
  {
    $cadId=$this->armarSqlId("","","","");
    $cad="select $cadId a1,$cadId a2 from documento order by a1";
    return($cad);
  }
  
  //  stotip#1ofi#3stoId#4stoNro#4:nroPiezas#2
  function armarCodigoCB()
  {
  	$stotip=$this->completarCeros($this->stotip_id,$this->lenStotip);
  	$ofi=$this->completarCeros($this->ofi_id,$this->lenOfi);
  	$stoId=$this->completarCeros($this->sto_id,$this->lenSto);
  	$stoNro=$this->completarCeros($this->sto_nro,$this->lenNro);
  	$nroPiezas=$this->completarCeros($this->doc_nropiezas,$this->lenPaquete);
  	
  	$cad=$stotip.$ofi.$stoId.$stoNro.$this->separador.$nroPiezas;
  	return($cad);
  }
  
  function completarCeros($cad,$cuantos)
  {
  	$longCad=strlen($cad);
  	if($longCad>$cuantos)
  	{
  	  $cad=substr($cad,0,$cuantos);
  	  $longCad=$cuantos;
  	}
  	$cero="0";
  	$cadCero="";
  	for($i=0;$i<($cuantos-$longCad);$i++)
  	{
  	  $cadCero.=$cero;	
  	}
  	$res=$cadCero.$cad;
  	return($res);
  }
  
  function addDetalleImprimir()
  {
  	//crear detalleDocumento
  	$oDD=new c_detalledocumento($this->con,$this->terminal);
  	
  	list($codigo,$cuantos)=explode($this->separador,$this->armarCodigoCB());
  	
  	for($i=1;$i<=$this->doc_nropiezas;$i++)
  	{
  	  $oDD->stotip_id=$this->stotip_id;
  	  $oDD->ofi_id=$this->ofi_id;
  	  $oDD->sto_id=$this->sto_id;
  	  $oDD->sto_nro=$this->sto_nro;
  	  $totalPaq=$cuantos;
  	  $nroPaq=$this->completarCeros($i,$this->lenPaquete);
  	  $oDD->detdoc_ref=$codigo.$nroPaq.$totalPaq;
  	  
  	  $oDD->add();
  	}
  	
  	//impresion
  	$oTer=new c_terminal($this->con);
  	$cadFile="P@".$codigo."@".$cuantos."@".$this->empresa."@".$this->doc_origen."-".$this->doc_destino;
  
  	$oTer->imprimir($this->terminal,"CBP",$cadFile);
  }
  
  function addStockHistorialactStockxOficina($id)
  {
  	$oStoHis=new c_stock_historial($this->con,$this->usu_audit);
  	$oStoHis->CrearHistorial($id,$this->doc_fecharec,$oStoHis->ok());
  }
  
  function updRepventas($repven,$doc)
  {
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	$existe=$oAux->info($doc);
  	if($existe!="0")
  	{
  	  $sql=<<<va
  	  update documento
  	  set repven_id='$repven'
  	  where 
  	  ofi_id='$oAux->ofi_id'
  	  and stotip_id='$oAux->stotip_id'
  	  and sto_id='$oAux->sto_id'
  	  and sto_nro='$oAux->sto_nro'
va;
	  $rs=&$this->con->Execute($sql);
	  $res=$existe;
  	}
  	else 
  	{
  	  $res="0";	
  	}
  	return($res);
  }
  
  function estado($esta)
  {
  	if($esta=="E")
  	  $res="Entregada";
  	if($esta=="A")
  	  $res="Anulada";
  	if($esta=="")
  	  $res="Enviada";
  	return($res);    
  }
  
  /**
   * Códigos de Barra del documento
   *
   * @param doc_id $id
   * @return unknown
   */
  function CargaDocumento($id,$sep="<br>")
  {
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	$res=$oAux->info($id);
  	if($res!="0")
  	{
  	  $sql=<<<va
  	  select detdoc_ref
  	  from detalledocumento
  	  where ofi_id='$oAux->ofi_id'
  	  and stotip_id='$oAux->stotip_id'
  	  and sto_id='$oAux->sto_id'
  	  and sto_nro='$oAux->sto_nro'  
va;
	  $rs=&$this->con->Execute($sql);
	  $cad="";
	  while(!$rs->EOF)
	  {
	  	$cad.=$rs->fields[0].$sep;
	  	$rs->MoveNext();
	  }
  	}
  	else 
  	  $cad="No existen códigos de barra del documento";
  	  
  	return($cad);  
  }
  
  function estaCompleto($id)
  {
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	$res=$oAux->info($id);
  	if($res!="0")
  	{
  	  $sql=<<<va
  	  select detdoc_ref
  	  from detalledocumento
  	  where ofi_id='$oAux->ofi_id'
  	  and stotip_id='$oAux->stotip_id'
  	  and sto_id='$oAux->sto_id'
  	  and sto_nro='$oAux->sto_nro'  
va;
	  $rs=&$this->con->Execute($sql);
	  $cont=0;
	  $ocurrencia=0;
	  while(!$rs->EOF)
	  {
	    $detdocId=$rs->fields[0];
	  
	    $sql=<<<va
	    select detdoc_ref 
  	    from des_paquete
  	    where detdoc_ref='$detdocId'  
va;
	    $rs1=&$this->con->Execute($sql);
	    if($rs1->EOF)
	      $despaqDetdocId="0";
	    else  
	      $despaqDetdocId=$rs1->fields[0];
	    
	    if($despaqDetdocId!="0")
	    {
	      $ocurrencia++;	
	    }
	    
	    $rs->MoveNext();
	    $cont++;
	  }
	  
	  if($ocurrencia==$cont)
	    $retorna=1; 
	  else 
	    $retorna=0;  
  	}
  	else 
  	{
  	  $retorna=0;	
  	}
  	return($retorna);
  }
  
  function despachar($id)
  {
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	$oAux->info($id);
  	$sql=<<<va
  	update documento
  	set doc_estado='E',doc_estado_usu='$this->usu_audit',
    doc_estado_usuf=to_date('$this->usu_faudit','$this->fechaLarga')
    where 
    ofi_id='$oAux->ofi_id'
    and stotip_id='$oAux->stotip_id'
    and sto_id='$oAux->sto_id'
    and sto_nro='$oAux->sto_nro' 
va;
	$rs=&$this->con->Execute($sql);
	return($id);
  }
  
  function anular($id,$motivo)
  {
  	$oSH=new c_stock_historial($this->con,$this->usu_audit);
  	
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	$oAux->info($id);
  	
  	$oAux->doc_estado=$oSH->anulado();
  	
  	$sql=<<<va
  	update documento
  	set doc_estado='$oAux->doc_estado',doc_estado_usu='$this->usu_audit',
    doc_estado_usuf=to_date('$this->usu_faudit','$this->fechaLarga')
    where 
    ofi_id='$oAux->ofi_id'
    and stotip_id='$oAux->stotip_id'
    and sto_id='$oAux->sto_id'
    and sto_nro='$oAux->sto_nro' 
va;
	$rs=&$this->con->Execute($sql);
	
	$oSH->anular($id,$motivo);
	
	return($id);
  	
  }
  
  function estadoEnvio($id)
  {
  	$cadId=$this->armarSqlId("dd.","dd.","dd.","dd.");
  	
  	$sql=<<<sql
  	  select distinct me.usu_audit,to_char(me.usu_faudit,'$this->fechaLarga')
  	  from manembxbultoreal me, detalledocumento dd, detalle_bulto db 
  	  where 
  	  me.bul_ref=db.bul_ref 
  	  and '$id'=$cadId 
  	  and db.detdoc_ref=dd.detdoc_ref
sql;
  	if($this->debug)
  	  echo "<hr>$sql<hr>";
    $rs=&$this->con->Execute($sql);
    $cont=0;
    while(!$rs->EOF)
    {
      $dato[$cont]["usuario"]=$rs->fields[0];
      $dato[$cont]["fecha"]=$rs->fields[1];
      $rs->MoveNext();
      $cont++;
    }
    return($dato);
  }
  
  function estadoRecepcion($id)
  {
  	$cadId=$this->armarSqlId("dd.","dd.","dd.","dd.");
  	
  	$sql=<<<sql
  	  select distinct me.usu_audit,to_char(me.usu_faudit,'$this->fechaLarga')
  	  from mandesxbulto me, detalledocumento dd, detalle_bulto db 
  	  where 
  	  me.bul_ref=db.bul_ref 
  	  and '$id'=$cadId 
  	  and db.detdoc_ref=dd.detdoc_ref
sql;
  	if($this->debug)
  	  echo "<hr>$sql<hr>";
    $rs=&$this->con->Execute($sql);
    $cont=0;
    while(!$rs->EOF)
    {
      $dato[$cont]["usuario"]=$rs->fields[0];
      $dato[$cont]["fecha"]=$rs->fields[1];
      $rs->MoveNext();
      $cont++;
    }
    return($dato);
  }
  
  function historial($id)
  {
  	$oAux=new c_documento($this->con,$this->usu_audit,$this->terminal);
  	$resInfo=$oAux->info($id);
  	
  	$i=0;
  	//$dato=array();
  	if($this->debug)
  	  echo "<hr>$id<hr>";
  	if($resInfo=="0") //no existe
  	{
  	  $dato[$i]["estado"]="Documento no existe";
  	  $dato[$i]["usuario"]="";
  	  $dato[$i]["fecha"]="";
  	  $i++;
  	}
  	else 
  	{
  	  $estado=$oAux->doc_estado;
  	  if($this->debug)
  	    echo "<hr>$estado<hr>";
  	  $flagAnulacion=0;  
  	  //creación
  	  //if($estado=="0")
  	  {
  	    $dato[$i]["estado"]="Creación";
  	    $dato[$i]["usuario"]=$oAux->usu_audit;
  	    $dato[$i]["fecha"]=$oAux->usu_faudit;
  	    
  	    if((strlen(trim($dato[$i]["usuario"]))>0)&&(strlen($dato[$i]["fecha"])>0))
  	      $i++;
  	  }
  	  //anulación
  	  if($estado=="A")
  	  {
  	    $dato[$i]["estado"]="Anulación";
  	    $dato[$i]["usuario"]=$oAux->doc_estado_usu;
  	    $dato[$i]["fecha"]=$oAux->doc_estado_usuf;
  	    
  	    if((strlen($dato[$i]["usuario"])>0)&&(strlen($dato[$i]["fecha"])>0))
  	    {
  	      $i++;
  	      $flagAnulacion=1;
  	    }
  	  }
  	  //envío
  	  if(!$flagAnulacion)
  	  {
  	    $resEnvio=$oAux->estadoEnvio($id);
  	    if(count($resEnvio)==0)
  	    {
  	      $flagEnvio=0;
  		}
  		else 
  		{
  		  $flagEnvio=1;	
		}
  	    for($m=0;$m<count($resEnvio);$m++)
  	    {
  	      $dato[$i]["estado"]="Envío";
  	      $dato[$i]["usuario"]=$resEnvio[$m]["usuario"];
  	      $dato[$i]["fecha"]=$resEnvio[$m]["fecha"];
  	      $i++;
  	    }  
  	    
  	    /*if((strlen($dato[$i]["usuario"])>0)&&(strlen($dato[$i]["fecha"])>0))
  	      $i++;*/
  	  }
  	  //recepción
  	  if((!$flagAnulacion)&&($flagEnvio))
  	  {
  	    $resRecepcion=$oAux->estadoRecepcion($id);
  	    if(count($resRecepcion)==0)
  	    {
  	      $flagRecepcion=0;
  	    }
  	    else 
  	    {
  	      $flagRecepcion=1;
  	    }
  	  	for($m=0;$m<count($resRecepcion);$m++)
  	  	{
  	  	  $dato[$i]["estado"]="Recepción en destino";
  	      $dato[$i]["usuario"]=$resRecepcion[$m]["usuario"];
  	      $dato[$i]["fecha"]=$resRecepcion[$m]["fecha"];
  	      $i++;
  	  	}
  	    
  	    /*if((strlen($dato[$i]["usuario"])>0)&&(strlen($dato[$i]["fecha"])>0))
  	      $i++;*/
  	  }
  	  //entrega
  	  //if((!$flagAnulacion)&&($flagRecepcion))
  	  if((!$flagAnulacion)&&($flagRecepcion)&&($estado=="E"))
  	  {
  	    $dato[$i]["estado"]="Entrega";
  	    $dato[$i]["usuario"]=$oAux->doc_estado_usu;
  	    $dato[$i]["fecha"]=$oAux->doc_estado_usuf;
  	    
  	    /*if((strlen(trim($dato[$i]["usuario"]))>0)&&(strlen($dato[$i]["fecha"])>0))
  	      $i++;
  	    else 
  	    {  
  	      unset($dato[$i]);
  	    }*/
  	  }
  	}
  	
  	if($this->debug)
  	{
  	  for($m=0;$m<count($dato);$m++)
  	  {
  	    echo "<hr>";	
  	    echo "estado: ".$dato[$m]["estado"]." <br> ";	
  	    echo "usuario: ".$dato[$m]["usuario"]." <br> ";	
  	    echo "fecha: ".$dato[$m]["fecha"]." <br> ";	
  	    echo "<hr>";	
  	  }
  	}
  	
  	return($dato);
  }

  /*function stockActual($ofi,$stotip)
  {
  	$oAux=new c_documento($this->con,$this->usu_audit);
  	$sql=<<<va
  	select nvl(max(sto_id),0) 
  	from documento
  	where 
  	ofi_id='$ofi'
  	and stotip_id='$stotip'
va;
	$rs=&$this->con->Execute($sql);
	$stoId=$rs->fields[0];
	return($oAux->id2cad($ofi,$stotip,$stoId));
  }
  
  function recuperarNroDoc($ofi,$stotip)
  {
    $oAux=new c_documento($this->con,$this->usu_audit);
    $resInfo=$oAux->info($oAux->stockActual($ofi,$stotip));
    if($resInfo=="0")
    {
      $res="0";
      $this->msg="Error.  Cargar stock!!!";
    }
    else
    {
      $nroActual=$oAux->doc_destino;
      if($nroActual>$oAux->doc_origen)
      {
        $res="0";
        $this->msg="Error.  Stock lleno.  Cargue nuevo stock!!!";
      }
      else 
        $res=$nroActual;  
    }
    return($res);
  }*/
}
?>