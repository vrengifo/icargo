<?php
//clase para application o modules
/**
 * Usada para el manejo de módulos
 *
 */
class c_application
{
  /**
   * Identificador
   *
   * @var int
   */
  var $id_aplicacion;
  /**
   * Nombre del módulo
   *
   * @var string
   */
  var $nombre_aplicacion;
  /**
   * Path al archivo que se ejecutará como módulo
   *
   * @var string
   */
  var $file_aplicacion;
  /**
   * path a la imagen que se muestra como ícono del módulo
   *
   * @var string
   */
  var $imagen_aplicacion;

  
  //constructor
  /**
   * Constructor
   *
   * @return c_application
   */
  function c_application()
  {
	  $this->id_aplicacion=0;
	  $this->nombre_aplicacion="";	  
	  $this->file_aplicacion="";
	  $this->imagen_aplicacion="";
  }
//funciones para 
  /**
   * Cargar información a atributos desde un arreglo de datos, no se carga identificador
   *
   * @param array $dato
   */
  function cargar_dato($dato)			
  {
    $ncampos=3;
	if($ncampos==count($dato))
	{
	  $this->nombre_aplicacion=$dato[0];      
      $this->file_aplicacion=$dato[1];
      $this->imagen_aplicacion=$dato[2];
	} 
  }
  
  /**
   * Muestra la información cargadas en los atributos
   *
   */
  function mostrar_dato()
  {
	echo $this->id_aplicacion."<br>";
	echo $this->nombre_aplicacion."<br>";	
	echo $this->file_aplicacion."<br>";
	echo $this->imagen_aplicacion."<br>";	
  }  
  
  /**
   * Consulta si existe o no un módulo por su nombre
   *
   * @param mixed $con
   * @return int
   */
  function validar($con)
  {
  	$sql="select count(id_aplicacion) cuantos from aplicacion where nombre_aplicacion='$this->nombre_aplicacion'";
	$rs = &$con->Execute($sql);
	$cuantos=$rs->fields[0];
	return ($cuantos);
  }  
  
  //funciones con base de datos
  /**
   * Crea un nuevo módulo
   *
   * @param mixed $con
   * @return int
   */
  function add($con)
  {
    $insertado=$this->validar($con);	
	if(!$insertado)
	{
	  $sql="insert into aplicacion"
			." (nombre_aplicacion,file_aplicacion,imagen_aplicacion)"
			." values ("
			."'$this->nombre_aplicacion','$this->file_aplicacion','$this->imagen_aplicacion')";
	  $rs = &$con->Execute($sql);
	}  
	  //echo "<br>$sql <br>";
	  //$this->mostrar_dato();	
	  if($rs)
	  {
	    //recuperar el id del aplicaciones insertado, id_aplicacion
	    $sql_id="select id_aplicacion from aplicacion where "
	  		."nombre_aplicacion='$this->nombre_aplicacion'";
	  }
	  if($insertado)
	  {
	    //recuperar el id del aplicaciones insertado, id_aplicacion
	    $sql_id="select id_aplicacion from aplicacion where "
	  		."nombre_aplicacion='$this->nombre_aplicacion' ";	  
	  }		
	    //echo "<br>$sql_id<br>";
	    $rs1 = &$con->Execute($sql_id);
	    if(!$rs1->EOF)
		  $id_aplicacion=$rs1->fields[0];
	    else
	      $id_aplicacion="0";	
	    return  $id_aplicacion;		
		
  }
  /**
   * Elimina un módulo
   *
   * @param mixed $con
   * @param int $id
   * @return int
   */
  function del($con,$id)
  {
 	$sql="delete from aplicacion "
			."where id_aplicacion=$id ";
	$rs = &$con->Execute($sql);
	if($rs)
	  return $id;
	else
	  return 0;  		
  }
  /**
   * Actualiza la información del módulo
   *
   * @param mixed $con
   * @param int $id
   * @return int
   */
  function update($con,$id)
  {
	//$this->validar();	
  	$sql="UPDATE aplicacion"
			." set nombre_aplicacion='$this->nombre_aplicacion',file_aplicacion='$this->file_aplicacion',imagen_aplicacion='$this->imagen_aplicacion' "
			." WHERE id_aplicacion=$id";
//	echo "<br>$sql <br>";
//	$this->mostrar_dato();		
	$rs = &$con->Execute($sql);
	if($rs)
	  return $id;
	else
	  return 0;	
  }    
       
}
?>