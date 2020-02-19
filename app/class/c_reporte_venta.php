<?php
//include_once("adodb/tohtml.inc.php");
//include_once("class/c_oficina.php");
include_once("class/c_parametro.php");
//include_once("class/c_usuario.php");

class c_reporte_venta
{
  var $repven_id;
  var $repven_nro;
  var $repven_fecha;
  var $repven_por;
  var $repven_uaudit;
  var $repven_faudit;
  var $repven_total_cash;
  var $repven_total_collect;
  var $repven_total_credito;
  var $repven_total;
  var $ofi_id;
  
  //objetos
  
  var $fechaCorta;
  var $fechaLarga;
  var $fechaNro;//formato para hacer codigo una fecha
  
  var $msg;
  var $separador;
  
  var $con;
  
  function c_reporte_venta($conDb,$usuario,$oficina)
  {
	  $this->repven_id="";
	  $this->repven_nro="";
	  $this->repven_fecha="";
	  $this->repven_por="";
	  $this->repven_uaudit=$usuario;
	  $this->repven_faudit=date("Y-m-d H:i:s");
	  $this->repven_total_cash="";
	  $this->repven_total_collect="";
	  $this->repven_total_credito="";
	  $this->repven_total="";
	  
	  $this->con=&$conDb;
	  /*
	  $this->oOficina=new c_oficina($this->con);
	  $this->oUsuario=new c_usuario($this->con);
	  $this->oParametro=new c_parametro($this->con);
	  $this->oParametro->info();
	  */
	  $oParametro= new c_parametro($this->con);
	  $oParametro->info();
	  $this->fechaCorta=$oParametro->fechaCorta;
	  $this->fechaLarga=$oParametro->fechaLarga;
	  $this->fechaNro="YYMMDD";
	  
	  $this->msg="";
	  $this->separador=":";
	  
	  $this->ofi_id=$oficina;
  }
    
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=7;
	  if($ncampos==count($dato))
	  {
        //$this->repven_id=$dato[0];
	    //$this->repven_nro=$dato[0];
	    $this->ofi_id=$dato[0];
	    $this->repven_fecha=$dato[1];
	    $this->repven_por=$dato[2];
	    //$this->repven_uaudit=$dato[3];
	    //$this->repven_faudit=$dato[4];
	    $this->repven_total_cash=$dato[3];
	    $this->repven_total_collect=$dato[4];
	    $this->repven_total_credito=$dato[5];
	    $this->repven_total=$dato[6];
	    
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
        //$this->repven_id=$dato[0];
	    $this->ofi_id=$dato[0];
	    $this->repven_fecha=$dato[1];
	    $this->repven_por=$dato[2];
	    //$this->repven_uaudit=$dato[3];
	    //$this->repven_faudit=$dato[4];
	    $this->repven_total_cash=$dato[3];
	    $this->repven_total_collect=$dato[4];
	    $this->repven_total_credito=$dato[5];
	    $this->repven_total=$dato[6];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);  
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
  	
  	$fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;
  	
