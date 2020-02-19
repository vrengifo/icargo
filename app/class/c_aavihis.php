<?php
//clase para aavihis = Historial de Vuelos
class c_aavihis
{
  var $vuehis_id;
  var $avi_id;
  var $vue_codigo;
  var $vuehis_fecha;
  var $vue_peso_max;
  var $vue_vol_max;
  var $vuehis_despachado;
  var $vuehis_receptado;
  
  //constructor
  function c_aavihis()
  {
	  $this->vuehis_id=0;
	  $this->avi_id="";
	  $this->vue_codigo="";
	  $this->vuehis_fecha="";
	  $this->vue_peso_max="0";
	  $this->vue_vol_max="0";
  }  
//funciones para cargar datos desde un arreglo, no se carga id
  function cargar_dato($dato)			
  {
    $ncampos=5;
	if($ncampos==count($dato))
	{
      $this->avi_id=$dato[0];
	  $this->vue_codigo=$dato[1];
	  $this->vuehis_fecha=$dato[2];
	  $this->vue_peso_max=$dato[3];
	  $this->vue_vol_max=$dato[4];	  	  	  
	} 
  }
  
  function mostrar_dato()
  {
	echo $this->vuehis_id."<br>";
	echo $this->avi_id."<br>";		
  }  
  
  function validar_pesovol($con)
  {
    //recuperar del avión sus valores máximos
	$sql="select avi_peso_max,avi_vol_max from avion where avi_id='$this->avi_id' ";
	$rs = &$con->Execute($sql);
	$avipesomax=$rs->fields[0];
	$avivolmax=$rs->fields[1];  
	
	if(($this->vue_peso_max>$avipesomax)||(strlen($this->vue_peso_max)<=0))
	  $this->vue_peso_max=$avipesomax;
	  
	if(($this->vue_vol_max>$avivolmax)||(strlen($this->vue_vol_max)<=0))
	  $this->vue_vol_max=$avivolmax;  
  }
  
  function inicializar()
  {    			
	if(strlen($this->vuehis_fecha)<=0)
	  $this->vuehis_fecha=date("Y-m-d");
	$this->vuehis_despachado=0;  
	$this->vuehis_receptado=0;  
/*	  
	if(strlen($this->vue_peso_max)<=0)
	  $this->vue_peso_max=$avipesomax;	
	  
	if(strlen($this->vue_vol_max)<=0)
	  $this->vue_vol_max=$avivolmax;		  
*/	  
  }
  
  function validar($con)
  {
  	$sql="select count(vuehis_id) cuantos from vuelo_historial where avi_id='$this->avi_id' and vue_codigo='$this->vue_codigo' "
		."and to_char(vuehis_fecha,'YYYY-MM-DD')='$this->vuehis_fecha' ";
	$rs = &$con->Execute($sql);
	$cuantos=$rs->fields[0];
	return ($cuantos);
  }  
  
  //funciones con base de datos
  function add($con)
  {
    $this->inicializar($con);
	$this->validar_pesovol($con);
	$insertado=$this->validar($con);	
	if(!$insertado)
	{
	  $sql="insert into vuelo_historial"
			." (avi_id,vue_codigo,vuehis_fecha,vue_peso_max,vue_vol_max,vuehis_despachado,vuehis_receptado)"
			." values ("
			."'$this->avi_id','$this->vue_codigo',to_date('$this->vuehis_fecha','YYYY-MM-DD'),'$this->vue_peso_max','$this->vue_vol_max','$this->vuehis_despachado','$this->vuehis_receptado')";
	  $rs = &$con->Execute($sql);	  
	}
	//recuperar el id
	$sql1="select vuehis_id from vuelo_historial "
		."where avi_id='$this->avi_id' and vue_codigo='$this->vue_codigo' "
		."and to_char(vuehis_fecha,'YYYY-MM-DD')='$this->vuehis_fecha' ";	  
	$rs1= &$con->Execute($sql1);
	$vuehis_id=$rs1->fields[0];
	  //echo "<br>$sql <br>";
	  //$this->mostrar_dato();		
	return($vuehis_id);				
  }
  function del($con,$id)
  {
 	$sql="delete from vuelo_historial "
			."where vuehis_id=$id ";
	$rs = &$con->Execute($sql);
	if($rs)
	  return $id;
	else
	  return 0;  		
  }
  function update($con,$id)
  {
	$this->validar_pesovol($con);	
  	$sql="UPDATE vuelo_historial"
			." set avi_id='$this->avi_id',vue_codigo='$this->vue_codigo',vuehis_fecha=to_date('$this->vuehis_fecha','YYYY-MM-DD'),"
			."vue_peso_max='$this->vue_peso_max',vue_vol_max='$this->vue_vol_max' "
			." WHERE vuehis_id=$id";
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