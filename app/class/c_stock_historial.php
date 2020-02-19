<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_stockxoficina.php");
include_once("class/c_parametro.php");
class c_stock_historial
{
  var $sto_id;
  var $ofi_id;
  var $stotip_id;
  var $sto_nro;
  var $sto_fecha;
  var $sto_observacion;
  
  var $usu_audit;
  var $usu_faudit;

  //objetos
  var $oStockxOficina;
  var $oParametro;

  var $msg;
  var $separador;
  var $estado;

  var $con;

  function c_stock_historial($conDb,$usuario)
  {
	  $this->sto_id="";
	  $this->ofi_id="";
	  $this->stotip_id="";
	  $this->sto_fecha="";
	  $this->sto_observacion=0;
	  $this->sto_nro=0;

	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");

	  $this->con=&$conDb;

	  $this->oStockxOficina=new c_stockxoficina($this->con,$this->usu_audit);
	  $this->oParametro=new c_parametro($this->con);
	  $this->oParametro->info();

	  $this->msg="";
	  $this->separador=":";

  }

  /**
   * Carga los datos a la clase desde un arreglo
   *
   * @param array $dato
   * @param string $iou ingresooupdate
   * @return boolean
   */
  /*
  function cargar_dato($dato,$iou="i")
  {
  	if($iou=="i")
  	{
  	  $ncampos=8;
	  if($ncampos==count($dato))
	  {
        $this->ofi_id=$dato[0];
	    $this->stotip_id=$dato[1];
	    $this->sto_id=$dato[2];
	    $this->sto_fecha=$dato[3];
	    $this->borrar=$dato[4];
	    $this->sto_observacion=$dato[5];
	    $this->borrar=$dato[6];
	    $this->sto_nro=$dato[7];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=1;
	  if($ncampos==count($dato))
	  {
	    $this->sto_fecha=$dato[0];
	    $this->borrar=$dato[1];
	    $this->sto_observacion=$dato[2];
	    $this->borrar=$dato[3];
	    $this->sto_nro=$dato[4];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }
  */

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
	  to_char(s.sto_fecha,'$this->oParametro->fechaCorta') fecha,to_char(s.borrar,'$this->oParametro->fechaCorta') fecha exp,
	  s.sto_observacion,s.borrar,s.sto_nro,
	  $cadId
	  from stock_historial s, oficina o, stock_tipo t
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
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oStockxOficina->sqlSelect(),"valor"=>""),
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
    $oAux=new c_stock_historial($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oStockxOficina->sqlSelect(),"valor"=>$oAux->ofi_id),
				array("etiqueta"=>"* Tipo Doc.","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect(),"valor"=>$oAux->stotip_id),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->sto_fecha),
				array("etiqueta"=>"* Fecha Expiración","nombre"=>"tFechaExp","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Desde","nombre"=>"tIni","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->sto_observacion),
				array("etiqueta"=>"* Hasta","nombre"=>"tFin","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Actual","nombre"=>"tActual","tipo_campo"=>"tet","sql"=>"","valor"=>$oAux->sto_nro)

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
	echo "<hr>Class c_stock_historial(conDb)<br>";
  	echo "sto_id:".$this->sto_id."<br>";
	echo "ofi_id:".$this->ofi_id."<br>";
	echo "stotip_id:".$this->stotip_id."<br>";
	echo "sto_fecha:".$this->sto_fecha."<br>";
	echo "sto_observacion:".$this->sto_observacion."<br>";
	echo "sto_nro:".$this->sto_nro."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "estado:".$this->estado."<br>";
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
  	$oAux=new c_stock_historial($this->con,$this->usu_audit);
  	$oAux->cad2id($vid);
  	$cadId=$this->armarSqlId("","","","");
    $sql=<<<va
    select $cadId
    from stock_historial
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
    from stock_historial
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
  	$oAux=new c_stock_historial($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($this->ofi_id,$this->stotip_id,$this->sto_id,$this->sto_nro);
    $existe=$this->existe($cadId);
    
    $fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;
    
	if($existe=="0")
	{
	  $this->ok();
	  $sql=<<<va
	  insert into stock_historial
	  (
	    sto_id,ofi_id,stotip_id,sto_nro,
	    sto_fecha,sto_observacion,
	    usu_audit,usu_faudit,estado
	  )
	  values
	  (
	    '$this->sto_id','$this->ofi_id','$this->stotip_id','$this->sto_nro',
	    to_date('$this->sto_fecha','$fechaLarga'),'$this->sto_observacion',
	    '$this->usu_audit',to_date('$this->usu_faudit','$fechaLarga'),'$this->estado'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->ofi_id,$this->stotip_id,$this->sto_id,$this->sto_nro);
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
  
  function anular($id,$motivo)
  {
  	$this->info($id);
  	$this->estado=$this->anulado();
  	$this->sto_observacion=$motivo;
  	$this->update($id);
  	return($id);
  }

  function del($id)
  {
 	$oAux=new c_stock_historial($this->con,$this->usu_audit);
  	$oAux->cad2id($id);

    $sql=<<<va
 	delete from stock_historial
	where sto_id='$oAux->sto_id' and ofi_id='$oAux->ofi_id'
	and stotip_id='$oAux->stotip_id'
	and sto_nro='$oAux->sto_nro'
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
	  $oAux=new c_stock_historial($this->con,$this->usu_audit);
	  $oAux->cad2id($id);
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  /*$sql=<<<va
	  UPDATE stock_historial
	  set
	  sto_fecha=to_date('$this->sto_fecha','$fechaLarga'),
	  sto_observacion='$this->sto_observacion',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga'),
	  WHERE sto_id='$oAux->sto_id' and ofi_id='$oAux->ofi_id'
	  and stotip_id='$oAux->stotip_id'
	  and sto_nro='$oAux->sto_nro'
va;*/
      
	  $sql=<<<va
	  UPDATE stock_historial
	  set
	  sto_observacion='$this->sto_observacion',
	  estado='$this->estado',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga'),
	  WHERE sto_id='$oAux->sto_id' and ofi_id='$oAux->ofi_id'
	  and stotip_id='$oAux->stotip_id'
	  and sto_nro='$oAux->sto_nro'
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
  	$oAux=new c_stock_historial($this->con,$this->usu_audit);
	$oAux->cad2id($id);
	
	$fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;

    $sql=<<<va
  	select
  	sto_id,ofi_id,stotip_id,usu_audit,
	to_char(usu_faudit,'$fechaLarga'),
	to_char(sto_fecha,'$fechaLarga'),
	sto_observacion,sto_nro 
  	from stock_historial
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
	  $this->usu_audit=$rs->fields[3];
	  $this->usu_faudit=$rs->fields[4];
	  
	  $this->sto_fecha=$rs->fields[5];
	  $this->sto_observacion=$rs->fields[6];
	  $this->sto_nro=$rs->fields[7];
	}
	return($res);
  }

  function sqlSelect()
  {
    $cadId=$this->armarSqlId("","");
    $cad="select $cadId a1,$cadId a2 from stock_historial order by a1";
    return($cad);
  }

  /*
  function recuperarNroDoc($ofi,$stotip)
  {
    $oAux=new c_stock_historial($this->con,$this->usu_audit);
    $nro=$oAux->nuevoStoId($ofi,$stotip);
    $cadId=$oAux->id2cad($ofi,$stotip,$nro);
    $resInfo=$oAux->info($cadId);
    if($resInfo=="0")
      $res="0";
    else
      $res=$nro;
    return($res);
  }
  */
  
  function CrearHistorial($id,$fecha,$observacion)
  {
  	//echo "<hr>CrearHistorial($id,$fecha,$observacion)<hr>";
  	$oAux=new c_stock_historial($this->con,$this->usu_audit);
  	$oAux->cad2id($id);
  	
  	$oAux->sto_fecha=$fecha;
  	if(strlen($observacion)==0)
  	  $observacion="0";
  	$oAux->sto_observacion=$observacion;
  	$resAdd=$oAux->add();
  	if($resAdd!="0")
  	{
  	  $oStockxOfi=new c_stockxoficina($this->con,$this->usu_audit);
  	  $oStockxOfi->actualizarActual($oAux->ofi_id,$oAux->stotip_id,$oAux->sto_id,$oAux->sto_nro);
  	}
  }
  
  function ok()
  {
  	$cad="O";
  	$this->estado=$cad;
  	return($cad);
  }
  
  function anulado()
  {
  	$cad="A";
  	$this->estado=$cad;
  	return($cad);
  }
}
?>