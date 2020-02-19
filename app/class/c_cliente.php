<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_verdadfalso.php");
class c_cliente
{
  var $cli_codigo;
  var $cli_nombre;
  var $cli_ciruc;
  var $cli_contacto;
  var $cli_direccion;
  var $cli_telefono;
  var $cli_fax;
  var $cli_email;
  var $cli_convenio;
  var $cli_observacion;
  
  //objetos
  var $oVerdadFalso;
  
  var $msg;
  
  var $con;
  
  function c_cliente($conDb)
  {
	  $this->cli_codigo="";
	  $this->cli_nombre="";
	  $this->cli_ciruc="";
	  $this->cli_contacto="";
	  $this->cli_direccion="";
	  $this->cli_telefono="";
	  $this->cli_fax="";
	  $this->cli_email="";
	  $this->cli_convenio="";
	  $this->cli_observacion="";
	  
	  $this->con=&$conDb;
	  $this->oVerdadFalso=new c_verdadfalso($this->con);
	  
	  $this->msg="";
  }
    
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=9;
	  if($ncampos==count($dato))
	  {
        $this->cli_codigo=$dato[0];
        $this->cli_ciruc=$dato[0];
        
	    $this->cli_nombre=$dato[1];
	    $this->cli_contacto=$dato[2];
	    $this->cli_direccion=$dato[3];
	    $this->cli_telefono=$dato[4];
	    $this->cli_fax=$dato[5];
	    $this->cli_email=$dato[6];
	    $this->cli_convenio=$dato[7];
	    $this->cli_observacion=$dato[8];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=9;
	  if($ncampos==count($dato))
	  {
        $this->cli_nombre=$dato[0];
        $this->cli_ciruc=$dato[1];
	    $this->cli_contacto=$dato[2];
	    $this->cli_direccion=$dato[3];
	    $this->cli_telefono=$dato[4];
	    $this->cli_fax=$dato[5];
	    $this->cli_email=$dato[6];
	    $this->cli_convenio=$dato[7];
	    $this->cli_observacion=$dato[8];
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
  	 select c.cli_codigo,
	 c.cli_codigo,c.cli_nombre,c.cli_ciruc,c.cli_contacto,c.cli_direccion,
	 c.cli_telefono,c.cli_fax,c.cli_email,vf.vf_texto 
	 ,c.cli_codigo 
	 from cliente c, verdadfalso vf
	 where vf.vf_valor=c.cli_convenio
	 order by c.cli_nombre,c.cli_ciruc
va;
		
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Codigo","Nombre","CI/RUC","Contacto","Dirección","Teléfono","Fax","E-mail","Convenio?","Modificar");
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
  	  //define('tCodigo', 'string', 'Código',1,20,document);
  	  define('tNombre', 'string', 'Nombre',1,250,document);
  	  define('tCiruc', 'string', 'CI/RUC',1,13,document);
  	  define('tContacto', 'string', 'Contacto',1,250,document);
  	  define('tDireccion', 'string', 'Dirección',1,300,document);
  	  define('tTelefono', 'string', 'Teléfono',1,25,document);
  	  define('tFax', 'string', 'Fax',1,25,document);
  	  define('tMail', 'email', 'E-mail',1,100,document);
  	  define('seConvenio', 'string', 'Convenio?',1,1,document);
  	  define('tObservacion', 'string', 'Observación',1,10000,document);
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
				//array("etiqueta"=>"* Código","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* CI/RUC","nombre"=>"tCiruc","tipo_campo"=>"text","sql"=>"","valor"=>"","js"=>"onBlur=valida_ci(form1.tCiruc);"),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Contacto","nombre"=>"tContacto","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Dirección","nombre"=>"tDireccion","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Teléfono","nombre"=>"tTelefono","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fax","nombre"=>"tFax","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* E-mail","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Convenio","nombre"=>"seConvenio","tipo_campo"=>"select","sql"=>$this->oVerdadFalso->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Observacion","nombre"=>"tObservacion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				
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
  		  <!--<input type="button" name="AddB" value="A&ntilde;adirB" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }

  function adminAddSinConvenio($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo)
  {
	$campo=array(
				//array("etiqueta"=>"* Código","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* CI/RUC","nombre"=>"tCiruc","tipo_campo"=>"text","sql"=>"","valor"=>"","js"=>"onBlur=valida_ci(form1.tCiruc);"),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Contacto","nombre"=>"tContacto","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Dirección","nombre"=>"tDireccion","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Teléfono","nombre"=>"tTelefono","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fax","nombre"=>"tFax","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* E-mail","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"*** Cliente Sin Convenio ***","nombre"=>"seConvenio","tipo_campo"=>"hidden","sql"=>"","valor"=>"0"),
				array("etiqueta"=>"* Observacion","nombre"=>"tObservacion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				
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
  		  <!--<input type="button" name="AddB" value="A&ntilde;adirB" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }  
  
  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)  
  {
  	$oAux=new c_cliente($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Código","nombre"=>"tCodigo","tipo_campo"=>"nada","sql"=>"","valor"=>$oAux->cli_codigo),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->cli_nombre),
				array("etiqueta"=>"* CI/RUC","nombre"=>"tCiruc","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->cli_ciruc),
				array("etiqueta"=>"* Contacto","nombre"=>"tContacto","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->cli_contacto),
				array("etiqueta"=>"* Dirección","nombre"=>"tDireccion","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->cli_direccion),
				array("etiqueta"=>"* Teléfono","nombre"=>"tTelefono","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->cli_telefono),
				array("etiqueta"=>"* Fax","nombre"=>"tFax","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->cli_fax),
				array("etiqueta"=>"* E-mail","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->cli_email),
				array("etiqueta"=>"* Convenio","nombre"=>"seConvenio","tipo_campo"=>"select","sql"=>$this->oVerdadFalso->sqlSelect(),"valor"=>$oAux->cli_convenio),
				array("etiqueta"=>"* Observacion","nombre"=>"tObservacion","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->cli_observacion)
				
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
  		  <!--<input type="button" name="AddB" value="A&ntilde;adirB" onClick="return vValidarB(document.form1,'$formaAction');">-->  		  
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
  		  <input type="button" name="boton" value="Equivalencia Kilos" onClick="fOpenWindow('kiloequixcli.php?padre=$id','EquivalenciaKilosCliente','640','480')">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function mostrar_dato()
  {
	echo "<hr>Class c_cliente(conDb)<br>";
  	echo "cli_codigo:".$this->cli_codigo."<br>";
	echo "cli_nombre:".$this->cli_nombre."<br>";
	echo "cli_ciruc:".$this->cli_ciruc."<br>";
	echo "cli_contacto:".$this->cli_contacto."<br>";
	echo "cli_direccion:".$this->cli_direccion."<br>";
	echo "cli_telefono:".$this->cli_telefono."<br>";
	echo "cli_fax:".$this->cli_fax."<br>";
	echo "cli_email:".$this->cli_email."<br>";
	echo "cli_convenio:".$this->cli_convenio."<br>";
	echo "cli_observacion:".$this->cli_observacion."<br>";
	echo "<hr>";
  }
  
  function existe($vid)
  {
  	$sql="select cli_codigo from cliente where cli_codigo='$vid'";
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
    $sql="select nvl(max(to_number(cli_codigo)),0) from cliente";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  
  function validarClob()
  {
    if(strlen($this->cli_observacion)==0)
	    $this->cli_observacion="-";
  }
  
  function add()
  {
  	$existe=$this->existe($this->cli_codigo);
	if($existe=="0")
	{
	  $this->validarClob();
	  $sql=<<<va
	  insert into cliente
	  (
	    cli_codigo,cli_nombre,cli_ciruc,cli_contacto,
	    cli_direccion,cli_telefono,cli_fax,cli_email,
	    cli_convenio,cli_observacion
	  )
	  values 
	  (
	    '$this->cli_codigo','$this->cli_nombre','$this->cli_ciruc','$this->cli_contacto',
	    '$this->cli_direccion','$this->cli_telefono','$this->cli_fax','$this->cli_email',
	    '$this->cli_convenio','$this->cli_observacion'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->cli_codigo;
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
 	delete from cliente 
	where cli_codigo='$id' 
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
	  $sql=<<<va
	  UPDATE cliente 
	  set 
	  cli_contacto='$this->cli_contacto',
	  cli_direccion='$this->cli_direccion',
	  cli_telefono='$this->cli_telefono',
	  cli_fax='$this->cli_fax',
	  cli_email='$this->cli_email',
	  cli_convenio='$this->cli_convenio',
	  cli_observacion='$this->cli_observacion' 
	  WHERE cli_codigo='$id'
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
  	cli_codigo,cli_nombre,cli_ciruc,cli_contacto,
	cli_direccion,cli_telefono,cli_fax,cli_email,
	cli_convenio,cli_observacion  
  	from cliente
  	where cli_codigo='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->cli_codigo="";
	  $this->cli_nombre="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->cli_codigo=$rs->fields[0];
	  $this->cli_nombre=$rs->fields[1];
	  $this->cli_ciruc=$rs->fields[2];
	  $this->cli_contacto=$rs->fields[3];
	  $this->cli_direccion=$rs->fields[4];
	  $this->cli_telefono=$rs->fields[5];
	  $this->cli_fax=$rs->fields[6];
	  $this->cli_email=$rs->fields[7];
	  $this->cli_convenio=$rs->fields[8];
	  $this->cli_observacion=$rs->fields[9];
	}
	return($res);
  }
  
  /**
   * Cliente sin convenio
   *
   * @return string
   */
  function sqlSelect()
  {
  	$cad="select cli_codigo,cli_ciruc||':'||cli_nombre from cliente where cli_convenio='0' order by cli_ciruc,cli_nombre ";
  	return($cad);
  }
  
  /**
   * Cliente con convenio
   *
   * @return string
   */
  function sqlSelectConvenio()
  {
  	$cad="select cli_codigo,cli_ciruc||':'||cli_nombre from cliente where cli_convenio='1' order by cli_ciruc,cli_nombre ";
  	return($cad);
  }
}
?>