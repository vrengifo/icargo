<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_estacion.php");
include_once("class/c_cliente.php");
include_once("class/c_kiloequivalenciaxest.php");
include_once("class/c_parametro.php");
class c_kiloequivalenciaxcli
{
  var $est_codigoO;
  var $est_codigoD;
  var $cli_codigo;
  var $kilequ_precio;
  var $usu_audit;
  var $usu_faudit;
  
  //objetos
  var $oEstacion;
  var $oCliente;
  var $oKiloequivalenciaxest;
  var $oParametro;
  
  var $msg;
  var $separador;
  
  var $con;
  
  function c_kiloequivalenciaxcli($conDb,$usuario)
  { 
	  $this->est_codigoO="";
	  $this->est_codigoD="";
	  $this->cli_codigo="";
	  $this->kilequ_precio="";
	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");
	  
	  $this->con=&$conDb;
	  
	  $this->oEstacion=new c_estacion($this->con);
	  $this->oCliente=new c_cliente($this->con);
	  $this->oKiloequivalenciaxest=new c_kiloequivalenciaxest($this->con,$this->usu_audit);
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
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
        $this->est_codigoO=$dato[0];
	    $this->est_codigoD=$dato[1];
	    //$this->cli_codigo=$dato[2];
	    $this->kilequ_precio=$dato[2];
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
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
	    $this->est_codigoO=$dato[0];
	    $this->est_codigoD=$dato[1];
	    //$this->cli_codigo=$dato[2];
	    $this->kilequ_precio=$dato[2];
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
  	  <input type="button" name="Cerrar" value="Cerrar" onClick="window.close();">
  	  <br>
va;
  	$cadId=$this->armarSqlId("t.","t.","t.");
  	$sql=<<<va
  	  select $cadId,
	  e1.est_nombre,e2.est_nombre,c.cli_nombre,t.kilequ_precio,
	  $cadId
	  from kiloequivalenciaxcli t, estacion e1, estacion e2, cliente c 
	  where e1.est_codigo=t.est_codigoO
	  and e2.est_codigo=t.est_codigoD
	  and c.cli_codigo='$padre'
	  and c.cli_codigo=t.cli_codigo
	  order by e1.est_nombre,e2.est_nombre,c.cli_nombre
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Est. Origen","Est. Destino","Cliente","Costo Kilo","Modificar");
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
  	  define('seOrigen', 'string', 'Est. Origen',1,7,document);
  	  define('seDestino', 'string', 'Est. Destino',1,7,document);
  	  define('seCliente', 'string', 'Cliente',1,20,document);
  	  define('tPrecio', 'num', 'Costo Kilo',1,15,document);
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
				array("etiqueta"=>"* Est. Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Est. Destino","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>""),
				//array("etiqueta"=>"* Cliente","nombre"=>"seCliente","tipo_campo"=>"select","sql"=>$this->oCliente->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Costo Kilo","nombre"=>"tPrecio","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
    $oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Est. Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>$oAux->est_codigoO),
				array("etiqueta"=>"* Est. Destino","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>$oAux->est_codigoD),
				//array("etiqueta"=>"* Cliente","nombre"=>"seCliente","tipo_campo"=>"select","sql"=>$this->oCliente->sqlSelect(),"valor"=>$oAux->cli_codigo),
				array("etiqueta"=>"* Costo Kilo","nombre"=>"tPrecio","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->kilequ_precio)
				
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
	echo "<hr>Class c_kiloequivalenciaxcli(conDb)<br>";
  	echo "est_codigoO:".$this->est_codigoO."<br>";
	echo "est_codigoD:".$this->est_codigoD."<br>";
	echo "cli_codigo:".$this->cli_codigo."<br>";
	echo "kilequ_precio:".$this->kilequ_precio."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "<hr>";
  }
  
  function id2cad($estO,$estD,$cli)
  {
    $cad=$estO.$this->separador.$estD.$this->separador.$cli;
    return($cad);
  }
  
  function cad2id($cad)
  {
    list($this->est_codigoO,$this->est_codigoD,$this->cli_codigo)=explode($this->separador,$cad);
    return($cad);
  }
  
  function armarSqlId($prefijo1,$prefijo2,$prefijo3)
  {
    $cad=$prefijo1."est_codigoO||'".$this->separador."'||".$prefijo2."est_codigoD||'".$this->separador."'||".$prefijo3."cli_codigo";
    return($cad);
  }
  
  function existe($vid)
  {
  	$oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
  	$oAux->cad2id($vid);
  	$cadId=$this->armarSqlId("","","");
    $sql=<<<va
    select $cadId 
    from kiloequivalenciaxcli 
    where 
    (est_codigoO='$oAux->est_codigoO' and est_codigoD='$oAux->est_codigoD' )
    or
    (est_codigoO='$oAux->est_codigoD' and est_codigoD='$oAux->est_codigoO' )
    and cli_codigo='$oAux->cli_codigo'
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
    $sql="select nvl(max(to_number(est_codigoO)),0) from kiloequivalenciaxcli";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  
  function creaoActualiza()
  {
  	$oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($this->est_codigoO,$this->est_codigoD,$this->cli_codigo);
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
  	$oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($this->est_codigoO,$this->est_codigoD,$this->cli_codigo);
    $existe=$this->existe($cadId);
    
    if($this->est_codigoO==$this->est_codigoD)
      $existe=$cadId;
    
	if($existe=="0")
	{
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
		
	  $sql=<<<va
	  insert into kiloequivalenciaxcli
	  (
	    est_codigoO,est_codigoD,cli_codigo,kilequ_precio,usu_audit,
	    usu_faudit
	  )
	  values 
	  (
	    '$this->est_codigoO','$this->est_codigoD','$this->cli_codigo','$this->kilequ_precio','$this->usu_audit',
	    to_date('$this->usu_faudit','$fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->est_codigoO,$this->est_codigoD,$this->cli_codigo);
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
 	$oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
  	$oAux->cad2id($id);
      
    $sql=<<<va
 	delete from kiloequivalenciaxcli 
	where est_codigoO='$oAux->est_codigoO' and est_codigoD='$oAux->est_codigoD' 
	and cli_codigo='$oAux->cli_codigo'
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
	  $oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
	  $oAux->cad2id($id);
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE kiloequivalenciaxcli 
	  set 
	  kilequ_precio='$this->kilequ_precio',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga') 
	  WHERE est_codigoO='$oAux->est_codigoO' and est_codigoD='$oAux->est_codigoD'
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
  	$oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
	$oAux->cad2id($id);
    
	$fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;
	
    $sql=<<<va
  	select 
  	est_codigoO,est_codigoD,kilequ_precio,usu_audit,
	to_char(usu_faudit,'$fechaLarga'),
	cli_codigo 
  	from kiloequivalenciaxcli
  	where 
  	(est_codigoO='$oAux->est_codigoO' and est_codigoD='$oAux->est_codigoD')
  	or
  	(est_codigoO='$oAux->est_codigoD' and est_codigoD='$oAux->est_codigoO')
  	and cli_codigo='$oAux->cli_codigo'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->est_codigoO="";
	  $this->est_codigoD="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->est_codigoO=$rs->fields[0];
	  $this->est_codigoD=$rs->fields[1];
	  $this->kilequ_precio=$rs->fields[2];
	  $this->usu_audit=$rs->fields[3];
	  $this->usu_faudit=$rs->fields[4];
	  $this->cli_codigo=$rs->fields[5];
	}
	return($res);
  }
  
  function sqlSelect()
  {
    $cadId=$this->armarSqlId("","","");
    $cad="select $cadId a1,$cadId a2 from kiloequivalenciaxcli order by a1";
    return($cad);
  }
  
  function recuperarCosto($estO,$estD,$cli)
  {
  	$oAux=new c_kiloequivalenciaxcli($this->con,$this->usu_audit);
    $cadId=$oAux->id2cad($estO,$estD,$cli);
    $resInfo=$oAux->info($cadId);
    if($resInfo=="0")
    {
      $res=$oAux->oKiloequivalenciaxest->recuperarCosto($estO,$estD);
    }
    else
      $res=$oAux->kilequ_precio;
    return($res);
  }
}
?>