  	$sql=<<<va
  	 select c.repven_id,
	 o.ofi_nombre,
  	 to_char(c.repven_fecha,'$fechaCorta') fecha,c.repven_nro,
	 u1.usu_nombre,u2.usu_nombre,
	 to_char(c.repven_faudit,'$fechaLarga') fechaaudit,
	 c.repven_total_cash,c.repven_total_collect,
	 c.repven_total_credito,c.repven_total 
	 ,c.repven_id 
	 from reporte_venta c, oficina o, usuario u1, usuario u2
	 where o.ofi_id=c.ofi_id
	 and u1.usu_codigo=c.repven_por
	 and u2.usu_codigo=c.repven_uaudit
	 order by o.ofi_nombre asc,fecha desc,c.repven_nro asc
va;
		
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Oficina","Fecha","Nro.","Por","Usuario Sistema","Fecha Sistema","Total Efectivo","Total Collect","Total Crédito","Total","Modificar");
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
  	  define('tCodigo', 'string', 'Código',1,20,document);
  	  define('tNombre', 'string', 'Nombre',1,250,document);
  	  define('tCiruc', 'string', 'CI/RUC',1,30,document);
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
				array("etiqueta"=>"* Código","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* CI/RUC","nombre"=>"tCiruc","tipo_campo"=>"text","sql"=>"","valor"=>""),
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
  
  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)  
  {
  	$oAux=new c_reporte_venta($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Código","nombre"=>"tCodigo","tipo_campo"=>"nada","sql"=>"","valor"=>$oAux->repven_id),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->repven_nro),
				array("etiqueta"=>"* CI/RUC","nombre"=>"tCiruc","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->repven_fecha),
				array("etiqueta"=>"* Contacto","nombre"=>"tContacto","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->repven_por),
				array("etiqueta"=>"* Dirección","nombre"=>"tDireccion","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->repven_uaudit),
				array("etiqueta"=>"* Teléfono","nombre"=>"tTelefono","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->repven_faudit),
				array("etiqueta"=>"* Fax","nombre"=>"tFax","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->repven_total_cash),
				array("etiqueta"=>"* E-mail","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->repven_total_collect),
				array("etiqueta"=>"* Convenio","nombre"=>"seConvenio","tipo_campo"=>"select","sql"=>$this->oVerdadFalso->sqlSelect(),"valor"=>$oAux->repven_total_credito),
				array("etiqueta"=>"* Observacion","nombre"=>"tObservacion","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->repven_total)
				
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
  		  <!--<input type="button" name="AddB" value="A&ntilde;adirB" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  */
  
  function mostrar_dato()
  {
	echo "<hr>Class c_reporte_venta(conDb,usuario,oficina)<br>";
  	echo "repven_id:".$this->repven_id."<br>";
	echo "repven_nro:".$this->repven_nro."<br>";
	echo "repven_fecha:".$this->repven_fecha."<br>";
	echo "repven_por:".$this->repven_por."<br>";
	echo "repven_uaudit:".$this->repven_uaudit."<br>";
	echo "repven_faudit:".$this->repven_faudit."<br>";
	echo "repven_total_cash:".$this->repven_total_cash."<br>";
	echo "repven_total_collect:".$this->repven_total_collect."<br>";
	echo "repven_total_credito:".$this->repven_total_credito."<br>";
	echo "repven_total:".$this->repven_total."<br>";
	echo "ofi_id:".$this->ofi_id."<br>";
	echo "<hr>";
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
  
  function generarNro($usuario,$fecha)
  {
  	$vuser=trim($usuario);
  	$vFecha=$this->convertirFecha($fecha,$this->fechaCorta,$this->fechaNro);
  	
  	$sql=<<<va
  	select nvl(count(repven_id),0) from reporte_venta
  	where 
  	repven_uaudit='$vuser'
  	and repven_fecha=to_date('$vFecha','$this->fechaNro')
va;
	$rs=&$this->con->Execute($sql);
	$aux=$rs->fields[0];
	$vNro=$aux+1;
	
  	$res=$vuser.$this->separador.$vFecha.$this->separador.$vNro;
  	return($res);
  }
  
  function existe()
  {
  	$sql=<<<va
  	select repven_id 
  	from reporte_venta 
  	where repven_nro='$this->repven_nro' 
va;
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res=0;
	else 
	  $res=$rs->fields[0];
	return($res);
  } 
  
  function add()
  {  	
	  $this->repven_total_collect=0;
	  do 
	  {
	    $this->repven_nro=$this->generarNro($this->repven_uaudit,$this->repven_fecha);
	    $existe=$this->existe();
	  }while($existe);
	  
	  $sql=<<<va
	  insert into reporte_venta
	  (
	    repven_nro,repven_fecha,repven_por,
	    repven_uaudit,repven_faudit,
	    repven_total_cash,repven_total_collect,
	    repven_total_credito,repven_total,ofi_id 
	  )
	  values 
	  (
	    '$this->repven_nro',to_date('$this->repven_fecha','$this->fechaCorta'),'$this->repven_por',
	    '$this->repven_uaudit',to_date('$this->repven_faudit','$this->fechaLarga'),
	    '$this->repven_total_cash','$this->repven_total_collect',
	    '$this->repven_total_credito','$this->repven_total','$this->ofi_id'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->existe();
	  }
	  else 
	  {
	    $res=0;
	    $this->msg="Error al añadir dato!!!";
	  }
	
	return($res);				
  }
  
  function del($id)
  {
 	$sql=<<<va
 	delete from reporte_venta 
	where repven_id='$id' 
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
	$existe=$this->existe($id);
	return($exite);	
  }
  
  function info($id)
  {
  	$sql=<<<va
  	select 
  	repven_id,repven_nro,to_char(repven_fecha,'$this->fechaCorta'),repven_por,
	repven_uaudit,to_char(repven_faudit,'$this->fechaLarga'),repven_total_cash,repven_total_collect,
	repven_total_credito,repven_total,ofi_id   
  	from reporte_venta
  	where repven_id='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->repven_id="";
	  $this->repven_nro="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->repven_id=$rs->fields[0];
	  $this->repven_nro=$rs->fields[1];
	  $this->repven_fecha=$rs->fields[2];
	  $this->repven_por=$rs->fields[3];
	  $this->repven_uaudit=$rs->fields[4];
	  $this->repven_faudit=$rs->fields[5];
	  $this->repven_total_cash=$rs->fields[6];
	  $this->repven_total_collect=$rs->fields[7];
	  $this->repven_total_credito=$rs->fields[8];
	  $this->repven_total=$rs->fields[9];
	  $this->ofi_id=$rs->fields[10];
	}
	return($res);
  }
}
?>