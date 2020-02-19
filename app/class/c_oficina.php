<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_estacion.php");
include_once("class/c_verdadfalso.php");
class c_oficina
{
  var $ofi_id;
  var $est_codigo;
  var $ofi_nombre;
  var $ofi_direccion;
  var $ofi_telf;
  var $ofi_entrega;
  var $ofi_recepcion;
  var $ofi_principal;
  var $ofi_establecimiento;
  
  //objetos
  var $overdadFalso;
  var $oEstacion;
  
  var $msg;
  
  var $con;
  
  function c_oficina($conDb)
  {
	  $this->ofi_id="";
	  $this->est_codigo="";
	  $this->ofi_nombre="";
	  $this->ofi_direccion="";
	  $this->ofi_telf="";
	  $this->ofi_entrega="";
	  $this->ofi_recepcion="";
	  $this->ofi_principal="";
	  $this->ofi_establecimiento="";
	  
	  $this->con=&$conDb;
	  
	  $this->overdadFalso=new c_verdadfalso($this->con);
	  $this->oEstacion=new c_estacion($this->con);
	  
	  $this->msg="";
	  $this->separador=":";	  
  }
    
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=8;
	  if($ncampos==count($dato))
	  {
        $this->est_codigo=$dato[0];
	    $this->ofi_nombre=$dato[1];
	    $this->ofi_direccion=$dato[2];
	    $this->ofi_telf=$dato[3];
	    $this->ofi_entrega=$dato[4];
	    $this->ofi_recepcion=$dato[5];
	    $this->ofi_principal=$dato[6];
	    $this->ofi_establecimiento=$dato[7];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=8;
	  if($ncampos==count($dato))
	  {
        $this->est_codigo=$dato[0];
	    $this->ofi_nombre=$dato[1];
	    $this->ofi_direccion=$dato[2];
	    $this->ofi_telf=$dato[3];
	    $this->ofi_entrega=$dato[4];
	    $this->ofi_recepcion=$dato[5];
	    $this->ofi_principal=$dato[6];
	    $this->ofi_establecimiento=$dato[7];
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
  	 select o.ofi_id,
	 e.est_codigo,o.ofi_nombre,o.ofi_direccion,
	 o.ofi_telf,vf1.vf_texto,vf2.vf_texto,vf3.vf_texto,o.ofi_establecimiento 
	 ,o.ofi_id 
	 from oficina o, estacion e, verdadfalso vf1, verdadfalso vf2, verdadfalso vf3 
	 where e.est_codigo=o.est_codigo 
	 and vf1.vf_valor=o.ofi_entrega 
	 and vf2.vf_valor=o.ofi_recepcion 
	 and vf3.vf_valor=o.ofi_principal 
	 order by est_codigo,ofi_nombre
va;
		
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Est. Codigo","Nombre","Direccion","Teléfono","Entrega?","Recepción?","Principal?","Establecimiento","Modificar");
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
  	  //define('tofiId', 'string', 'Id',1,3,document);
  	  define('seEstacion', 'string', 'Estación',1,7,document);
  	  define('tofiNombre', 'string', 'Nombre',1,100,document);
  	  define('tofiDireccion', 'string', 'Dirección',1,250,document);
  	  define('tofiTelf', 'string', 'Telefono',1,250,document);
  	  define('seEntrega', 'string', 'Entrega?',1,1,document);
  	  define('seRecepcion', 'string', 'Recepción?',1,1,document);
  	  define('sePrincipal', 'string', 'Principal?',1,1,document);
  	  define('tEstablecimiento', 'string', 'Establecimiento',1,7,document);
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
				//array("etiqueta"=>"* Id","nombre"=>"tofiId","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Estación","nombre"=>"seEstacion","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tofiNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Dirección","nombre"=>"tofiDireccion","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Telefóno","nombre"=>"tofiTelf","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Entrega?","nombre"=>"seEntrega","tipo_campo"=>"select","sql"=>$this->overdadFalso->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Recepción?","nombre"=>"seRecepcion","tipo_campo"=>"select","sql"=>$this->overdadFalso->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Principal?","nombre"=>"sePrincipal","tipo_campo"=>"select","sql"=>$this->overdadFalso->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Establecimiento","nombre"=>"tEstablecimiento","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
  	$oAux=new c_oficina($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Id","nombre"=>"tofiId","tipo_campo"=>"nada","sql"=>"","valor"=>$oAux->ofi_id),
				array("etiqueta"=>"* Estación","nombre"=>"seEstacion","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>$oAux->est_codigo),
				array("etiqueta"=>"* Nombre","nombre"=>"tofiNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ofi_nombre),
				array("etiqueta"=>"* Dirección","nombre"=>"tofiDireccion","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ofi_direccion),
				array("etiqueta"=>"* Telefóno","nombre"=>"tofiTelf","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ofi_telf),
				array("etiqueta"=>"* Entrega?","nombre"=>"seEntrega","tipo_campo"=>"select","sql"=>$this->overdadFalso->sqlSelect(),"valor"=>$oAux->ofi_entrega),
				array("etiqueta"=>"* Recepción?","nombre"=>"seRecepcion","tipo_campo"=>"select","sql"=>$this->overdadFalso->sqlSelect(),"valor"=>$oAux->ofi_recepcion),
				array("etiqueta"=>"* Principal?","nombre"=>"sePrincipal","tipo_campo"=>"select","sql"=>$this->overdadFalso->sqlSelect(),"valor"=>$oAux->ofi_principal),
				array("etiqueta"=>"* Establecimiento","nombre"=>"tEstablecimiento","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ofi_establecimiento)
				
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
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function mostrar_dato()
  {
	echo "<hr>Class c_oficina(conDb)<br>";
  	echo "ofi_id:".$this->ofi_id."<br>";
	echo "est_codigo:".$this->est_codigo."<br>";
	echo "ofi_nombre:".$this->ofi_nombre."<br>";
	echo "ofi_direccion:".$this->ofi_direccion."<br>";
	echo "ofi_telf:".$this->ofi_telf."<br>";
	echo "ofi_entrega:".$this->ofi_entrega."<br>";
	echo "ofi_recepcion:".$this->ofi_recepcion."<br>";
	echo "ofi_principal:".$this->ofi_principal."<br>";
	echo "ofi_establecimiento:".$this->ofi_establecimiento."<br>";
	echo "<hr>";
  }
  
  function existe($vid)
  {
  	$sql="select ofi_id from oficina where ofi_id='$vid'";
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else 
	  $res=$rs->fields[0];
	return($res);
  } 
  
  function nuevoCodigo()
  { 
    $sql="select nvl(max(to_number(ofi_id)),0) from oficina";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  
  function add()
  {
	do 
	{
  	  $this->ofi_id=$this->nuevoCodigo();
  	  $existe=$this->existe($this->ofi_id);
	}while($existe!="0");
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into oficina
	  (
	    ofi_id,est_codigo,ofi_nombre,ofi_direccion,
	    ofi_telf,ofi_entrega,ofi_recepcion,ofi_principal,ofi_establecimiento
	  )
	  values 
	  (
	    '$this->ofi_id','$this->est_codigo','$this->ofi_nombre','$this->ofi_direccion',
	    '$this->ofi_telf','$this->ofi_entrega','$this->ofi_recepcion','$this->ofi_principal'
	    ,'$this->ofi_establecimiento'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->ofi_id;
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
 	delete from oficina 
	where ofi_id='$id' 
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
	  UPDATE oficina 
	  set 
	  est_codigo='$this->est_codigo',
	  ofi_nombre='$this->ofi_nombre',
	  ofi_direccion='$this->ofi_direccion',
	  ofi_telf='$this->ofi_telf',
	  ofi_entrega='$this->ofi_entrega',
	  ofi_recepcion='$this->ofi_recepcion',
	  ofi_principal='$this->ofi_principal',
	  ofi_establecimiento='$this->ofi_establecimiento' 
	  WHERE ofi_id='$id'
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
  	ofi_id,est_codigo,ofi_nombre,ofi_direccion,
	ofi_telf,ofi_entrega,ofi_recepcion,ofi_principal,ofi_establecimiento 
  	from oficina
  	where ofi_id='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->ofi_id="";
	  $this->est_codigo="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->ofi_id=$rs->fields[0];
	  $this->est_codigo=$rs->fields[1];
	  $this->ofi_nombre=$rs->fields[2];
	  $this->ofi_direccion=$rs->fields[3];
	  $this->ofi_telf=$rs->fields[4];
	  $this->ofi_entrega=$rs->fields[5];
	  $this->ofi_recepcion=$rs->fields[6];
	  $this->ofi_principal=$rs->fields[7];
	  $this->ofi_establecimiento=$rs->fields[8];
	}
	return($res);
  }
  
  function sqlSelect($oficina="")
  {
  	if($oficina=="")
  	  $cad="select ofi_id,ofi_nombre from oficina order by ofi_nombre ";
  	else 
  	  $cad="select ofi_id,ofi_nombre from oficina where ofi_id='$oficina' order by ofi_nombre ";
  	return($cad);
  }
}
?>