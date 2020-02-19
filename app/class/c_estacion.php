<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_ciudad.php");
include_once("class/c_moneda.php");

/**
 * Clase usada para el manejo de estacion
 *
 */
class c_estacion
{
  /**
   * atributo est_codigo
   *
   * @var string
   */
  var $est_codigo;
  /**
   * atributo ciu_codigo
   *
   * @var string
   */
  var $ciu_codigo;
  /**
   * atributo mon_codigo
   *
   * @var string
   */
  var $mon_codigo;
  /**
   * atributo est_nombre
   *
   * @var string
   */
  var $est_nombre;
  /**
   * atributo est_ruc
   *
   * @var string
   */
  var $est_ruc;
  /**
   * atributo est_autsri
   *
   * @var string
   */
  var $est_autsri;
  
  //objetos
  /**
   * objeto c_ciudad
   *
   * @var c_ciudad
   */
  var $oCiudad;
  /**
   * objeto c_moneda
   *
   * @var c_moneda
   */
  var $oMoneda;
  
  /**
   * Almacena los mensajes de la clase
   *
   * @var string
   */
  var $msg;
  
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
   * @return c_estacion
   */
  function c_estacion($conDb)
  {
	  $this->est_codigo="";
	  $this->ciu_codigo="";
	  $this->mon_codigo="";
	  $this->est_nombre="";
	  $this->est_ruc="";
	  $this->est_autsri="";
	  
	  $this->con=&$conDb;
	  
	  $this->oCiudad=new c_ciudad($this->con);
	  $this->oMoneda=new c_moneda($this->con);
	  
	  $this->msg="";
	  $this->separador=":";	  
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
  	  $ncampos=6;
	  if($ncampos==count($dato))
	  {
        $this->est_codigo=$dato[0];
	    $this->ciu_codigo=$dato[1];
	    $this->mon_codigo=$dato[2];
	    $this->est_nombre=$dato[3];
	    $this->est_ruc=$dato[4];
	    $this->est_autsri=$dato[5];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=5;
	  if($ncampos==count($dato))
	  {
	    $this->ciu_codigo=$dato[0];
	    $this->mon_codigo=$dato[1];
	    $this->est_nombre=$dato[2];
	    $this->est_ruc=$dato[3];
	    $this->est_autsri=$dato[4];
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
  	
  	$sql=<<<va
  	  select e.est_codigo,
	  e.est_codigo,c.ciu_descripcion,m.mon_descripcion,e.est_nombre,
	  e.est_ruc,e.est_autsri,
	  e.est_codigo
	  from estacion e, ciudad c, moneda m 
	  where c.ciu_codigo=e.ciu_codigo
	  and m.mon_codigo=e.mon_codigo 
	  order by c.ciu_descripcion,e.est_codigo
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Est. Codigo","Ciudad","Moneda","Nombre","RUC","Aut Sri","Modificar");
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
  	  define('testCodigo', 'string', 'Id',1,7,document);
  	  define('seCiudad', 'string', 'Ciudad',1,3,document);
  	  define('seMoneda', 'string', 'Moneda',1,3,document);
  	  define('tNombre', 'string', 'Nombre',1,30,document);
  	  define('tRuc', 'string', 'Ruc',1,13,document);
  	  define('tAutSri', 'string', 'Autorización SRI',1,30,document);
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
				array("etiqueta"=>"* Id","nombre"=>"testCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Ciudad","nombre"=>"seCiudad","tipo_campo"=>"select","sql"=>$this->oCiudad->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Moneda","nombre"=>"seMoneda","tipo_campo"=>"select","sql"=>$this->oMoneda->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Ruc","nombre"=>"tRuc","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Autorización SRI","nombre"=>"tAutSri","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
    $oAux=new c_estacion($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Id","nombre"=>"testCodigo","tipo_campo"=>"nada","sql"=>"","valor"=>$oAux->est_codigo),
				array("etiqueta"=>"* Ciudad","nombre"=>"seCiudad","tipo_campo"=>"select","sql"=>$this->oCiudad->sqlSelect(),"valor"=>$oAux->ciu_codigo),
				array("etiqueta"=>"* Moneda","nombre"=>"seMoneda","tipo_campo"=>"select","sql"=>$this->oMoneda->sqlSelect(),"valor"=>$oAux->mon_codigo),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->est_nombre),
				array("etiqueta"=>"* Ruc","nombre"=>"tRuc","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->est_ruc),
				array("etiqueta"=>"* Autorización SRI","nombre"=>"tAutSri","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->est_autsri)
				
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
	echo "<hr>Class c_estacion(conDb)<br>";
  	echo "est_codigo:".$this->est_codigo."<br>";
	echo "est_codigo:".$this->est_codigo."<br>";
	echo "est_codigo:".$this->mon_codigo."<br>";
	echo "est_codigo:".$this->est_nombre."<br>";
	echo "est_codigo:".$this->est_ruc."<br>";
	echo "est_codigo:".$this->est_autsri."<br>";
	echo "<hr>";
  }
  
  /**
   * Verifica si existe el dato
   *
   * @param string $vid
   * @return string
   */
  function existe($vid)
  {
  	$sql="select est_codigo from estacion where est_codigo='$vid'";
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
    $sql="select nvl(max(to_number(est_codigo)),0) from estacion";
    $rs=&$this->con->Execute($sql);
    $res=$rs->fields[0];
    $nuevo=$res+1;
    return($nuevo);
  }
  */
  
  /**
   * Crea un nuevo registro
   *
   * @return string
   */
  function add()
  {
  	$existe=$this->existe($this->est_codigo);
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into estacion
	  (
	    est_codigo,ciu_codigo,mon_codigo,est_nombre,
	    est_ruc,est_autsri
	  )
	  values 
	  (
	    '$this->est_codigo','$this->ciu_codigo','$this->mon_codigo','$this->est_nombre',
	    '$this->est_ruc','$this->est_autsri'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->est_codigo;
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
 	$sql=<<<va
 	delete from estacion 
	where est_codigo='$id' 
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
	  $sql=<<<va
	  UPDATE estacion 
	  set 
	  ciu_codigo='$this->ciu_codigo',
	  mon_codigo='$this->mon_codigo',
	  est_nombre='$this->est_nombre',
	  est_ruc='$this->est_ruc',
	  est_autsri='$this->est_autsri',
	  WHERE est_codigo='$id'
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
  	$sql=<<<va
  	select 
  	est_codigo,ciu_codigo,mon_codigo,est_nombre,
	est_ruc,est_autsri 
  	from estacion
  	where est_codigo='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->est_codigo="";
	  $this->ciu_codigo="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res=$id;
	  $this->est_codigo=$rs->fields[0];
	  $this->ciu_codigo=$rs->fields[1];
	  $this->mon_codigo=$rs->fields[2];
	  $this->est_nombre=$rs->fields[3];
	  $this->est_ruc=$rs->fields[4];
	  $this->est_autsri=$rs->fields[5];
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
    $cad="select est_codigo,est_nombre from estacion order by est_nombre";
    return($cad);
  }

  /**
   * Arma una sintaxis sql para obtener el identificador y su texto excluyendo el $dato
   *
   * @return string
   */
  function sqlSelectExcluye($dato)
  {
    $cad="select est_codigo,est_nombre from estacion where est_codigo<>'$dato' order by est_nombre";
    return($cad);
  } 
  
  /**
   * Arma una sintaxis sql para obtener el identificador y su texto solo de $dato
   *
   * @return string
   */
  function sqlSelectSolo($dato)
  {
    $cad="select est_codigo,est_nombre from estacion where est_codigo='$dato' order by est_nombre";
    return($cad);
  }   
  
  /**
   * Arma una sintaxis sql para obtener el identificador y su texto, excluye el dato pasado
   *
   * @return string
   */
  function sqlSelectDocumento($dato)
  {
    $cad="select est_codigo,est_nombre from estacion where est_codigo<>'$dato' order by est_nombre";
    return($cad);
  }
}
?>