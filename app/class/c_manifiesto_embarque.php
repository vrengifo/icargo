<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_verdadfalso.php");
include_once("class/c_parametro.php");
include_once("class/c_estacion.php");
include_once("class/c_usuario.php");
/**
 * Gestor de Manifiesto de Embarque
 *
 */
class c_manifiesto_embarque
{
  /**
   * atributo manemb_id
   *
   * @var int
   */
  var $manemb_id;
  /**
   * atributo manemb_nro
   *
   * @var string
   */
  var $manemb_nro;
  /**
   * atributo manemb_vuelo
   *
   * @var string
   */
  var $manemb_vuelo;
  /**
   * atributo manemb_fecha
   *
   * @var date
   */
  var $manemb_fecha;
  /**
   * atributo manemb_por
   *
   * @var string
   */
  var $manemb_por;
  /**
   * atributo manemb_origen
   *
   * @var string
   */
  var $manemb_origen;
  /**
   * atributo manemb_destino
   *
   * @var string
   */
  var $manemb_destino;
  /**
   * atributo usu_audit
   *
   * @var string
   */
  var $usu_audit;
  /**
   * atributo usu_faudit
   *
   * @var datetime
   */
  var $usu_faudit;
  /**
   * atributo manemb_enviado
   *
   * @var string
   */
  var $manemb_enviado;
  /**
   * atributo manemb_finalizado
   *
   * @var string
   */
  var $manemb_finalizado;
  
  //objetos
  
  /**
   * mensajes (de error o warnings)
   *
   * @var string
   */
  var $msg;
  /**
   * separador usado para identificadores
   *
   * @var string
   */
  var $separador;
  /**
   * formato de fecha YYYY-MM-DD
   *
   * @var string
   */
  var $fechaCorta;
  /**
   * formato de fecha y hora YYYY-MM-DD HH24:MI:SS
   *
   * @var unknown_type
   */
  var $fechaLarga;
  
  /**
   * Conexión a base de datos
   *
   * @var int
   */
  var $con;
  
  function c_manifiesto_embarque($conDb,$usuario)
  {
	  $this->manemb_id=0;
	  $this->manemb_nro="";
	  $this->manemb_vuelo="";
	  $this->manemb_fecha="";
	  $this->manemb_por="";
	  $this->manemb_origen="";
	  $this->manemb_destino="";
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
  	  $ncampos=6;
	  if($ncampos==count($dato))
	  {
	    $this->manemb_nro=$dato[0];
	    $this->manemb_vuelo=$dato[1];
	    $this->manemb_fecha=$dato[2];
	    $this->manemb_por=$dato[3];
	    $this->manemb_origen=$dato[4];
	    $this->manemb_destino=$dato[5];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=6;
	  if($ncampos==count($dato))
	  {
        $this->manemb_nro=$dato[0];
	    $this->manemb_vuelo=$dato[1];
	    $this->manemb_fecha=$dato[2];
	    $this->manemb_por=$dato[3];
	    $this->manemb_origen=$dato[4];
	    $this->manemb_destino=$dato[5];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);  
  }
  
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$estacion)
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
  	
