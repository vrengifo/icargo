<?php
include_once("adodb/tohtml.inc.php");

class c_impdescargo
{
  var $idc_id;
  var $idc_nombre;
  var $idc_valor;
  var $idc_observacion;
  
  //objetos
  
  var $msg;
  
  var $con;
  
  function c_impdescargo($conDb)
  {
	  $this->idc_id="";
	  $this->idc_nombre="";
	  $this->idc_valor="";
	  $this->idc_observacion="";
	  
	  $this->con=&$conDb;
	  
	  $this->msg="";
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
        $this->idc_nombre=$dato[0];
	    $this->idc_valor=$dato[1];
	    $this->idc_observacion=$dato[2];
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
	    $this->idc_nombre=$dato[0];
	    $this->idc_valor=$dato[1];
	    $this->idc_observacion=$dato[2];
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
  	
  	$sql=<<<va
  	  select e.idc_id,
	  e.idc_nombre,e.idc_valor,e.idc_observacion,
	  e.idc_id
	  from impdescargo e 
	  order by e.idc_nombre
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Nombre","Valor (Porcentaje)","Observación","Modificar");
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
  	  define('tNombre', 'string', 'Nombre',1,100,document);
  	  define('tValor', 'num', 'Valor',1,5,document);
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
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Valor","nombre"=>"tValor","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"  Observación","nombre"=>"tObservacion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				
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
    $oAux=new c_impdescargo($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->idc_nombre),
				array("etiqueta"=>"* Valor","nombre"=>"tValor","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->idc_valor),
				array("etiqueta"=>"  Observación","nombre"=>"tObservacion","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->idc_observacion)
				
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
	echo "<hr>Class c_impdescargo(conDb)<br>";
  	echo "idc_id:".$this->idc_id."<br>";
	echo "idc_id:".$this->idc_id."<br>";
	echo "idc_id:".$this->idc_valor."<br>";
	echo "idc_id:".$this->idc_observacion."<br>";
	echo "<hr>";
  }
  
  function existe($vid)
  {
  	$sql="select idc_id from impdescargo where idc_nombre='$vid'";
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res=0;
	else 
	  $res=$rs->fields[0];
	return($res);
  } 
  
  /*
  function nuevoCodigo()
  { 
    $sql="select nvl(max(to_number(idc_id)),0) from impdescargo";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  
  function add()
  {
  	$existe=$this->existe($this->idc_nombre);
	if($existe==0)
	{
	  $sql=<<<va
	  insert into impdescargo
	  (
	    idc_nombre,idc_valor,idc_observacion
	  )
	  values 
	  (
	    '$this->idc_nombre','$this->idc_valor','$this->idc_observacion'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->existe($this->idc_nombre);
	  }
	  else 
	  {
	    $res=0;
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
 	delete from impdescargo 
	where idc_id='$id' 
va;
	$rs = &$this->con->Execute($sql);
	if($rs)
	  $res=$id;
	else
	{
	  $res=0;
	  $this->msg="No se puede eliminar dato!!!";
	}
	return($res);	
  }
  
  function update($id)
  {
    $oAux=new c_impdescargo($this->con);
    $existe=$oAux->info($id);
    if($oAux->idc_nombre!=$this->idc_nombre)
    {
      $existeNombre=$oAux->existe($this->idc_nombre);
      if($existeNombre)
        $this->idc_nombre=$oAux->idc_nombre;
      else
        $this->idc_nombre=$this->idc_nombre;  
    }
	if($existe!=0)
	{
	  $sql=<<<va
	  UPDATE impdescargo 
	  set 
	  idc_nombre='$this->idc_nombre',
	  idc_valor='$this->idc_valor',
	  idc_observacion='$this->idc_observacion'
	  WHERE idc_id='$id'
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
  	$sql=<<<va
  	select 
  	idc_id,idc_nombre,idc_valor,idc_observacion 
  	from impdescargo
  	where idc_id='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->idc_id="";
	  $this->idc_nombre="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->idc_id=$rs->fields[0];
	  $this->idc_nombre=$rs->fields[1];
	  $this->idc_valor=$rs->fields[2];
	  $this->idc_observacion=$rs->fields[3];
	}
	return($res);
  }
  
  function sqlSelect()
  {
    $cad="select idc_id,idc_nombre from impdescargo order by idc_nombre";
    return($cad);
  }
}
?>