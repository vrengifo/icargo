<?php
//clase para ativu = Tipo de Vuelo
/**
 * Clase usada para el manejo de tipovuelo
 *
 */
class c_ativu
{
  var $tipvue_codigo;
  var $tipvue_descripcion;

  
  //constructor
  function c_ativu()
  {
	  $this->tipvue_codigo="";
	  $this->tipvue_descripcion="";	  
  }  
//funciones para cargar datos desde un arreglo, no se carga id
  function cargar_dato($dato)			
  {
    $ncampos=2;
	if($ncampos==count($dato))
	{
      $this->tipvue_codigo=$dato[0];
	  $this->tipvue_descripcion=$dato[1];      
	} 
  }
  
  function mostrar_dato()
  {
	echo $this->tipvue_codigo."<br>";
	echo $this->tipvue_descripcion."<br>";		
  }  
  
  function validar($con)
  {
  	$sql="select count(tipvue_codigo) cuantos from aplicacion where tipvue_codigo='$this->tipvue_codigo'";
	$rs = &$con->Execute($sql);
	$cuantos=$rs->fields[0];
	return ($cuantos);
  }  
  
  //funciones con base de datos
  function add($con)
  {
    $insertado=$this->validar($con);	
	if(!$insertado)
	{
	  $sql="insert into tipovuelo"
			." (tipvue_codigo,tipvue_descripcion)"
			." values ("
			."'$this->tipvue_codigo','$this->tipvue_descripcion')";
	  $rs = &$con->Execute($sql);
	}  
	  //echo "<br>$sql <br>";
	  //$this->mostrar_dato();		
	return($this->tipvue_codigo);				
  }
  function del($con,$id)
  {
 	$sql="delete from tipovuelo "
			."where tipvue_codigo='$id' ";
	$rs = &$con->Execute($sql);
	if($rs)
	  return $id;
	else
	  return 0;  		
  }
  function update($con,$id)
  {
	//$this->validar();	
  	$sql="UPDATE tipovuelo"
			." set tipvue_descripcion='$this->tipvue_descripcion' "
			." WHERE tipvue_codigo='$id'";
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