  	/*
  	$sql=<<<va
  	 select c.manemb_id,
	 c.manemb_nro,c.manemb_vuelo,to_char(c.manemb_fecha,'$this->fechaCorta') fecha,u.usu_nombre,
	 e1.est_origen,e2.est_nombre,
	 c.manemb_id 
	 from manifiesto_embarque c, usuario u, estacion e1, estacion e2  
	 where u.usu_codigo=c.manemb_por
	 and e1.est_codigo=c.manemb_origen
	 and e2.est_codigo=c.manemb_destino
	 order by fecha desc,c.manemb_vuelo asc,c.manemb_nro asc
va;
  	*/
  	//sin del
  	$sql=<<<va
  	 select 
	 c.manemb_nro,c.manemb_vuelo,to_char(c.manemb_fecha,'$this->fechaCorta') fecha,u.usu_nombre,
	 e1.est_nombre,e2.est_nombre,
	 c.manemb_id 
	 from manifiesto_embarque c, usuario u, estacion e1, estacion e2  
	 where u.usu_codigo=c.manemb_por
	 and e1.est_codigo=c.manemb_origen
	 and e2.est_codigo=c.manemb_destino
	 and c.manemb_origen='$estacion' 
	 and ( (nvl(c.manemb_enviado,'0')='0') or (c.manemb_enviado<>'1') ) 
	 order by fecha desc,c.manemb_vuelo asc,c.manemb_nro asc
va;
	//echo"<hr>$sql<hr>";
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
				array("etiqueta"=>"* Por","nombre"=>"sePor","tipo_campo"=>"select","sql"=>$oUsu->sqlSelectSolo($this->usu_audit),"valor"=>""),
				array("etiqueta"=>"* Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$oEst->sqlSelectSolo($estOrigen),"valor"=>$estOrigen),
				array("etiqueta"=>"* Destino","nombre"=>"seDestino","tipo_campo"=>"select","sql"=>$oEst->sqlSelectExcluye($estOrigen),"valor"=>"")
				
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
  	$oAux=new c_manifiesto_embarque($this->con,$this->usu_audit);
  	$oAux->info($id);
  	
  	$oEst=new c_estacion($this->con);
	$oUsu=new c_usuario($this->con);
  	
	$campo=array(
				array("etiqueta"=>"* Nro","nombre"=>"tNro","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->manemb_nro),
				array("etiqueta"=>"* Vuelo","nombre"=>"tVuelo","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->manemb_vuelo),
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"date","sql"=>"","valor"=>$oAux->manemb_fecha),
				array("etiqueta"=>"* Por","nombre"=>"sePor","tipo_campo"=>"select","sql"=>$oUsu->sqlSelect(),"valor"=>$oAux->manemb_por),
				array("etiqueta"=>"* Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$oEst->sqlSelect(),"valor"=>$oAux->manemb_origen),
				array("etiqueta"=>"* Destino","nombre"=>"seDestino","tipo_campo"=>"select","sql"=>$oEst->sqlSelect(),"valor"=>$oAux->manemb_destino)
				
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_updCad($this->con,'false',$titulo,'images/360/personwrite.gif',"100%",'true'
		,$campo,$campo_hidden,$id);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
<table border="0" width="100%">
  <tr>
    <td width="58%">
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  	</td>
  	<td width="42%">
	<table border="0">
	  <tr>
	    <td>
		  <div align="center">
		  <!--  
		    <input type="button" name="doc" value="Documentos por Manifiesto" onClick="fOpenWindow('docxmanemb.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','DocxManEmb','450','550')">
		  -->  
	<acronym title="Permite asignar los documentos (guías, facturas, etc.) que serán transportados en el Manifiesto de Embarque">
          <a href="#" onClick="fOpenWindow('docxmanemb.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','DocxManEmb','450','550')" >
	    <img src="images/boton/docxman.gif" border="0">
	  </a>
	</acronym>
		    		       
		    </div></td>
	  </tr>	  
	  <tr>
	    <td>
		  <div align="center">
		  <!--  
		  <input type="button" name="bulto" value="::::: Bultos por Manifiesto :::::" onClick="fOpenWindow('bulxmanemb.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','BulxManE','450','550')">
		  -->
	<acronym title="Permite gestionar los bultos (contenedores de carga) que se están cargando al Manifiesto de Embarque">
          <a href="#" onClick="fOpenWindow('bulxmanemb.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','BulxManE','450','550')" >
	    <img src="images/boton/bulxman.gif" border="0">
	  </a>
	</acronym>		  
		    </div></td>
	  </tr>
	  <tr>
	    <td>
		  <div align="center">
		    <!--
		    <input type="button" name="bultoBaja" value=" Bajar Bultos de Manifiesto" onClick="fOpenWindow('bulxmanembqueda.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','BulxManE','450','550')">
		    -->
	<acronym title="Permite identificar los bultos que por motivos de vuelo no van a ser transportados">
          <a href="#" onClick="fOpenWindow('bulxmanembqueda.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id','BulxManE','450','550')" >
	    <img src="images/boton/bajabulxman.gif" border="0">
	  </a>
	</acronym>		    
		    </div></td>
	  </tr>
	  <tr>
	    <td>
		  <div align="center">
		  <!--
		    <input type="button" name="bultoFin" value="Finalizar Manifiesto" onClick="self.location='bulxmanembreal.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id'">
		  -->
	<acronym title="Permite cerrar el manifiesto con el fin de no permitir más ingresos al mismo">
          <a href="#" onClick="self.location='bulxmanembreal.php?id_aplicacion=$id_aplicacion&id_subaplicacion=$id_subaplicacion&principal=$principal&idp=$id'" >
	    <img src="images/boton/finman.gif" border="0">
	  </a>
	</acronym>		    
		    </div></td>
	  </tr>
	</table>
	
	</td>
  </tr>
</table>	
  	
  		  <!--<input type="submit" name="Upd" value="Actualizar" onClick="return vValidar();">-->
  		  
  		  <!--<input type="button" name="AddB" value="A&ntilde;adirB" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
<!--
<p>
  Nota: <br>
<ul>
  <li>El botón <strong>"Documentos por Manifiesto"</strong> permite asignar los documentos (guías, facturas, etc.) que serán transportados en el Manifiesto de Embarque</li>
  <li>El bot&oacute;n <strong>&quot;Bultos por Manifiesto&quot;</strong> permite gestionar los bultos (contenedores de carga) que se est&aacute;n cargando al Manifiesto de Embarque </li>
  <li>El bot&oacute;n <strong>&quot;Bajar Bultos de Manifiesto&quot;</strong> permite identificar los bultos que por motivos de vuelo no van a ser transportados</li>
  <li>El bot&oacute;n <strong>&quot;Finalizar Manifiesto&quot;</strong> permite cerrar el manifiesto con el fin de no permitir m&aacute;s ingresos al mismo </li>
</ul>
</p>
-->
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  
  function mostrar_dato()
  {
	echo "<hr>Class c_manifiesto_embarque(conDb)<br>";
  	echo "manemb_id:".$this->manemb_id."<br>";
	echo "manemb_nro:".$this->manemb_nro."<br>";
	echo "manemb_vuelo:".$this->manemb_vuelo."<br>";
	echo "manemb_fecha:".$this->manemb_fecha."<br>";
	echo "manemb_por:".$this->manemb_por."<br>";
	echo "manemb_origen:".$this->manemb_origen."<br>";
	echo "manemb_destino:".$this->manemb_destino."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "manemb_enviado:".$this->manemb_enviado."<br>";
	echo "manemb_finalizado:".$this->manemb_finalizado."<br>";
	
	echo "<hr>";
  }
  
  function id2cad($fecha,$vuelo)
  {
    $cad=$vuelo.$this->separador.$fecha;
    return($cad);
  }

  function cad2id($cad)
  {
    list($this->manemb_vuelo,$this->manemb_fecha)=explode($this->separador,$cad);
    return($cad);
  }
  
  function existe($vid)
  {
  	$oAux=new c_manifiesto_embarque($this->con,$this->usu_audit);
  	$oAux->cad2id($vid);
  	$sql=<<<va
  	select manemb_id 
  	from manifiesto_embarque 
  	where manemb_fecha=to_date('$oAux->manemb_fecha','$this->fechaCorta')
  	and manemb_vuelo='$oAux->manemb_vuelo'
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
    $sql="select nvl(max(to_number(manemb_id)),0) from manifiesto_embarque";
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
  	$oAux=new c_manifiesto_embarque($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($this->manemb_fecha,$this->manemb_vuelo);
  	$existe=$this->existe($cadId);
	if($existe==0)
	{
	  //$this->validarClob();
	  $sql=<<<va
	  insert into manifiesto_embarque
	  (
	    manemb_nro,manemb_vuelo,manemb_fecha,
	    manemb_por,manemb_origen,manemb_destino,
	    usu_audit,usu_faudit
	  )
	  values 
	  (
	    '$this->manemb_nro','$this->manemb_vuelo',to_date('$this->manemb_fecha','$this->fechaCorta'),
	    '$this->manemb_por','$this->manemb_origen','$this->manemb_destino',
	    '$this->usu_audit',to_date('$this->usu_faudit','$this->fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->existe($cadId);
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
 	delete from manifiesto_embarque 
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
	  /*
	  	  $sql=<<<va
	  UPDATE manifiesto_embarque 
	  set 
	  manemb_nro='$this->manemb_nro',
	  manemb_vuelo='$this->manemb_vuelo',
	  manemb_fecha=to_date('$this->manemb_fecha','$this->fechaCorta'),
	  manemb_por='$this->manemb_por',
	  manemb_origen='$this->manemb_origen',
	  manemb_destino='$this->manemb_destino',
	  usu_audit='$this->usu_audit',
	  usu_faudit='$this->usu_faudit',
	  WHERE manemb_id='$id'
va;
	  */
	  $sql=<<<va
	  UPDATE manifiesto_embarque 
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
  	manemb_id,manemb_nro,manemb_vuelo,to_char(manemb_fecha,'$this->fechaCorta'),
	manemb_por,manemb_origen,manemb_destino,usu_audit,
	to_char(usu_faudit,'$this->fechaLarga'),
	nvl(manemb_enviado,'0'),nvl(manemb_finalizado,'0') 
  	from manifiesto_embarque
  	where manemb_id=$id
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->manemb_id="";
	  $this->manemb_nro="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->manemb_id=$rs->fields[0];
	  $this->manemb_nro=$rs->fields[1];
	  $this->manemb_vuelo=$rs->fields[2];
	  $this->manemb_fecha=$rs->fields[3];
	  $this->manemb_por=$rs->fields[4];
	  $this->manemb_origen=$rs->fields[5];
	  $this->manemb_destino=$rs->fields[6];
	  $this->usu_audit=$rs->fields[7];
	  $this->usu_faudit=$rs->fields[8];
	  $this->manemb_enviado=$rs->fields[9];
	  $this->manemb_finalizado=$rs->fields[10];
	}
	return($res);
  }
  
  function sqlSelect()
  {
  	$cad="select manemb_id,manemb_vuelo||':'||manemb_nro from manifiesto_embarque order by manemb_vuelo,manemb_nro ";
  	return($cad);
  }
  
  function envioEmbarque($id)
  {
  	$oAux=new c_manifiesto_embarque($this->con,$this->usu_audit);
  	$oAux->info($id);
  	$sql=<<<va
  	select nvl(count(manemb_id),0) from manembxbultoreal
  	where manemb_id=$oAux->manemb_id
va;
	$rs=&$this->con->Execute($sql);
	$existeReal=$rs->fields[0];
	return($existeReal);
  }
  
  function recibioEmbarque($id)
  {
  	$oAux=new c_manifiesto_embarque($this->con,$this->usu_audit);
  	$oAux->info($id);
  	$sql=<<<va
  	select nvl(count(manemb_id),0) from manifiesto_desembarque
  	where manemb_id=$oAux->manemb_id
va;
	$rs=&$this->con->Execute($sql);
	$existe=$rs->fields[0];
	return($existe);
  }
  
  function aEnviado($id)
  {
  	$sql=<<<va
  	update manifiesto_embarque
  	set manemb_enviado='1'
  	where manemb_id=$id
va;
	$rs=&$this->con->Execute($sql);
	return($id);
  }
  
  function aFinalizado($id)
  {
  	$sql=<<<va
  	update manifiesto_embarque
  	set manemb_finalizado='1'
  	where manemb_id=$id
va;
	$rs=&$this->con->Execute($sql);
	return($id);
  }
}
?>