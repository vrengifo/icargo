<?php
/**
 * Librería contenedora de funciones para el html
 *
 */
include_once("adodb/tohtml.inc.php");
/**
 * Clase estación
 *
 */
include_once("class/c_estacion.php");
/**
 * Clase Parámetro
 *
 */
include_once("class/c_parametro.php");
/**
 * Clase Terminal
 *
 */
include_once("class/c_terminal.php");
/**
 * Clase usada para el manejo de bulto
 *
 */
class c_bulto
{
  /**
   * Campo bul_id (identificador)
   *
   * @var int
   */
  var $bul_id;
  /**
   * Campo bul_fecha
   *
   * @var date
   */
  var $bul_fecha;
  /**
   * Campo bul_origen
   *
   * @var string
   */
  var $bul_origen;
  /**
   * Campo bul_destino
   *
   * @var string
   */
  var $bul_destino;
  var $usu_audit;
  var $usu_faudit;
  /**
   * Campo bul_ref, valor de código de barras generado para el bulto
   *
   * @var string
   */
  var $bul_ref;//id

  //objetos

  var $msg;
  var $separador;
  var $iniNro;
  /**
   * Enter description here...
   *
   * @var unknown_type
   */
  var $fechaLarga;
  /**
   * formato de fecha
   *
   * @var string
   */
  var $fechaCorta;

  var $terminal;
  
  var $tamOrigen;
  var $tamDestino;
  /**
   * Formato de fecha para concatenar al armar el identificador
   *
   * @var string
   */
  var $fechaFormatoId;
  var $maxNro;
  
  /**
   * Conexión a la base de datos
   *
   * @var mixed
   */
  var $con;

  function c_bulto($conDb,$usuario)
  {
	  $this->bul_id="";
	  $this->bul_fecha="";
	  $this->bul_origen="";
	  $this->bul_destino="";
	  $this->iniNro=1;//valor de inicio pa cada día

	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");

	  $this->con=&$conDb;

	  $oParametro=new c_parametro($this->con);
	  $oParametro->info();
	  
	  $this->fechaCorta=$oParametro->fechaCorta;
	  $this->fechaLarga=$oParametro->fechaLarga;
	  $this->fechaFormatoId="YYMMDD";//050219
	  
	  $this->tamOrigen=3;
	  $this->tamDestino=3;
	  $this->maxNro=4;

	  $this->msg="";
	  $this->separador=":";
	  
	  $this->terminal="";

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
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
        $this->bul_fecha=$dato[0];
	    $this->bul_origen=$dato[1];
	    $this->bul_destino=$dato[2];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
	    $this->bul_fecha=$dato[0];
	    $this->bul_origen=$dato[1];
	    $this->bul_destino=$dato[2];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }



  function mostrar_dato()
  {
	echo "<hr>Class c_bulto(conDb)<br>";
  	echo "bul_id:".$this->bul_id."<br>";
	echo "bul_fecha:".$this->bul_fecha."<br>";
	echo "bul_origen:".$this->bul_origen."<br>";
	echo "bul_destino:".$this->bul_destino."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "iniNro:".$this->iniNro."<br>";
	echo "<hr>";
  }


  
  function aumentarCeros($valor,$max)
  {
  	$tamValor=strlen($valor);
  	$cad="";
  	$cero="0";
  	for($i=1;$i<($max-$tamValor);$i++)
  	{
  	  $cad.=$cero;
  	}
  	$cad.=$cero.$valor;
  	return($cad);
  }
  
  function generarId($nro,$fecha,$origen,$destino)
  {
  	$vOrigen=substr($origen,0,$this->tamOrigen);
  	$vDestino=substr($destino,0,$this->tamDestino);
  	$vFecha=$this->convertirFecha($fecha,$this->fechaCorta,$this->fechaFormatoId);
  	$vNro=$this->aumentarCeros($nro,$this->maxNro);
  	$res=$vOrigen.$vDestino.$vFecha.$vNro;
  	return($res);
  }
  
  function existe($vid)
  {
  	$oAux=new c_bulto($this->con, $this->usu_audit);
    $sql=<<<va
    select bul_ref
    from bulto
    where bul_ref='$vid'
va;
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else
	  $res=$rs->fields[0];
	return($res);
  }

  function nuevoNro($origen,$destino,$fecha)
  {
    $sql=<<<va
    select nvl(max(to_number(bul_id)),0)
    from bulto
    where
    bul_fecha=to_date('$fecha','$this->fechaCorta')
    and bul_origen='$origen'
    and bul_destino='$destino'
va;
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }

  function convertirFecha($fecha,$formato,$nuevoFormato)
  {
  	$sql=<<<va
  	select to_char(to_date('$fecha','$formato'),'$nuevoFormato')
  	from dual 
va;
	$rs=$this->con->Execute($sql);
	return($rs->fields[0]);
  }
  
  function imprimirCBBulto($terminal,$codigo,$cuantos,$empresa,$origen,$destino)
  {
  	//impresion
  	$oTer=new c_terminal($this->con);
  	$cadFile="B@".$codigo."@".$cuantos."@".$empresa."@".$origen."-".$destino;
  	$oTer->imprimir($terminal,"CBB",$cadFile);
  }
  
  function add()
  {
  	$oAux=new c_bulto($this->con,$this->usu_audit);
  	do 
  	{
  	  $this->bul_id=$oAux->nuevoNro($this->bul_origen,$this->bul_destino,$this->bul_fecha);
  	  $this->bul_ref=$oAux->generarId($this->bul_id,$this->bul_fecha,$this->bul_origen,$this->bul_destino);
      $existe=$oAux->existe($this->bul_ref);
  	}while($existe!="0");
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into bulto
	  (
	    bul_ref,
	    bul_id,bul_fecha,bul_origen,
	    bul_destino,
	    usu_audit,usu_faudit
	  )
	  values
	  (
	    '$this->bul_ref',
	    '$this->bul_id',to_date('$this->bul_fecha','$this->fechaCorta'),'$this->bul_origen',
	    '$this->bul_destino',
	    '$this->usu_audit',to_date('$this->usu_faudit','$this->fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->bul_ref;
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

  function del($id)
  {
    $sql=<<<va
 	delete from bulto
	where bul_ref='$id'
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

  /*
  function update($id)
  {
	$existe=$this->existe($id);
	if($existe!="0")
	{
	  $oAux=new c_bulto($this->con);
	  $oAux->cad2id($id);
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE bulto
	  set
	  bul_destino=to_date('$this->bul_destino','$fechaCorta'),
	  borrar=to_date('$this->borrar','$fechaCorta'),
	  iniNro='$this->iniNro',
	  borrar='$this->borrar',
	  borrar='$this->borrar',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga'),
	  WHERE bul_id='$oAux->bul_id' and bul_fecha='$oAux->bul_fecha'
	  and bul_origen='$oAux->bul_origen'
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
	$sql=<<<va
  	select
  	bul_id,to_char(bul_fecha,'$this->fechaCorta'),bul_origen,bul_destino,
  	usu_audit,to_char(usu_faudit,'$this->fechaLarga'),
  	bul_ref 
  	from bulto
  	where bul_ref='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->bul_id="";
	  $this->bul_fecha="";
	  $this->bul_origen="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else
	{
	  $res=$id;
	  $this->bul_id=$rs->fields[0];
	  $this->bul_fecha=$rs->fields[1];
	  $this->bul_origen=$rs->fields[2];
	  $this->bul_destino=$rs->fields[3];
	  $this->usu_audit=$rs->fields[4];
	  $this->usu_faudit=$rs->fields[5];
	  $this->bul_ref=$rs->fields[6];
	}
	return($res);
  }

  function sqlSelect()
  {
    $cad="select bul_ref a1,bul_ref a2 from bulto order by a1";
    return($cad);
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
  	
  	$fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;
  	
  	$sql=<<<va
  	  select $cadId,
	  o.ofi_nombre,t.stotip_nombre,
	  to_char(s.bul_destino,'$fechaCorta') fecha,to_char(s.borrar,'$fechaCorta') fecha exp,
	  s.iniNro,s.borrar,s.borrar,
	  $cadId
	  from bulto s, oficina o, stock_tipo t
	  where o.bul_fecha=s.bul_fecha
	  and t.bul_origen=s.bul_origen
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

  function validaJs()
  {
  	//<script language="JavaScript" src="js/validation.js"></script>
  	$cad=<<<va

  	<script language="javascript">
	function valida()
	{
  	  define('seOficina', 'string', 'Oficina',1,7,document);
  	  define('seStockTipo', 'string', 'Tipo Doc.',1,7,document);
  	  define('tFecha', 'date', 'Fecha',10,10,document);
  	  define('tFechaExp', 'date', 'Fecha Expiracion',10,10,document);
  	  define('tIni', 'num', 'Desde',1,10,document);
  	  define('tFin', 'num', 'Hasta',1,10,document);
  	  define('tActual', 'num', 'Actual',1,10,document);
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
	// onClick="validate();return returnVal;"
  }

  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)
  {
    $oAux=new c_bulto($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oOficina->sqlSelect(),"valor"=>$oAux->bul_fecha),
				array("etiqueta"=>"* Tipo Doc.","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect(),"valor"=>$oAux->bul_origen),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->bul_destino),
				array("etiqueta"=>"* Fecha Expiración","nombre"=>"tFechaExp","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Desde","nombre"=>"tIni","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->iniNro),
				array("etiqueta"=>"* Hasta","nombre"=>"tFin","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Actual","nombre"=>"tActual","tipo_campo"=>"tet","sql"=>"","valor"=>$oAux->borrar)

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
    
    /*
  //formato fecha yymmdd
  function id2cad($nro,$fecha,$origen,$destino)
  {
    $cad=$origen.$this->separador.$destino.$this->separador.$fecha.$this->separador.$nro;
    return($cad);
  }

  function cad2id($cad)
  {
    list($this->bul_origen,$this->bul_destino,$this->bul_fecha,$this->bul_id)=explode($this->separador,$cad);
    return($cad);
  }

  function armarSqlId($prefijo1,$prefijo2,$prefijo3,$prefijo4)
  {
    $cad=$prefijo1."bul_origen||'".$this->separador."'||".$prefijo2."bul_destino||'".$this->separador."'||to_char(".$prefijo3."bul_fecha,'$this->fechaFormatoId')||'".$this->separador."'||".$prefijo4."bul_id";
    return($cad);
  }
  */

    /*function stockActual($ofi,$stotip)
  {
  	$oAux=new c_bulto($this->con,$this->usu_audit);
  	$sql=<<<va
  	select nvl(max(bul_id),0) 
  	from bulto
  	where 
  	bul_fecha='$ofi'
  	and bul_origen='$stotip'
va;
	$rs=&$this->con->Execute($sql);
	$stoId=$rs->fields[0];
	return($oAux->id2cad($ofi,$stotip,$stoId));
  }
  
  function recuperarNroDoc($ofi,$stotip)
  {
    $oAux=new c_bulto($this->con,$this->usu_audit);
    $resInfo=$oAux->info($oAux->stockActual($ofi,$stotip));
    if($resInfo=="0")
    {
      $res="0";
      $this->msg="Error.  Cargar stock!!!";
    }
    else
    {
      $nroActual=$oAux->borrar;
      if($nroActual>$oAux->borrar)
      {
        $res="0";
        $this->msg="Error.  Stock lleno.  Cargue nuevo stock!!!";
      }
      else 
        $res=$nroActual;  
    }
    return($res);
  }
  
  function recuperarStoId($ofi,$stotip)
  {
    $oAux=new c_bulto($this->con,$this->usu_audit);
    $resInfo=$oAux->info($oAux->stockActual($ofi,$stotip));
    if($resInfo=="0")
    {
      $res="0";
      $this->msg="Error";
    }
    else
    {
      $nroActual=$oAux->bul_id;
      $res=$nroActual;  
    }
    return($res);
  }
  
  function recuperarIdDocCompleto($ofi,$stotip)
  {
  	$ostoHis=new c_stock_historial($this->con,$this->usu_audit);
  	$oAux=new c_bulto($this->con,$this->usu_audit);
    $resInfo=$oAux->info($oAux->stockActual($ofi,$stotip));
    if($resInfo=="0")
    {
      $res="0";
      $this->msg="Error";	
    }
    else 
    {
      $res=$ostoHis->id2cad($oAux->bul_fecha,$oAux->bul_origen,$oAux->bul_id,$oAux->borrar); 
    }
    return($res);
  }
  
  function actualizarActual($bul_fecha,$bul_origen,$bul_id,$sto_nro)
  {
  	
  	$oAux=new c_bulto($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($bul_fecha,$bul_origen,$bul_id);
  	$oAux->info($cadId);
  	
  	if($sto_nro>=$oAux->borrar)
  	{
  	  $res="0";
  	  $this->msg="Error.  Stock no disponible!!!";
  	}
  	else 
  	{
  	  $nro=$sto_nro+1;
  	
  	  $sql=<<<va
  	  update bulto
  	  set borrar='$nro'
  	  where 
  	  bul_id='$bul_id'
  	  and bul_fecha='$bul_fecha'
  	  and bul_origen='$bul_origen'
va;
	  $rs=&$this->con->Execute($sql);
	  if($rs)
	    $res=$cadId;
	  else
	  { 
	    $res="0";
	    $this->msg="Error.  No se puedo actualizar dato!!!";
	  }
  	}
	return($res);    
  }*/   
}
?>