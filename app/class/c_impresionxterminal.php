<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_terminal.php");
include_once("class/c_stock_tipo.php");
class c_impresionxterminal
{
  var $ter_id;
  var $ofi_id;
  var $stotip_id;
  var $ter_id_imp;
  var $ofi_id_imp;
  //no en base
  var $terminalId;
  var $terminalIdImp;
  
  //objetos
  var $oTerm;
  var $oStockTipo;
  
  var $msg;
  var $separador;
  
  var $con;
  
  function c_impresionxterminal($conDb)
  {
	  $this->ter_id="";
	  $this->ofi_id="";
	  $this->stotip_id="";
	  $this->ter_id_imp="";
	  $this->ofi_id_imp="";
	  
	  $this->con=&$conDb;
	  
	  $this->oTerm=new c_terminal($this->con);
	  $this->oStockTipo=new c_stock_tipo($this->con);
	  
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
  	$oAuxTer=new c_terminal($this->con);
  	if($iou=="i")
  	{
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
        $this->stotip_id=$dato[0];
	  	$this->terminalId=$dato[1];
	  	$oAuxTer->cad2id($this->terminalId);
	  	$this->ter_id=$oAuxTer->ter_id;
	  	$this->ofi_id=$oAuxTer->ofi_id;
	    $this->terminalIdImp=$dato[2];
	    $oAuxTer->cad2id($this->terminalIdImp);
	    $this->ter_id_imp=$oAuxTer->ter_id;
	    $this->ofi_id_imp=$oAuxTer->ofi_id;
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
	    $this->stotip_id=$dato[0];
	  	$this->terminalId=$dato[1];
	  	$oAuxTer->cad2id($this->terminalId);
	  	$this->ter_id=$oAuxTer->ter_id;
	  	$this->ofi_id=$oAuxTer->ofi_id;
	    $this->terminalIdImp=$dato[2];
	    $oAuxTer->cad2id($this->terminalIdImp);
	    $this->ter_id_imp=$oAuxTer->ter_id;
	    $this->ofi_id_imp=$oAuxTer->ofi_id;
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);  
  }

  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$padre)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&padre=".$padre;
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">
	  <input type="hidden" name="padre" value="$padre">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  <br>
va;
  	$cadId=$this->armarSqlId("ixt.","ixt.","ixt.");
  	$oAuxTer=new c_terminal($this->con);
  	$oAuxTer->cad2id($padre);
  	
  	$sql=<<<va
  	  select $cadId,
	  st.stotip_nombre,t1.ter_nombre,t2.ter_nombre,
	  $cadId
	  from impresionxterminal ixt, stock_tipo st, terminal t1, terminal t2 
	  where st.stotip_id=ixt.stotip_id
	  and ixt.ofi_id='$oAuxTer->ofi_id' and ixt.ter_id='$oAuxTer->ter_id'
	  and (t1.ofi_id=ixt.ofi_id and t1.ter_id=ixt.ter_id)
	  and (t2.ofi_id=ixt.ofi_id_imp and t2.ter_id=ixt.ter_id_imp)
	  order by st.stotip_nombre,t1.ter_nombre,t2.ter_nombre
