<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_verdadfalso.php");
include_once("class/c_parametro.php");
include_once("class/c_manifiesto_embarque.php");

class c_manifiesto_desembarque
{
  var $manemb_id;
  var $mandes_fecharec;
  var $mandes_por;
  var $usu_audit;
  var $usu_faudit;
  var $mandes_finalizado;
  
  //objetos
  
  var $msg;
  var $separador;
  var $fechaCorta;
  var $fechaLarga;
  
  var $con;
  
  function c_manifiesto_desembarque($conDb,$usuario)
  {
	  $this->manemb_id=0;
	  $this->mandes_fecharec="";
	  $this->mandes_por="";
	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");
	  
	  $this->con=&$conDb;
	  
	  $this->msg="";
	  
	  $oParametro=new c_parametro($this->con);
	  $oParametro->info();
	  $this->fechaCorta=$oParametro->fechaCorta;
	  $this->fechaLarga=$oParametro->fechaLarga;
	  
	  $this->separador=":";
  }
    
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
	    $this->manemb_id=$dato[0];
	  	$this->mandes_fecharec=$dato[1];
	    $this->mandes_por=$dato[2];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=2;
	  if($ncampos==count($dato))
	  {
        $this->mandes_fecharec=$dato[0];
	    $this->mandes_por=$dato[1];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);  
  }
  
  /*function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo)
  {
    $param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <!--<input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">-->
  	  <br>
va;
  	
  	//sin del
  	$sql=<<<va
  	 select 
	 c.borrar,c.borrar,to_char(c.mandes_fecharec,'$this->fechaCorta') fecha,u.usu_nombre,
	 e1.est_nombre,e2.est_nombre,
	 c.manemb_id 
	 from manifiesto_desembarque c, usuario u, estacion e1, estacion e2  
	 where u.usu_codigo=c.mandes_por
	 and e1.est_codigo=c.borrar
	 and e2.est_codigo=c.borrar
	 order by fecha desc,c.borrar asc,c.borrar asc
va;
		
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Nro","Vuelo","Fecha","Por","Origen","Destino","Modificar");
	  $cad.=build_table_sindelCad($rs,false,$mainheaders,$titulo,
			'images/360/yearview.gif','50%','true',$destUpdate,$param_destino,"total",0);

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
  	  define('tNro', 'string', 'Nro.',1,15,document);
  	  define('tVuelo', 'string', 'Vuelo',1,20,document);
  	  define('tFecha', 'date', 'Fecha',10,10,document);
  	  define('sePor', 'string', 'Por',1,15,document);
  	  define('seOrigen', 'string', 'Origen',1,7,document);
  	  define('seDestino', 'string', 'Destino',1,7,document);
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
  
  
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$estOrigen)
  {
	$oEst=new c_estacion($this->con);
	$oUsu=new c_usuario($this->con);
	
  	$campo=array(
				array("etiqueta"=>"* Nro","nombre"=>"tNro","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Vuelo","nombre"=>"tVuelo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Por","nombre"=>"sePor","tipo_campo"=>"select","sql"=>$oUsu->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$oEst->sqlSelect(),"valor"=>$estOrigen),
				array("etiqueta"=>"* Destino","nombre"=>"seDestino","tipo_campo"=>"select","sql"=>$oEst->sqlSelect(),"valor"=>"")
				
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
  	$oAux=new c_manifiesto_desembarque($this->con);
  	$oAux->info($id);
  	
  	$oEst=new c_estacion($this->con);
	$oUsu=new c_usuario($this->con);
  	
	$campo=array(
				array("etiqueta"=>"* Nro","nombre"=>"tNro","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Vuelo","nombre"=>"tVuelo","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->mandes_fecharec),
				array("etiqueta"=>"* Por","nombre"=>"sePor","tipo_campo"=>"select","sql"=>$oUsu->sqlSelect(),"valor"=>$oAux->mandes_por),
				array("etiqueta"=>"* Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$oEst->sqlSelect(),"valor"=>$oAux->borrar),
				array("etiqueta"=>"* Destino","nombre"=>"seDestino","tipo_campo"=>"select","sql"=>$oEst->sqlSelect(),"valor"=>$oAux->borrar)
				
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
  		  <input type="button" name="doc" value="Documentos por Manifiesto" onClick="fOpenWindow('docxmanemb.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','DocxManEmb','450','550')">
  		  <input type="button" name="bulto" value="Bultos por Manifiesto" onClick="fOpenWindow('bulxmanemb.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','BulxManE','450','550')">
  		  <br>
  		  <input type="button" name="bultoBaja" value="Bajar Bultos de Manifiesto" onClick="fOpenWindow('bulxmanembqueda.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','BulxManE','450','550')">
  		  <input type="button" name="bultoFin" value="Finalizar Manifiesto" onClick="self.location='bulxmanembreal.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id'">
  		  
  		  <!--<input type="button" name="AddB" value="A&ntilde;adirB" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }*/
  
  
  function mostrar_dato()
  {
	echo "<hr>Class c_manifiesto_desembarque(conDb)<br>";
  	echo "manemb_id:".$this->manemb_id."<br>";
	echo "mandes_fecharec:".$this->mandes_fecharec."<br>";
	echo "mandes_por:".$this->mandes_por."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "mandes_finalizado:".$this->mandes_finalizado."<br>";
	echo "<hr>";
  }
  
  /*
  function id2cad($fecha,$vuelo)
  {
    $cad=$vuelo.$this->separador.$fecha;
    return($cad);
  }

  function cad2id($cad)
  {
    list($this->borrar,$this->mandes_fecharec)=explode($this->separador,$cad);
    return($cad);
  }*/
  
  function existe($vid)
  {
  	$oAux=new c_manifiesto_desembarque($this->con,$this->usu_audit);
  	$sql=<<<va
  	select manemb_id 
  	from manifiesto_desembarque 
  	where manemb_id=$vid
va;
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
    $sql="select nvl(max(to_number(manemb_id)),0) from manifiesto_desembarque";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  /*
  function validarClob()
  {
    if(strlen($this->borrar)==0)
	    $this->borrar="-";
  }
  */
  
  function add()
  {
  	$oAux=new c_manifiesto_desembarque($this->con,$this->usu_audit);
  	$existe=$oAux->existe($this->manemb_id);
	if($existe==0)
	{
	  //$this->validarClob();
	  $sql=<<<va
	  insert into manifiesto_desembarque
	  (
	    manemb_id,mandes_fecharec,mandes_por,
	    usu_audit,usu_faudit
	  )
	  values 
	  (
	    '$this->manemb_id',to_date('$this->mandes_fecharec','$this->fechaCorta'),
	    '$this->mandes_por',
	    '$this->usu_audit',to_date('$this->usu_faudit','$this->fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->existe($this->manemb_id);
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
 	delete from manifiesto_desembarque 
	where manemb_id='$id'
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
	$existe=$this->info($id);
	if($existe!=0)
	{
	  $sql=<<<va
	  UPDATE manifiesto_desembarque 
	  set 
	  usu_audit='$this->usu_audit',
	  usu_faudit='$this->usu_faudit',
	  WHERE manemb_id='$id'
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
  	manemb_id,to_char(mandes_fecharec,'$this->fechaCorta'),
	mandes_por,
	usu_audit,to_char(usu_faudit,'$this->fechaLarga'),
	mandes_finalizado  
  	from manifiesto_desembarque
  	where manemb_id='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->manemb_id="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->manemb_id=$rs->fields[0];
	  $this->mandes_fecharec=$rs->fields[1];
	  $this->mandes_por=$rs->fields[2];
	  $this->usu_audit=$rs->fields[3];
	  $this->usu_faudit=$rs->fields[4];
	  $this->mandes_finalizado=$rs->fields[5];
	}
	return($res);
  }
  
  function sqlSelect()
  {
  	$cad="select manemb_id a1,manemb_id a2 from manifiesto_desembarque order by a1 ";
  	return($cad);
  }
  
  function aFinalizado($id)
  {
  	$sql=<<<va
  	update manifiesto_desembarque
  	set mandes_finalizado='1'
  	where manemb_id='$id'
va;
	$rs=&$this->con->Execute($sql);
	return($id);
  }
  
  /*
  function enviodesembarque($id)
  {
  	$oAux=new c_manifiesto_desembarque($this->con,$this->usu_audit);
  	$oAux->info($id);
  	$sql=<<<va
  	select nvl(count(manemb_id),0) from manembxbultoreal
  	where manemb_id=$oAux->manemb_id
va;
	$rs=&$this->con->Execute($sql);
	$existeReal=$rs->fields[0];
	return($existeReal);
  }
  
  function recibiodesembarque($id)
  {
  	$oAux=new c_manifiesto_desembarque($this->con,$this->usu_audit);
  	$oAux->info($id);
  	$sql=<<<va
  	select nvl(count(manemb_id),0) from manifiesto_desembarque
  	where manemb_id=$oAux->manemb_id
va;
	$rs=&$this->con->Execute($sql);
	$existe=$rs->fields[0];
	return($existe);
  }
  */
}
?>