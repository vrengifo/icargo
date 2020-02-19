<?php
//clase para subapplication o submodules
/**
 * clase para el manejo de submódulos
 *
 */
class c_subapplication
{
  /**
   * atributo id_subaplicacion
   *
   * @var int
   */
  var $id_subaplicacion;
  /**
   * atributo id_aplicacion
   *
   * @var int
   */
  var $id_aplicacion;
  /**
   * atributo nombre_subaplicacion
   *
   * @var string
   */
  var $nombre_subaplicacion;
  /**
   * atributo file_subaplicacion
   *
   * @var string
   */
  var $file_subaplicacion;
  /**
   * atributo imagen_subaplicacion
   *
   * @var string
   */
  var $imagen_subaplicacion;

  
  //constructor
  /**
   * Constructor
   *
   * @return c_subapplication
   */
  function c_subapplication()
  {
	  $this->id_subaplicacion=0;
	  $this->id_aplicacion=0;
	  $this->nombre_subaplicacion="";	  
	  $this->file_subaplicacion="";
	  $this->imagen_subaplicacion="";
  }  
//funciones para cargar datos desde un arreglo, no se carga id
  /**
   * Cargar información a los atributos de la clase
   *
   * @param array $dato
   */
  function cargar_dato($dato)			
  {
    $ncampos=4;
	if($ncampos==count($dato))
	{
	  $this->id_aplicacion=$dato[0];
	  $this->nombre_subaplicacion=$dato[1];      
      $this->file_subaplicacion=$dato[2];
      $this->imagen_subaplicacion=$dato[3];
	} 
  }
  
  /**
   * Mostrar la información cargada a la clase
   *
   */
  function mostrar_dato()
  {
	echo $this->id_subaplicacion."<br>";
	echo $this->id_aplicacion."<br>";	
	echo $this->nombre_subaplicacion."<br>";	
	echo $this->file_subaplicacion."<br>";
	echo $this->imagen_subaplicacion."<br>";	
  }  
  
  /**
   * Verifica si el dato ya existe
   *
   * @param object $con
   * @return int
   */
  function validar($con)
  {
  	$sql="select count(id_subaplicacion) cuantos from subaplicacion where nombre_subaplicacion='$this->nombre_subaplicacion' "
		."and id_aplicacion=$this->id_aplicacion ";
	$rs = &$con->Execute($sql);
	$cuantos=$rs->fields[0];
	return ($cuantos);
  }  
  
  //funciones con base de datos
  /**
   * Inserta un submodulo
   *
   * @param object $con
   * @return int
   */
  function add($con)
  {
    $insertado=$this->validar($con);	
	if(!$insertado)
	{
	  $sql="insert into subaplicacion"
			." (id_aplicacion,nombre_subaplicacion,file_subaplicacion,imagen_subaplicacion)"
			." values ("
			."$this->id_aplicacion,'$this->nombre_subaplicacion','$this->file_subaplicacion','$this->imagen_subaplicacion')";
	  $rs = &$con->Execute($sql);
	}  
	  //echo "<br>$sql <br>";
	  //$this->mostrar_dato();	
	  if($rs)
	  {
	    //recuperar el id del aplicaciones insertado, id_subaplicacion
	    $sql_id="select id_subaplicacion from subaplicacion where "
	  		."nombre_subaplicacion='$this->nombre_subaplicacion' and file_subaplicacion='$this->file_subaplicacion' "
			."and id_aplicacion=$this->id_aplicacion ";
	  }
	  if($insertado)
	  {
	    //recuperar el id del aplicaciones insertado, id_subaplicacion
	    $sql_id="select id_subaplicacion from subaplicacion where "
	  		."nombre_subaplicacion='$this->nombre_subaplicacion' and id_aplicacion=$this->id_aplicacion ";	  
	  }		
	    //echo "<br>$sql_id<br>";
	    $rs1 = &$con->Execute($sql_id);
	    if(!$rs1->EOF)
		  $id_subaplicacion=$rs1->fields[0];
	    else
	      $id_subaplicacion="0";	
	    return  $id_subaplicacion;		
		
  }
  /**
   * Eliminar un submódulo
   *
   * @param object $con
   * @param int $id
   * @return int
   */
  function del($con,$id)
  {
 	$sql="delete from subaplicacion "
			."where id_subaplicacion=$id ";
	$rs = &$con->Execute($sql);
	if($rs)
	  return $id;
	else
	  return 0;  		
  }
  /**
   * Actualizar un submódulo
   *
   * @param object $con
   * @param int $id
   * @return int
   */
  function update($con,$id)
  {
	//$this->validar();	
  	$sql="UPDATE subaplicacion"
			." set id_aplicacion=$this->id_aplicacion,nombre_subaplicacion='$this->nombre_subaplicacion',file_subaplicacion='$this->file_subaplicacion',imagen_subaplicacion='$this->imagen_subaplicacion' "
			." WHERE id_subaplicacion=$id";
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