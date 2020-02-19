<?php
include_once("adodb/tohtml.inc.php");
/**
 * Clase usada para manejar la tabla usuario
 *
 */
class c_usuario
{
  /**
   * atributo usu_codigo
   *
   * @var string
   */
  var $usu_codigo;
  /**
   * atributo usu_clave
   *
   * @var string
   */
  var $usu_clave;
  /**
   * atributo usu_nombre
   *
   * @var string
   */
  var $usu_nombre;
  /**
   * atributo usu_email
   *
   * @var string
   */
  var $usu_email;
  /**
   * atributo perfil_id
   *
   * @var int
   */
  var $perfil_id;
  
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
   * @return c_usuario
   */
  function c_usuario($conDb)
  {
	  $this->usu_codigo="";
	  $this->usu_clave="";	  
	  $this->usu_nombre="";
	  $this->usu_email="";
	  
	  $this->con=&$conDb;
  }  

  /**
   * Carga los datos a la clase desde un arreglo
   *
   * @param array $dato
   * @return int
   */
  function cargar_dato($dato)			
  {
    $ncampos=4;
	if($ncampos==count($dato))
	{
      $this->usu_codigo=$dato[0];
	  $this->usu_clave=$dato[1];      
      $this->usu_nombre=$dato[2];
      //$this->usu_email=$dato[3];
      $this->perfil_id=$dato[3];
	} 
  }
  
  /**
   * Muestra la información del objeto
   *
   */
  function mostrar_dato()
  {
	echo "<hr>Class c_usuario(conDb) <br>";
  	echo "usu_codigo:".$this->usu_codigo."<br>";
	echo "usu_clave:".$this->usu_clave."<br>";	
	echo "usu_nombre:".$this->usu_nombre."<br>";
	echo "perfil_id:".$this->perfil_id."<br>";
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
  	$sql=<<<va
  	select usu_codigo from usuario where usu_codigo='$vid'
va;
	$rs = $this->con->Execute($sql);
	if($rs->EOF)
	  $res=0;
	else 
	  $res=$rs->fields[0];  
	return($res);
  }
  
  /**
   * Crea un nuevo registro
   *
   * @return string
   */
  function add()
  {
    $existe=$this->existe($this->usu_codigo);
	if(!$existe)
	{
	  $sql=<<<va
	  insert into usuario
	  (usu_codigo,usu_clave,usu_nombre,perfil_id)
	  values 
	  (
	   '$this->usu_codigo','$this->usu_clave','$this->usu_nombre','$this->perfil_id'
	  )
va;

	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->usu_codigo;
	    $this->cargarModulo($this->usu_codigo,$this->perfil_id);
	  }
	  else 
	    $res=0;  
	}
	else 
	{
	  $res=$existe;	
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
 	delete from usuario 
	where usu_codigo='$id' 
va;
	$rs = &$this->con->Execute($sql);
	if($rs)
	  $res=$id;
	else
	  $res=0;
	return($res);
  }
  
  /**
   * Actualiza un registro
   *
   * @param string $vid
   * @return string
   */
  function update($vid)
  {
	$existe=$this->existe($vid);
	if($existe)
	{
  	  $sql=<<<va
  	  update usuario
	  set usu_clave='$this->usu_clave',usu_nombre='$this->usu_nombre',
	  perfil_id='$this->perfil_id' 
	  where usu_codigo='$vid'
va;
	  $rs=&$this->con->Execute($sql);
	  $this->cargarModulo($vid,$this->perfil_id);
	  $res=$vid;
	}
	else 
	  $res=$vid;
	return($res);
  }
  
  function cargarModulo($usuario,$perfil)
  {
  	$sqlDel="delete from usuario_aplicacion where usu_codigo='$usuario' ";
  	$rsDel=&$this->con->Execute($sqlDel);
  	
  	$sql=<<<mya
  	insert into usuario_aplicacion (usu_codigo,id_aplicacion)
  	select '$usuario',id_aplicacion
  	from perfil_aplicacion 
  	where perfil_id=$perfil
mya;
	$rs=&$this->con->Execute($sql);
  	return($usuario);
  }

  /**
   * Actualiza la clave del usuario
   *
   * @param string $vuser
   * @param string $npass
   * @return int
   */
  function cambiar_clave($vuser,$npass)
  {
    $sql=<<<va
    update usuario set usu_clave='$npass' where usu_codigo='$vuser' 
va;
	$rs=&$this->con->Execute($sql);
	if($rs)
	  $res=1;
	else
	  $res=0;
	return($res);    
  }

  /**
   * Verifica si un usuario a ingresado bien su username y password
   *
   * @param string $vuser
   * @param string $vpass
   * @return string
   */
  function verificar_usuario($vuser,$vpass)
  {
    $sql=<<<va
    select usu_codigo from usuario where usu_codigo='$vuser' and usu_clave='$vpass' 
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
    {
      $res="0";
    }
    else 
    {  
      $res=$rs->fields[0]; 	
    }
    return($res);
  }
  
  /**
   * Recupera información del objeto
   *
   * @param string $vuser
   * @return string
   */
  function info($vuser)
  {
    $sql=<<<va
    select usu_codigo,usu_clave,usu_nombre,perfil_id 
    from usuario 
    where usu_codigo='$vuser' 
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $this->usu_codigo="";
	  $this->usu_clave="";
	  $this->usu_nombre="";
	  $this->perfil_id="";
	}
	else 
	{
	  $this->usu_codigo=$rs->fields[0];
	  $this->usu_clave=$rs->fields[1];
	  $this->usu_nombre=$rs->fields[2];
	  $this->perfil_id=$rs->fields[3];
	}
	return($this->usu_codigo);	
  }
  
  /**
   * Arma una sintaxis sql para el manejo de identificadores
   *
   * @return string
   */
  function sqlSelect()
  {
    $cad="select usu_codigo,usu_nombre from usuario order by usu_codigo,usu_nombre";
    return($cad);
  }

  /**
   * Arma una sintaxis sql para el manejo de identificadores de usuario mostrando solo $dato
   *
   * @return string
   */
  function sqlSelectSolo($dato)
  {
    $cad="select usu_codigo,usu_nombre from usuario where usu_codigo='$dato' order by usu_codigo,usu_nombre";
    return($cad);
  }
  
  //interfaz
  /**
   * Crea el interfaz para añadir
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $destAdd
   * @param string $destUpdate
   * @param string $titulo
   * @param string $oficina
   * @return string
   */
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$oficina="")
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
  	//$cadId=$this->armarSqlId("o.","t.","s.");
  	
  	//$fechaCorta=$this->oParametro->fechaCorta;
	//$fechaLarga=$this->oParametro->fechaLarga;
  	
	  $sql=<<<va
	  	  select usu_codigo u1,usu_codigo u2,usu_nombre,usu_codigo u3 
		  from usuario 
		  order by usu_codigo,usu_nombre
va;
  	$rs= &$this->con->Execute($sql);
    if ($rs->EOF)
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");
	  $mainheaders=array("Eliminar","Usuario","Nombre","Modificar");
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

}
?>
