<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_estacion.php");
include_once("class/c_parametro.php");
/**
 * Clase usada para el manejo de kiloequivalenciaxest
 *
 */
class c_kiloequivalenciaxest
{
  /**
   * atributo est_codigoO
   *
   * @var string
   */
  var $est_codigoO;
  /**
   * atributo est_codigoD
   *
   * @var string
   */
  var $est_codigoD;
  /**
   * atributo kilequ_precio
   *
   * @var double
   */
  var $kilequ_precio;
  /**
   * atributo usu_audit
   *
   * @var string
   */
  var $usu_audit;
  /**
   * atributo usu_faudit
   *
   * @var date
   */
  var $usu_faudit;
  
  //objetos
  /**
   * objeto c_estacion
   *
   * @var c_estacion
   */
  var $oEstacion;
  /**
   * objeto c_parametro
   *
   * @var c_parametro
   */
  var $oParametro;
  
  /**
   * Almacena los mensajes de la clase
   *
   * @var string
   */
  var $msg;
  /**
   * separador de cadena identificadora
   *
   * @var string
   */
  var $separador;
  
  /**
   * valor por defecto
   *
   * @var double
   */
  var $valorDefault;
  
  /**
   * Objeto que contiene la conexión a la base de datos
   *
   * @var object
   */
  var $con;
  
  /**
   * Constructor
   *
   * @param object $conDb
   * @param string $usuario
   * @return c_kiloequivalenciaxest
   */
  function c_kiloequivalenciaxest($conDb,$usuario)
  {
  	  $this->est_codigoO="";
	  $this->est_codigoD="";
	  $this->kilequ_precio="";
	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");
	  
	  $this->con=&$conDb;
	  
	  $this->oEstacion=new c_estacion($this->con);
	  $this->oParametro=new c_parametro($this->con);
	  $this->oParametro->info();
	  
	  $this->msg="";
	  $this->separador=":";
	  
	  $this->valorDefault="0.50";
  }
    
  /**
   * Carga los datos a la clase desde un arreglo
   *
   * @param array $dato
   * @param string $iou ingresooupdate
   * @return int
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
	  	$this->kilequ_precio=$dato[2];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);  
  }

  /**
   * Crea el interfaz de Administración
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $destAdd
   * @param string $destUpdate
   * @param string $titulo
   * @return string
   */
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
  	$cadId=$this->armarSqlId("t.","t.");
  	$sql=<<<va
  	  select $cadId,
	  e1.est_nombre,e2.est_nombre,t.kilequ_precio,
	  $cadId
	  from kiloequivalenciaxest t, estacion e1, estacion e2 
	  where e1.est_codigo=t.est_codigoO
	  and e2.est_codigo=t.est_codigoD
	  order by e1.est_nombre,e2.est_nombre
va;
	//echo "<hr>$sql<hr>";
  	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Est. Origen","Est. Destino","Costo Kilo","Modificar");
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
  
