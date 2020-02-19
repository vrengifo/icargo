<?php
/**
 * Clase usada para el manejo de verdadfalso
 *
 */
class c_verdadfalso
{
  /**
   * atributo vf_valor
   *
   * @var string
   */
  var $vf_valor;
  /**
   * atributo vf_texto
   *
   * @var string
   */
  var $vf_texto;

  /**
   * Objeto que contiene la conexión a la base de datos
   *
   * @var object
   */
  var $con;
  
  //constructor
  /**
   * Constructor
   *
   * @param object $conDb
   * @return c_verdadfalso
   */
  function c_verdadfalso($conDb)
  {
	  $this->vf_valor="";
	  $this->vf_texto="";
	  
	  $this->con=&$conDb;
  }
  
  /**
   * Arma un sql para campos html select
   *
   * @return string
   */
  function sqlSelect()
  {
  	$sql="select vf_valor,vf_texto from verdadfalso order by vf_valor";
  	return($sql);
  }
  
  /**
   * Carga la información de SI o Verdadero
   *
   */
  function si()
  {
  	$this->vf_valor="1";
  	$this->vf_texto="Sí";
  }
    
  /**
   * Carga la información de NO o Falso
   *
   */
  function no()
  {
  	$this->vf_valor="0";
  	$this->vf_texto="No";
  }
  
  /**
   * Recupera información del objeto
   *
   * @param string $id
   * @return string
   */
  function info($id)
  {
  	$sql="select vf_valor,vf_texto from verdadfalso where vf_valor='$id' ";
  	$rs=&$this->con->Execute($sql);
  	if($rs->EOF)
  	{
  	  $res="0";
  	  $this->vf_valor="0";
  	  $this->vf_texto="No";
  	}
  	else 
  	{
  	  $res=$id;
  	  $this->vf_valor=$rs->fields[0];
  	  $this->vf_texto=$rs->fields[1];
  	}
  	return($res);
  }

}
?>