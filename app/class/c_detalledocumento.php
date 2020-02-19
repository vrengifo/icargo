<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_documento.php");
include_once("class/c_parametro.php");
class c_detalledocumento
{
  var $sto_id;
  var $ofi_id;
  var $stotip_id;
  var $sto_nro;
  var $detdoc_ref;
  
  //objetos
  var $oDocumento;
  var $oParametro;

  var $msg;
  var $separador;
  var $usuario;
  var $terminal;

  var $con;

  function c_detalledocumento($conDb,$terminal)
  {
	  $this->sto_id="";
	  $this->ofi_id="";
	  $this->stotip_id="";
	  $this->detdoc_ref="";
	  $this->sto_nro=0;

	  $this->con=&$conDb;
	  
	  $this->usuario="icargo";
	  $this->terminal=$terminal;

	  $this->oDocumento=new c_documento($this->con,$this->usuario, $terminal);
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
	    $this->detdoc_ref=$dato[3];
	    $this->borrar=$dato[4];
	    $this->borrar=$dato[5];
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
	    $this->detdoc_ref=$dato[0];
	    $this->borrar=$dato[1];
	    $this->borrar=$dato[2];
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
	  to_char(s.detdoc_ref,'$this->oParametro->fechaCorta') fecha,to_char(s.borrar,'$this->oParametro->fechaCorta') fecha exp,
	  s.borrar,s.borrar,s.sto_nro,
	  $cadId
	  from detalledocumento s, oficina o, stock_tipo t
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
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oDocumento->sqlSelect(),"valor"=>""),
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
    $oAux=new c_detalledocumento($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oDocumento->sqlSelect(),"valor"=>$oAux->ofi_id),
				array("etiqueta"=>"* Tipo Doc.","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect(),"valor"=>$oAux->stotip_id),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->detdoc_ref),
				array("etiqueta"=>"* Fecha Expiración","nombre"=>"tFechaExp","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Desde","nombre"=>"tIni","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->borrar),
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
	echo "<hr>Class c_detalledocumento(conDb,terminal)<br>";
  	echo "sto_id:".$this->sto_id."<br>";
	echo "ofi_id:".$this->ofi_id."<br>";
	echo "stotip_id:".$this->stotip_id."<br>";
	echo "sto_nro:".$this->sto_nro."<br>";
	echo "detdoc_ref:".$this->detdoc_ref."<br>";
	echo "<hr>";
  }

  /*
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
  */

  function existe($vid)
  {
  	$oAux=new c_detalledocumento($this->con,$this->terminal);
  	$oAux->info($vid);
    $sql=<<<va
    select detdoc_ref
    from detalledocumento
    where detdoc_ref='$oAux->detdoc_ref'
va;
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else
	  $res=$rs->fields[0];
	return($res);
  }

  function add()
  {
  	$oAux=new c_detalledocumento($this->con,$this->terminal);
    $existe=$this->existe($this->detdoc_ref);
    
    $fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;
    
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into detalledocumento
	  (
	    sto_id,ofi_id,stotip_id,sto_nro,
	    detdoc_ref
	  )
	  values
	  (
	    '$this->sto_id','$this->ofi_id','$this->stotip_id','$this->sto_nro',
	    '$this->detdoc_ref'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->detdoc_ref;
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
 	delete from detalledocumento
	where detdoc_ref='$id' 
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
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE detalledocumento
	  set
	  detdoc_ref='$this->detdoc_ref',
	  WHERE detdoc_ref='$id'
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
  	$oAux=new c_detalledocumento($this->con,$this->terminal);
	
	$fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;

    $sql=<<<va
  	select
  	sto_id,ofi_id,stotip_id,
	detdoc_ref,sto_nro 
  	from detalledocumento
  	where detdoc_ref='$id' 
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
	  $this->detdoc_ref=$rs->fields[3];
	  $this->sto_nro=$rs->fields[4];
	}
	return($res);
  }

  /*
  function sqlSelect()
  {
    $cadId=$this->armarSqlId("","");
    $cad="select $cadId a1,$cadId a2 from detalledocumento order by a1";
    return($cad);
  }
  */
  
}
?>