  /**
   * Construye código javascript para validación de formularios
   *
   * @return string
   */
  function validaJs()
  {
  	//<script language="JavaScript" src="js/validation.js"></script>
  	$cad=<<<va
  	
  	<script language="javascript">
	function valida()
	{
  	  define('seOrigen', 'string', 'Est. Origen',1,7,document);
  	  define('seDestino', 'string', 'Est. Destino',1,7,document);
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
  
  /**
   * Crea el interfaz para añadir
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $titulo
   * @return string
   */
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo)
  {
	$campo=array(
				array("etiqueta"=>"* Est. Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Est. Destino","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Costo Kilo","nombre"=>"tPrecio","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
  
  /**
   * Crea el interfaz para actualización
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $titulo
   * @param string $id
   * @return string
   */
  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)  
  {
    $oAux=new c_kiloequivalenciaxest($this->con,$this->usu_audit);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Est. Origen","nombre"=>"seOrigen","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>$oAux->est_codigoO),
				array("etiqueta"=>"* Est. Destino","nombre"=>"seOficina","tipo_campo"=>"select","sql"=>$this->oEstacion->sqlSelect(),"valor"=>$oAux->est_codigoD),
				array("etiqueta"=>"* Costo Kilo","nombre"=>"tPrecio","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->kilequ_precio)
				
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
  
  /**
   * Muestra la información del objeto
   *
   */
  function mostrar_dato()
  {
	echo "<hr>Class c_kiloequivalenciaxest(conDb)<br>";
  	echo "est_codigoO:".$this->est_codigoO."<br>";
	echo "est_codigoD:".$this->est_codigoD."<br>";
	echo "kilequ_precio:".$this->kilequ_precio."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "<hr>";
  }
  
  /**
   * Crea una cadena identificadora a partir de los atributos identificadores
   *
   * @param string $estO
   * @param string $estD
   * @return string
   */
  function id2cad($estO,$estD)
  {
    $cad=$estO.$this->separador.$estD;
    return($cad);
  }
  
  /**
   * Carga datos a los atributos identificadores desde una cadena identificadora
   *
   * @param string $cad
   * @return string
   */
  function cad2id($cad)
  {
    list($this->est_codigoO,$this->est_codigoD)=explode($this->separador,$cad);
    return($cad);
  }
  
  /**
   * Arma una cadena sql del identificador
   *
   * @param string $prefijo1
   * @param string $prefijo2
   * @return string
   */
  function armarSqlId($prefijo1,$prefijo2)
  {
    $cad=$prefijo1."est_codigoO||'".$this->separador."'||".$prefijo2."est_codigoD";
    return($cad);
  }
  
  /**
   * Verifica si existe el dato
   *
   * @param string $vid
   * @return string
   */
  function existe($vid)
  {
  	$oAux=new c_kiloequivalenciaxest($this->con,$this->usu_audit);
  	$oAux->cad2id($vid);
  	$cadId=$this->armarSqlId("","");
    $sql=<<<va
    select $cadId 
    from kiloequivalenciaxest 
    where 
    (est_codigoO='$oAux->est_codigoO' and est_codigoD='$oAux->est_codigoD' )
    or
    (est_codigoO='$oAux->est_codigoD' and est_codigoD='$oAux->est_codigoO' )
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
    $sql="select nvl(max(to_number(est_codigoO)),0) from kiloequivalenciaxest";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  
  /**
   * Crea o actualiza un dato
   *
   * @return string
   */
  function creaoActualiza()
  {
  	$oAux=new c_kiloequivalenciaxest($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($this->est_codigoO,$this->est_codigoD);
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
  
  /**
   * Crea un nuevo registro
   *
   * @return string
   */
  function add()
  {
  	$oAux=new c_kiloequivalenciaxest($this->con,$this->usu_audit);
  	$cadId=$oAux->id2cad($this->est_codigoO,$this->est_codigoD);
    $existe=$this->existe($cadId);
    
    if($this->est_codigoO==$this->est_codigoD)
      $existe=$cadId;
    
	if($existe=="0")
	{
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
		
	  $sql=<<<va
	  insert into kiloequivalenciaxest
	  (
	    est_codigoO,est_codigoD,kilequ_precio,usu_audit,
	    usu_faudit
	  )
	  values 
	  (
	    '$this->est_codigoO','$this->est_codigoD','$this->kilequ_precio','$this->usu_audit',
	    to_date('$this->usu_faudit','$fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->est_codigoO,$this->est_codigoD);
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
  
  /**
   * Elimina un registro
   *
   * @param string $id
   * @return string
   */
  function del($id)
  {
 	$oAux=new c_kiloequivalenciaxest($this->con,$this->usu_audit);
  	$oAux->cad2id($id);
      
    $sql=<<<va
 	delete from kiloequivalenciaxest 
	where est_codigoO='$oAux->est_codigoO' and est_codigoD='$oAux->est_codigoD' 
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
  
  /**
   * Actualiza un registro
   *
   * @param string $id
   * @return string
   */
  function update($id)
  {
  	$existe=$this->existe($id);
	if($existe!="0")
	{
	  $oAux=new c_kiloequivalenciaxest($this->con,$oAux->usu_audit);
	  $oAux->cad2id($id);
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE kiloequivalenciaxest 
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
  
  /**
   * Recupera información del objeto
   *
   * @param string $id
   * @return string
   */
  function info($id)
  {
  	$oAux=new c_kiloequivalenciaxest($this->con, $this->usu_audit);
	$oAux->cad2id($id);
    
	$fechaCorta=$this->oParametro->fechaCorta;
	$fechaLarga=$this->oParametro->fechaLarga;
	
    $sql=<<<va
  	select 
  	est_codigoO,est_codigoD,kilequ_precio,usu_audit,
	to_char(usu_faudit,'$fechaLarga') 
  	from kiloequivalenciaxest
  	where 
  	(est_codigoO='$oAux->est_codigoO' and est_codigoD='$oAux->est_codigoD' )
  	or
  	(est_codigoO='$oAux->est_codigoD' and est_codigoD='$oAux->est_codigoO' )
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
	}
	return($res);
  }
  
  /**
   * Arma una sintaxis sql para obtener el identificador y su texto
   *
   * @return string
   */
  function sqlSelect()
  {
    $cadId=$this->armarSqlId("","");
    $cad="select $cadId a1,$cadId a2 from kiloequivalenciaxest order by a1";
    return($cad);
  }
  
  /**
   * Recupera el costo de envío desde una estación origen a una estación destino
   *
   * @param string $estO
   * @param string $estD
   * @return double
   */
  function recuperarCosto($estO,$estD)
  {
  	$oAux=new c_kiloequivalenciaxest($this->con,$this->usu_audit);
    $cadId=$oAux->id2cad($estO,$estD);
    $resInfo=$oAux->info($cadId);
    //echo"<hr>$resInfo<hr>";
    if($resInfo=="0")
    {
    	$res=$oAux->valorDefault;
    }
    else
      $res=$oAux->kilequ_precio;
      
    return($res);
  }
}
?>