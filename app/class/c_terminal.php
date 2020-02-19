<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_oficina.php");
include_once("class/c_impresionxterminal.php");
class c_terminal
{
  var $ter_id;
  var $ofi_id;
  var $ter_nombre;
  var $ter_ip;
  var $ter_pto;
  
  //objetos
  var $oOficina;
  //var $oImpresionxterminal;
  
  var $msg;
  var $separador;
  
  var $con;
  
  function c_terminal($conDb)
  {
	  $this->ter_id="";
	  $this->ofi_id="";
	  $this->ter_nombre="";
	  $this->ter_ip="";
	  $this->ter_pto="";
	  
	  $this->con=&$conDb;
	  
	  $this->oOficina=new c_oficina($this->con);
	  //$this->oImpresionxterminal=new c_impresionxterminal($this->con);
	  
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
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=5;
	  if($ncampos==count($dato))
	  {
        $this->ter_id=$dato[0];
	    $this->ofi_id=$dato[1];
	    $this->ter_nombre=$dato[2];
	    $this->ter_ip=$dato[3];
	    $this->ter_pto=$dato[4];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=4;
	  if($ncampos==count($dato))
	  {
	    $this->ofi_id=$dato[0];
	    $this->ter_nombre=$dato[1];
	    $this->ter_ip=$dato[2];
	    $this->ter_pto=$dato[3];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);  
  }

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
  	$cadId=$this->armarSqlId("o.","t.");
  	$sql=<<<va
  	  select $cadId,
	  t.ter_id,o.ofi_nombre,t.ter_ip,t.ter_pto,
	  $cadId
	  from terminal t, oficina o 
	  where o.ofi_id=t.ofi_id
	  order by o.ofi_nombre,t.ter_id
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Código","Oficina","IP","Puerto","Modificar");
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
  	  define('tCodigo', 'string', 'Código',1,25,document);
  	  define('seOficina', 'string', 'Oficina',1,10,document);
  	  define('tNombre', 'string', 'Nombre',1,250,document);
  	  define('tIp', 'string', 'IP',1,30,document);
  	  define('tPto', 'num', 'Puerto',1,4,document);
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
				array("etiqueta"=>"* Código","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oOficina->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* IP","nombre"=>"tIp","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Puerto","nombre"=>"tPuerto","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
    $oAux=new c_terminal($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Código","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ter_id),
				array("etiqueta"=>"* Oficina","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oOficina->sqlSelect(),"valor"=>$oAux->ofi_id),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ter_nombre),
				array("etiqueta"=>"* IP","nombre"=>"tIp","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ter_ip),
				array("etiqueta"=>"* Puerto","nombre"=>"tPuerto","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ter_pto)
				
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
  		  <input type="button" name="boton" value="Impresión" onClick="fOpenWindow('aimpxter.php?padre=$id','ImpresionxTerminal','640','480')">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function mostrar_dato()
  {
	echo "<hr>Class c_terminal(conDb)<br>";
  	echo "ter_id:".$this->ter_id."<br>";
	echo "ofi_id:".$this->ofi_id."<br>";
	echo "ter_nombre:".$this->ter_nombre."<br>";
	echo "ter_ip:".$this->ter_ip."<br>";
	echo "ter_pto:".$this->ter_pto."<br>";
	echo "<hr>";
  }
  
  function id2cad($ofi,$ter)
  {
    $cad=$ofi.$this->separador.$ter;
    return($cad);
  }
  
  function cad2id($cad)
  {
    list($this->ofi_id,$this->ter_id)=explode($this->separador,$cad);
    return($cad);
  }
  
  function armarSqlId($prefijo1,$prefijo2)
  {
    $cad=$prefijo1."ofi_id||'".$this->separador."'||".$prefijo2."ter_id";
    return($cad);
  }
  
  function existe($vid)
  {
  	$oAux=new c_terminal($this->con);
  	$oAux->cad2id($vid);
  	$cadId=$this->armarSqlId("","");
    $sql=<<<va
    select $cadId 
    from terminal 
    where ter_id='$oAux->ter_id'
    and ofi_id='$oAux->ofi_id'
va;
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else 
	  $res=$rs->fields[0];
	return($res);
  }
  
  /*
  function nuevoCodigo()
  { 
    $sql="select nvl(max(to_number(ter_id)),0) from terminal";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  
  function add()
  {
  	$oAux=new c_terminal($this->con);
  	$cadId=$oAux->id2cad($this->ofi_id,$this->ter_id);
    $existe=$this->existe($cadId);
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into terminal
	  (
	    ter_id,ofi_id,ter_nombre,ter_ip,
	    ter_pto
	  )
	  values 
	  (
	    '$this->ter_id','$this->ofi_id','$this->ter_nombre','$this->ter_ip',
	    '$this->ter_pto'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->ofi_id,$this->ter_id);
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
 	$oAux=new c_terminal($this->con);
  	$oAux->cad2id($id);
      
    $sql=<<<va
 	delete from terminal 
	where ter_id='$oAux->ter_id' and ofi_id='$oAux->ofi_id' 
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
	  $oAux=new c_terminal($this->con);
	  $oAux->cad2id($id);
	  $sql=<<<va
	  UPDATE terminal 
	  set 
	  ofi_id='$this->ofi_id',
	  ter_nombre='$this->ter_nombre',
	  ter_ip='$this->ter_ip',
	  ter_pto='$this->ter_pto',
	  WHERE ter_id='$oAux->ter_id' and ofi_id='$oAux->ofi_id'
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
  	$oAux=new c_terminal($this->con);
	$oAux->cad2id($id);
      
    $sql=<<<va
  	select 
  	ter_id,ofi_id,ter_nombre,ter_ip,
	ter_pto 
  	from terminal
  	where ter_id='$oAux->ter_id' and ofi_id='$oAux->ofi_id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->ter_id="";
	  $this->ofi_id="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->ter_id=$rs->fields[0];
	  $this->ofi_id=$rs->fields[1];
	  $this->ter_nombre=$rs->fields[2];
	  $this->ter_ip=$rs->fields[3];
	  $this->ter_pto=$rs->fields[4];
	}
	return($res);
  }
  
  function sqlSelect($dato="")
  {
    $cadId=$this->armarSqlId("","");
    $oAux=new c_terminal($this->con);
    
    if($dato=="")
      $cad="select $cadId as idTer,ter_id from terminal order by idTer";
    else
    { 
      $oAux->cad2id($dato);
      $cad="select $cadId as idTer,ter_id from terminal where ofi_id='$oAux->ofi_id' and ter_id='$oAux->ter_id' order by idTer";  
    }
    return($cad);
  }
  
  function imprimir($terminal,$stotip,$fileodata)
  {
  	//buscar ip y pto de servidor de impresión
  	$cImpxter=new c_impresionxterminal($this->con);
  	$terminalImp=$cImpxter->buscarTerminalxStotip($terminal,$stotip);
  	if($terminalImp=="0")
  	{
  	  $this->msg=$cImpxter->msg;
  	  $res=0;
  	}
  	else 
  	{
  	  $oAux=new c_terminal($this->con);
  	  $oAux->info($terminalImp);
  	  $ip=$oAux->ter_ip;
  	  $pto=$oAux->ter_pto;
  	  
  	  include_once("class/c_stock_tipo.php");
  	  $oStotip=new c_stock_tipo($this->con);
  	  $oStotip->info($stotip);
  	  include_once("class/c_impresion.php");
  	  $oImp=new c_impresion();
  	  /*echo "******".$ip."ip<br>";
  	  echo "******".$pto."pto<br>";
  	  echo "******".$fileodata."file<br>";
  	  echo "******".$oStotip->stotip_formato."formato<br>";*/
  	  $res=$oImp->print2socket($ip,$pto,$fileodata,$oStotip->stotip_formato,0,"b",5,10);
  	}
  	return($res);
  }
  
  function buscaxTeridTerip($terId,$terIp)
  {
    $oAux=new c_terminal($this->con);
    $cadId=$oAux->armarSqlId("","");
    $sql=<<<va
	select $cadId
    from terminal
    where 
    ter_id='$terId' and ter_ip='$terIp'    
va;
	//echo "<hr>$sql<hr>";
    $rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->msg="Error.  Terminal no encontrado";
	}
	else 
	{
	  $res=$rs->fields[0];
	}
	return($res);
  }
  
  
}
?>