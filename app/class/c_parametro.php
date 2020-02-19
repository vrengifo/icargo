<?php
include_once("adodb/tohtml.inc.php");

/**
 * Clase usada para el manejo de Parametro
 *
 */
class c_parametro
{
  //en base
  /**
   * atributo mon_codigo
   *
   * @var string
   */
  var $mon_codigo;
  /**
   * atributo usu_admin
   *
   * @var string
   */
  var $usu_admin;
  /**
   * atributo peso_unidad
   *
   * @var string
   */
  var $peso_unidad;
  /**
   * atributo iva
   *
   * @var decimal
   */
  var $iva;
  /**
   * formato para fecha corta YYYY-MM-DD
   *
   * @var string
   */
  var $fechaCorta;
  /**
   * formato para fecha larga YYYY-MM-DD HH24:MI:SS
   *
   * @var string
   */
  var $fechaLarga;
  /**
   * atributo seguro
   *
   * @var decimal
   */
  var $seguro;
  /**
   * separador decimal
   *
   * @var string
   */
  var $sepDec;//separador decimal
  
  //objetos
  
  /**
   * usado para mensajes de la clase
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
   * @return c_parametro
   */
  function c_parametro($conDb)
  {
	  $this->mon_codigo="$";
	  $this->usu_admin="root";
	  $this->peso_unidad="K";
	  $this->iva="12";
	  $this->seguro="1";
	  
	  $this->con=&$conDb;
	  
	  $this->msg="";
	  
	  $this->fechaCorta="YYYY-MM-DD";
	  $this->fechaLarga="YYYY-MM-DD HH24:MI:SS";
	  $this->sepDec=".";
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
  	  $ncampos=2;
	  if($ncampos==count($dato))
	  {
        $this->mon_codigo=$dato[0];
	    $this->usu_admin=$dato[1];

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
	    $this->usu_admin=$dato[0];
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
  	  select mon_codigo,
	  mon_codigo,usu_admin,
	  mon_codigo
	  from moneda 
	  order by usu_admin
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
  	  define('tCodigo', 'string', 'Id',1,3,document);
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
				array("etiqueta"=>"* Id","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
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
    $oAux=new c_parametro($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Id","nombre"=>"tCodigo","tipo_campo"=>"nada","sql"=>"","valor"=>$oAux->mon_codigo),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->usu_admin)
				
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
	echo "<hr>Class c_parametro(conDb)<br>";
  	echo "mon_codigo:".$this->mon_codigo."<br>";
	echo "mon_codigo:".$this->mon_codigo."<br>";
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
  	$sql="select mon_codigo from moneda where mon_codigo='$vid'";
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
    $sql="select nvl(max(to_number(mon_codigo)),0) from moneda";
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
  	$existe=$this->existe($this->mon_codigo);
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into moneda
	  (
	    mon_codigo,usu_admin
	  )
	  values 
	  (
	    '$this->mon_codigo','$this->usu_admin'
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->mon_codigo;
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
 	delete from moneda 
	where mon_codigo='$id' 
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
	  UPDATE moneda 
	  set 
	  usu_admin='$this->usu_admin',
	  WHERE mon_codigo='$id'
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
   * @return string
   */
  function info()
  {
  	$sql=<<<va
  	select 
  	mon_codigo,usu_admin,peso_unidad,iva,fechacorta,fechalarga,seguro
  	from parametro
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->mon_codigo="";
	  $this->usu_admin="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else 
	{
	  $res="1";
	  $this->mon_codigo=$rs->fields[0];
	  $this->usu_admin=$rs->fields[1];
	  $this->peso_unidad=$rs->fields[2];
	  $this->iva=$rs->fields[3];
	  $this->fechaCorta=$rs->fields[4];
	  $this->fechaLarga=$rs->fields[5];
	  $this->seguro=$rs->fields[6];
	}
	return($res);
  }
  
  
  /**
   * Arma una sintaxis sql para el manejo de identificadores
   *
   */
  function sqlSelect()
  {
  }
}
?>