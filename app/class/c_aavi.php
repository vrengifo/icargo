<?php
//clase para aavi = Avión
class c_aavi
{
  var $avi_id;
  var $avi_matricula;
  var $avi_peso_max;
  var $avi_vol_max;

  //constructor
  function c_aavi()
  {
	  $this->avi_id="";
	  $this->avi_matricula="N";
	  $this->avi_peso_max="0";
	  $this->avi_vol_max="0";
  }  
//funciones para cargar datos desde un arreglo, no se carga id
  function cargar_dato($dato)			
  {
    $ncampos=4;
	if($ncampos==count($dato))
	{
      $this->avi_id=$dato[0];
	  $this->avi_matricula=$dato[1];	  	  
	  $this->avi_peso_max=$dato[2];
	  $this->avi_vol_max=$dato[3];
	} 
  }
  
  function mostrar_dato()
  {
	echo $this->avi_id."<br>";
	echo $this->avi_matricula."<br>";		
  }  
  
  function inicializar()
  {    	
	if(strlen($this->avi_matricula)<=0)
	  $this->avi_matricula=$this->avi_id;	  
	if(strlen($this->avi_peso_max)<=0)
	  $this->avi_peso_max="0";
	if(strlen($this->avi_vol_max)<=0)
	  $this->avi_vol_max="0";	
  }
  
  function validar($con)
  {
  	$sql="select count(avi_id) cuantos from avion where avi_id='$this->avi_id'";
	$rs = &$con->Execute($sql);
	$cuantos=$rs->fields[0];
	return ($cuantos);
  }  
  
  //funciones con base de datos
  function add($con)
  {
    $this->inicializar();
	$insertado=$this->validar($con);	
	if(!$insertado)
	{
	  $sql="insert into avion"
			." (avi_id,avi_matricula,avi_peso_max,avi_vol_max)"
			." values ("
			."'$this->avi_id','$this->avi_matricula','$this->avi_peso_max','$this->avi_vol_max')";
	  $rs = &$con->Execute($sql);
	}  
	  //echo "<br>$sql <br>";
	  //$this->mostrar_dato();		
	return($this->avi_id);				
  }
  function del($con,$id)
  {
 	$sql="delete from avion "
			."where avi_id='$id' ";
	$rs = &$con->Execute($sql);
	if($rs)
	  return $id;
	else
	  return 0;  		
  }
  function update($con,$id)
  {
	//$this->validar();	
  	$sql="UPDATE avion"
			." set avi_peso_max='$this->avi_peso_max',avi_vol_max='$this->avi_vol_max' "
			." WHERE avi_id='$id'";
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