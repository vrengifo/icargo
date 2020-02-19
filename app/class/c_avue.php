<?php
//clase para avue = Vuelo
class c_avue
{
  var $vue_codigo;
  var $tipvue_codigo;
  var $vue_ruta;
  var $vue_origen;
  var $vue_destino;

  
  //constructor
  function c_avue()
  {
	  $this->vue_codigo="";
	  $this->tipvue_codigo="N";
	  $this->vue_ruta="";
	  $this->vue_origen="";
	  $this->vue_destino="";
  }  
//funciones para cargar datos desde un arreglo, no se carga id
  function cargar_dato($dato)			
  {
    $ncampos=5;
	if($ncampos==count($dato))
	{
      $this->tipvue_codigo=$dato[0];
	  $this->vue_codigo=$dato[1];	  
	  $this->vue_ruta=$dato[2];
	  $this->vue_origen=$dato[3];
	  $this->vue_destino=$dato[4];
	} 
  }
  
  function mostrar_dato()
  {
	echo $this->vue_codigo."<br>";
	echo $this->tipvue_codigo."<br>";		
  }  
  
  function inicializar()
  {    
	
	if(strlen($this->vue_codigo)<=0)
	  $this->vue_codigo="0000";
	  
	if(strlen($this->vue_ruta)<=0)
	  $this->vue_ruta=$this->vue_origen."-".$this->vue_destino;	
  }
  
  function validar($con)
  {
  	$sql="select count(vue_codigo) cuantos from vuelo where vue_codigo='$this->vue_codigo'";
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
	  $sql="insert into vuelo"
			." (vue_codigo,tipvue_codigo,vue_ruta,vue_origen,vue_destino)"
			." values ("
			."'$this->vue_codigo','$this->tipvue_codigo','$this->vue_ruta','$this->vue_origen','$this->vue_destino')";
	  $rs = &$con->Execute($sql);
	}  
	  //echo "<br>$sql <br>";
	  //$this->mostrar_dato();		
	return($this->vue_codigo);				
  }
  function del($con,$id)
  {
 	$sql="delete from vuelo "
			."where vue_codigo='$id' ";
	$rs = &$con->Execute($sql);
	if($rs)
	  return $id;
	else
	  return 0;  		
  }
  function update($con,$id)
  {
	//$this->validar();	
  	$sql="UPDATE vuelo"
			." set vue_ruta='$this->vue_ruta',vue_origen='$this->vue_origen',vue_destino='$this->vue_destino' "
			." WHERE vue_codigo='$id'";
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