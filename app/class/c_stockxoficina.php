<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_oficina.php");
include_once("class/c_stock_tipo.php");
include_once("class/c_stock_historial.php");
include_once("class/c_parametro.php");
class c_stockxoficina
{
  var $sto_id;
  var $ofi_id;
  var $stotip_id;
  var $sto_fecha;
  var $sto_fechaexp;
  var $stoofi_ini;
  var $stoofi_fin;
  var $stoofi_actual;
  var $usu_audit;
  var $usu_faudit;

  //objetos
  var $oOficina;
  var $oStockTipo;
  var $oParametro;

  var $msg;
  var $separador;
  /*
  var $fechaLarga;
  var $fechaCorta;
  */
  
  var $con;

  function c_stockxoficina($conDb,$usuario)
  {
	  //echo"<hr>$usuario<hr>";
  	  $this->sto_id="";
	  $this->ofi_id="";
	  $this->stotip_id="";
	  $this->sto_fecha="";
	  $this->sto_fechaexp="";
	  $this->stoofi_ini=0;
	  $this->stoofi_fin=0;
	  $this->stoofi_actual=0;

	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");

	  $this->con=&$conDb;

	  $this->oOficina=new c_oficina($this->con);
	  $this->oStockTipo=new c_stock_tipo($this->con);
	  $this->oParametro=new c_parametro($this->con);
	  $this->oParametro->info();

	  $this->msg="";
	  $this->separador=":";

	  /*
	  $this->fechaLarga=$this->oParametro->fechaLarga;
	  $this->fechaCorta=$this->oParametro->fechaCorta;
	  */
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
  	  $ncampos=7;
	  if($ncampos==count($dato))
	  {
        $this->ofi_id=$dato[0];
	    $this->stotip_id=$dato[1];
	    $this->sto_fecha=$dato[2];
	    $this->sto_fechaexp=$dato[3];
	    $this->stoofi_ini=$dato[4];
	    $this->stoofi_fin=$dato[5];
	    $this->stoofi_actual=$dato[6];
	    /*
	    $this->usu_audit=$dato[3];
	    $this->usu_faudit=$dato[4];
	    */
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=7;
	  if($ncampos==count($dato))
	  {
	    $this->ofi_id=$dato[0];
	    $this->stotip_id=$dato[1];
	    $this->sto_fecha=$dato[2];
	    $this->sto_fechaexp=$dato[3];
	    $this->stoofi_ini=$dato[4];
	    $this->stoofi_fin=$dato[5];
	    $this->stoofi_actual=$dato[6];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }

  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$oficina="")
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
  	
  	if($oficina=="")
  	{
	  $sql=<<<va
  	  select $cadId,
	  o.ofi_nombre,t.stotip_nombre,
	  to_char(s.sto_fecha,'$fechaCorta') fecha,to_char(s.sto_fechaexp,'$fechaCorta') fechaexp,
	  s.stoofi_ini,s.stoofi_fin,s.stoofi_actual,
	  $cadId 
	  from stockxoficina s, oficina o, stock_tipo t
	  where o.ofi_id=s.ofi_id
	  and t.stotip_id=s.stotip_id
	  order by o.ofi_nombre,t.stotip_nombre,fecha desc
va;
  	}
  	else 
  	{
  	  $sql=<<<va
  	  select $cadId,
	  o.ofi_nombre,t.stotip_nombre,
	  to_char(s.sto_fecha,'$fechaCorta') fecha,to_char(s.sto_fechaexp,'$fechaCorta') fechaexp,
	  s.stoofi_ini,s.stoofi_fin,s.stoofi_actual,
	  $cadId
	  from stockxoficina s, oficina o, stock_tipo t
	  where o.ofi_id=s.ofi_id
	  and s.ofi_id='$oficina'
	  and t.stotip_id=s.stotip_id
	  order by o.ofi_nombre,t.stotip_nombre,fecha desc
va;
  	}
  	//echo "<hr>$sql<hr>";
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

  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$oficina)
  {
	$campo=array(
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oOficina->sqlSelect($oficina),"valor"=>""),
				array("etiqueta"=>"* Tipo Doc.","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fecha Expiración","nombre"=>"tFechaExp","tipo_campo"=>"date","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Desde","nombre"=>"tIni","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Hasta","nombre"=>"tFin","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Actual","nombre"=>"tActual","tipo_campo"=>"text","sql"=>"","valor"=>"")

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

  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id,$oficina="")
  {
    $oAux=new c_stockxoficina($this->con,$this->usu_audit);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oOficina->sqlSelect($oficina),"valor"=>$oAux->ofi_id),
				array("etiqueta"=>"* Tipo Doc.","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect(),"valor"=>$oAux->stotip_id),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->sto_fecha),
				array("etiqueta"=>"* Fecha Expiración","nombre"=>"tFechaExp","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->sto_fechaexp),
				array("etiqueta"=>"* Desde","nombre"=>"tIni","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->stoofi_ini),
				array("etiqueta"=>"* Hasta","nombre"=>"tFin","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->stoofi_fin),
				array("etiqueta"=>"* Actual","nombre"=>"tActual","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->stoofi_actual)

				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
					array("nombre"=>"principal","valor"=>$principal)
				);

	$cadForm=build_updCad($this->con,'false',$titulo,'images/360/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden,$id);
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

  function mostrar_dato()
  {
	echo "<hr>Class c_stockxoficina(conDb)<br>";
  	echo "sto_id:".$this->sto_id."<br>";
	echo "ofi_id:".$this->ofi_id."<br>";
	echo "stotip_id:".$this->stotip_id."<br>";
	echo "sto_fecha:".$this->sto_fecha."<br>";
	echo "sto_fechaexp:".$this->sto_fechaexp."<br>";
	echo "stoofi_ini:".$this->stoofi_ini."<br>";
	echo "stoofi_fin:".$this->stoofi_fin."<br>";
	echo "stoofi_actual:".$this->stoofi_actual."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "<hr>";
  }

  function id2cad($ofi,$stotip,$stoId)
  {
    $cad=$ofi.$this->separador.$stotip.$this->separador.$stoId;
    return($cad);
  }

  function cad2id($cad)
  {
    list($this->ofi_id,$this->stotip_id,$this->sto_id)=explode($this->separador,$cad);
    return($cad);
  }

  function armarSqlId($prefijo1,$prefijo2,$prefijo3)
  {
    $cad=$prefijo1."ofi_id||'".$this->separador."'||".$prefijo2."stotip_id||'".$this->separador."'||".$prefijo3."sto_id";
    return($cad);
  }

  function existe($vid)
  {
  	$oAux=new c_stockxoficina($this->con,$this->usu_audit);
  	$oAux->cad2id($vid);
  	$cadId=$this->armarSqlId("","","");
    $sql=<<<va
    select $cadId
    from stockxoficina
    where ofi_id='$oAux->ofi_id'
    and stotip_id='$oAux->stotip_id'
    and sto_id='$oAux->sto_id'
va;
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else
	  $res=$rs->fields[0];
	return($res);
  }

  function nuevoStoId($ofi,$stotip)
  {
    $sql=<<<va
    select nvl(max(to_number(sto_id)),0)
    from stockxoficina
    where
    ofi_id='$ofi'
    and stotip_id='$stotip'
va;
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }

  function add()
  {
  	$oAux=new c_stockxoficina($this->con,$this->usu_audit);
  	$this->sto_id=$oAux->nuevoStoId($this->ofi_id,$this->stotip_id);
  	$cadId=$oAux->id2cad($this->ofi_id,$this->stotip_id,$this->sto_id);
    $existe=$this->existe($cadId);
	if($existe=="0")
	{
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;	
		
	  $sql=<<<va
	  insert into stockxoficina
	  (
	    sto_id,ofi_id,stotip_id,
	    sto_fecha,sto_fechaexp,
	    stoofi_ini,stoofi_fin,stoofi_actual,
	    usu_audit,usu_faudit
	  )
	  values
	  (
	    '$this->sto_id','$this->ofi_id','$this->stotip_id',
	    to_date('$this->sto_fecha','$fechaCorta'),to_date('$this->sto_fechaexp','$fechaCorta'),
	    '$this->stoofi_ini','$this->stoofi_fin','$this->stoofi_actual',
	    '$this->usu_audit',to_date('$this->usu_faudit','$fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->ofi_id,$this->stotip_id,$this->sto_id);
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
 	$oAux=new c_stockxoficina($this->con,$this->usu_audit);
  	$oAux->cad2id($id);

    $sql=<<<va
 	delete from stockxoficina
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
	  $oAux=new c_stockxoficina($this->con,$this->usu_audit);
	  $oAux->cad2id($id);
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE stockxoficina
	  set
	  sto_fecha=to_date('$this->sto_fecha','$fechaCorta'),
	  sto_fechaexp=to_date('$this->sto_fechaexp','$fechaCorta'),
	  stoofi_ini='$this->stoofi_ini',
	  stoofi_fin='$this->stoofi_fin',
	  stoofi_actual='$this->stoofi_actual',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga') 
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

  function info($id)
  {
  	$oAux=new c_stockxoficina($this->con,$this->usu_audit);
	$oAux->cad2id($id);
	
    $fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;
	
	$sql=<<<va
  	select
  	sto_id,ofi_id,stotip_id,usu_audit,
	to_char(usu_faudit,'$fechaLarga'),
	to_char(sto_fecha,'$fechaCorta'),
	to_char(sto_fechaexp,'$fechaCorta'),
	stoofi_ini,stoofi_fin,stoofi_actual 
  	from stockxoficina
  	where sto_id='$oAux->sto_id' and ofi_id='$oAux->ofi_id'
  	and stotip_id='$oAux->stotip_id'
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
	  $this->usu_audit=$rs->fields[3];
	  $this->usu_faudit=$rs->fields[4];
	  
	  $this->sto_fecha=$rs->fields[5];
	  $this->sto_fechaexp=$rs->fields[6];
	  $this->stoofi_ini=$rs->fields[7];
	  $this->stoofi_fin=$rs->fields[8];
	  $this->stoofi_actual=$rs->fields[9];
	}
	return($res);
  }

  function sqlSelect()
  {
    $cadId=$this->armarSqlId("","");
    $cad="select $cadId a1,$cadId a2 from stockxoficina order by a1";
    return($cad);
  }

  function stockActual($ofi,$stotip)
  {
  	$oAux=new c_stockxoficina($this->con,$this->usu_audit);
  	$sql=<<<va
  	select nvl(max(sto_id),0) 
  	from stockxoficina
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
    $oAux=new c_stockxoficina($this->con,$this->usu_audit);
    $resInfo=$oAux->info($oAux->stockActual($ofi,$stotip));
    if($resInfo=="0")
    {
      $res="0";
      $this->msg="Error.  Cargar stock!!!";
    }
    else
    {
      $nroActual=$oAux->stoofi_actual;
      if($nroActual>$oAux->stoofi_fin)
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
    $oAux=new c_stockxoficina($this->con,$this->usu_audit);
    $resInfo=$oAux->info($oAux->stockActual($ofi,$stotip));
    if($resInfo=="0")
    {
      $res="0";
      $this->msg="Error";
    }
    else
    {
      $nroActual=$oAux->sto_id;
      $res=$nroActual;  
    }
    return($res);
  }
  
  function recuperarIdDocCompleto($ofi,$stotip)
  {
  	$ostoHis=new c_stock_historial($this->con,$this->usu_audit);
  	$oAux=new c_stockxoficina($this->con,$this->usu_audit);
    $resInfo=$oAux->info($oAux->stockActual($ofi,$stotip));
    if($resInfo=="0")
    {
      $res="0";
      $this->msg="Error";	
    }
    else 
    {
      $res=$ostoHis->id2cad($oAux->ofi_id,$oAux->stotip_id,$oAux->sto_id,$oAux->stoofi_actual); 
    }
    return($res);
  }
  
  function actualizarActual($ofi_id,$stotip_id,$sto_id,$sto_nro)
  {
  	
  	$oAux=new c_stockxoficina($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($ofi_id,$stotip_id,$sto_id);
  	$oAux->info($cadId);
  	
  	if($sto_nro>=$oAux->stoofi_fin)
  	{
  	  $res="0";
  	  $this->msg="Error.  Stock no disponible!!!";
  	}
  	else 
  	{
  	  $nro=$sto_nro+1;
  	
  	  $sql=<<<va
  	  update stockxoficina
  	  set stoofi_actual='$nro'
  	  where 
  	  sto_id='$sto_id'
  	  and ofi_id='$ofi_id'
  	  and stotip_id='$stotip_id'
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
  }
}
?>