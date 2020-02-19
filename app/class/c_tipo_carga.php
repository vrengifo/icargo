<?php
include_once("adodb/tohtml.inc.php");

class c_tipo_carga
{
  var $tipcar_id;
  var $tipcar_descripcion;
  
  //objetos
  
  
  var $msg;
  
  var $con;
  
  function c_tipo_carga($conDb)
  {    
      $this->tipcar_id="";
	  $this->tipcar_descripcion="";
	  
	  $this->con=&$conDb;
	  $this->msg="";
  }
    
  function cargar_dato($dato)			
  {
	if($iou=="i")
  	{
  	  $ncampos=1;
	  if($ncampos==count($dato))
	  {
        //$this->tipcar_id=$dato[0];
	    $this->tipcar_descripcion=$dato[0];

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
	    $this->tipcar_descripcion=$dato[0];
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
  	  select tipcar_id a1,
	  tipcar_descripcion,
	  tipcar_id a2 
	  from tipo_carga 
	  order by a1,tipcar_descripcion
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Código","Nombre","Modificar");
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
  	  //define('tCodigo', 'string', 'Id',1,3,document);
  	  define('tNombre', 'string', 'Nombre',1,30,document);
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
				//array("etiqueta"=>"* Id","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
    $oAux=new c_tipo_carga($this->con);
  	$oAux->info($id);
	$campo=array(
				//array("etiqueta"=>"* Id","nombre"=>"tCodigo","tipo_campo"=>"nada","sql"=>"","valor"=>$oAux->pai_codigo),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->pai_descripcion)
				
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
	echo "<hr>Class c_tipo_carga(conDb)<br>";
  	echo "tipcar_id:".$this->tipcar_id."<br>";
	echo "tipcar_descripcion:".$this->tipcar_descripcion."<br>";		
	echo "<hr>";
  }
  
  function existe($vid)
  {
  	$sql="select tipcar_id from tipo_carga where tipcar_descripcion='$vid'";
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res=0;
	else 
	  $res=$rs->fields[0];
	return($res);
  }
  
  function crearId()
  {
    $sql=<<<va
    select nvl(max(tipcar_id),0)
    from tipo_carga
va;
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0]+1;
    return($res);
  }
  
  function add()
  {
	$existe=$this->existe($this->tipcar_descripcion);	
	if($existe==0)
	{
	  $this->tipcar_id=$this->crearId();
	  $sql=<<<va
	  insert into tipo_carga
	  (tipcar_id,tipcar_descripcion)
	  values 
	  ('$this->tipcar_id','$this->tipcar_descripcion')
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	    $res=$this->tipcar_id;
	  else 
	    $res=0;  
	}
	else
	{
	  $res=$existe;	
	}
	return($res);				
  }
  
  function del($id)
  {
 	$sql=<<<va
 	delete from tipo_carga 
	where tipcar_id='$id' 
va;
	$rs = &$this->con->Execute($sql);
	if($rs)
	  $res=$id;
	else
	  $res="0";
	return($res);    		
  }
  
  function update($id)
  {
	$oAux=new c_tipo_carga($this->con);
	$resInfo=$oAux->info($id);
	if($resInfo)
	{
      $existe=$oAux->existe($this->tipcar_descripcion);
	  if(!$existe)
	  {
	    $sql=<<<va
	    UPDATE tipo_carga 
	    set tipcar_descripcion='$this->tipcar_descripcion' 
	    WHERE tipcar_id='$existe'
va;
        $rs=&$this->con->Execute($sql);
        $res=$existe;
	  }
	  else 
	  {
	    $res=$existe;	
	  }
	}
	else
	{
	  $res=$resInfo;
	}
	return($res);
  }
  
  function info($id)
  {
  	$sql=<<<va
  	select tipcar_id,tipcar_descripcion from tipo_carga
  	where tipcar_id='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->tipcar_id="";
	  $this->tipcar_descripcion="";	
	}
	else 
	{
	  $res=$id;
	  $this->tipcar_id=$rs->fields[0];
	  $this->tipcar_descripcion=$rs->fields[1];
	}
	return($res);
  }
  
  function sqlSelect()
  {
  	$cad="select tipcar_id,tipcar_descripcion from tipo_carga order by tipcar_descripcion";
  	return($cad);
  }
  
}
?>