va;
	//echo "<hr>$sql<hr>";
  	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Tipo","Terminal","Terminal Impresión","Modificar");
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
  	  define('seStockTipo', 'string', 'Tipo',1,3,document);
  	  define('seTerminal', 'string', 'Terminal',1,36,document);
  	  define('seTerminalImp', 'string', 'Terminal Impresión',1,36,document);
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
  
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$padre)
  {
	$campo=array(
				array("etiqueta"=>"* Tipo","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect("0"),"valor"=>""),
				array("etiqueta"=>"* Terminal","nombre"=>"seTerminal","tipo_campo"=>"select","sql"=>$this->oTerm->sqlSelect($padre),"valor"=>$padre),
				array("etiqueta"=>"* Terminal Impresión","nombre"=>"seTerminalImp","tipo_campo"=>"select","sql"=>$this->oTerm->sqlSelect(),"valor"=>"")
				
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"padre","valor"=>$padre),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/360/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&padre=".$padre;
  	
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
  
  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id,$padre)  
  {
    $oAux=new c_impresionxterminal($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Tipo","nombre"=>"seStockTipo","tipo_campo"=>"select","sql"=>$this->oStockTipo->sqlSelect("0"),"valor"=>$oAux->stotip_id),
				array("etiqueta"=>"* Terminal","nombre"=>"seTerminal","tipo_campo"=>"select","sql"=>$this->oTerm->sqlSelect($padre),"valor"=>$oAux->terminalId),
				array("etiqueta"=>"* Terminal Impresión","nombre"=>"seTerminalImp","tipo_campo"=>"select","sql"=>$this->oTerm->sqlSelect(),"valor"=>$oAux->terminalIdImp)
				
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
			  		array("nombre"=>"padre","valor"=>$padre),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_updCad($this->con,'false',$titulo,'images/360/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden,$id);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&padre=".$padre;
  	
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
	echo "<hr>Class c_impresionxterminal(conDb)<br>";
  	echo "ter_id:".$this->ter_id."<br>";
	echo "ofi_id:".$this->ofi_id."<br>";
	echo "stotip_id:".$this->stotip_id."<br>";
	echo "ter_id_imp:".$this->ter_id_imp."<br>";
	echo "ofi_id_imp:".$this->ofi_id_imp."<br>";
	echo "terminalId:".$this->terminalId."<br>";
	echo "terminalIdImp:".$this->terminalIdImp."<br>";
	echo "<hr>";
  }
  
  function id2cad($stotip,$ofi,$ter)
  {
    $cad=$stotip.$this->separador.$ofi.$this->separador.$ter;
    return($cad);
  }
  
  function cad2id($cad)
  {
    list($this->stotip_id,$this->ofi_id,$this->ter_id)=explode($this->separador,$cad);
    return($cad);
  }
  
  function armarSqlId($prefijo1,$prefijo2,$prefijo3)
  {
    $cad=$prefijo1."stotip_id||'".$this->separador."'||".$prefijo2."ofi_id||'".$this->separador."'||".$prefijo3."ter_id";
    return($cad);
  }
  
  function existe($vid)
  {
  	$oAux=new c_impresionxterminal($this->con);
  	$oAux->cad2id($vid);
  	$cadId=$this->armarSqlId("","","");
    $sql=<<<va
    select $cadId 
    from impresionxterminal 
    where ter_id='$oAux->ter_id'
    and ofi_id='$oAux->ofi_id'
    and stotip_id='$oAux->stotip_id'
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
    $sql="select nvl(max(to_number(ter_id)),0) from impresionxterminal";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  
  function creaoActualiza()
  {
  	$oAux=new c_impresionxterminal($this->con);
  	$cadId=$oAux->id2cad($this->stotip_id,$this->ofi_id,$this->ter_id);
  	$existe=$this->existe($cadId);
  	if($existe!="0")
  	{
  	  $res=$this->update($existe);
  	}
  	else 
  	{
  	  $res=$this->add();
 	}
 	return($res);
  }
  
  function add()
  {
  	$oAux=new c_impresionxterminal($this->con);
  	$cadId=$oAux->id2cad($this->stotip_id,$this->ofi_id,$this->ter_id);
    $existe=$this->existe($cadId);
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into impresionxterminal
	  (
	    ter_id,ofi_id,stotip_id,ter_id_imp,
	    ofi_id_imp
	  )
	  values 
	  (
	    '$this->ter_id','$this->ofi_id','$this->stotip_id','$this->ter_id_imp',
	    '$this->ofi_id_imp'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->stotip_id,$this->ofi_id,$this->ter_id);
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
 	$oAux=new c_impresionxterminal($this->con);
  	$oAux->cad2id($id);
      
    $sql=<<<va
 	delete from impresionxterminal 
	where ter_id='$oAux->ter_id' and ofi_id='$oAux->ofi_id' and stotip_id='$oAux->stotip_id'
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
	  $oAux=new c_impresionxterminal($this->con);
	  $oAux->cad2id($id);
	  $sql=<<<va
	  UPDATE impresionxterminal 
	  set 
	  ter_id_imp='$this->ter_id_imp',
	  ofi_id_imp='$this->ofi_id_imp' 
	  WHERE ter_id='$oAux->ter_id' and ofi_id='$oAux->ofi_id'
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
  	$oAux=new c_impresionxterminal($this->con);
	$oAux->cad2id($id);
      
    $sql=<<<va
  	select 
  	ter_id,ofi_id,stotip_id,ter_id_imp,
	ofi_id_imp 
  	from impresionxterminal
  	where ter_id='$oAux->ter_id' and ofi_id='$oAux->ofi_id'
  	and stotip_id='$oAux->stotip_id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->ter_id="";
	  $this->ofi_id="";
	  $this->terminalId="";
	  $this->terminalIdImp="";
	  
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->ter_id=$rs->fields[0];
	  $this->ofi_id=$rs->fields[1];
	  $this->stotip_id=$rs->fields[2];
	  $this->ter_id_imp=$rs->fields[3];
	  $this->ofi_id_imp=$rs->fields[4];
	  
	  $a=new c_terminal($this->con);
	  $this->terminalId=$this->oTerm->id2cad($this->ofi_id,$this->ter_id);
	  $this->terminalIdImp=$this->oTerm->id2cad($this->ofi_id_imp,$this->ter_id_imp);
	}
	return($res);
  }
  
  function sqlSelect()
  {
    $cadId=$this->armarSqlId("","");
    $cad="select $cadId as idTer,ter_id from impresionxterminal order by idTer";
    return($cad);
  }
  
  function buscarTerminalxStotip($terminal,$stotip)
  {
  	$oTer=new c_terminal($this->con);
  	$oTer->cad2id($terminal);
  	$sql=<<<va
  	select ter_id_imp,ofi_id_imp
  	from impresionxterminal
  	where 
  	stotip_id='$stotip'
  	and ofi_id='$oTer->ofi_id'
  	and ter_id='$oTer->ter_id'
va;
	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->msg="Error.  Configuración no válida para terminal";
	}
	else 
	{
	  $terIdImp=$rs->fields[0];
	  $ofiIdImp=$rs->fields[1];
	  $res=$oTer->id2cad($ofiIdImp,$terIdImp);
	}
	return($res);
  }
}
?>