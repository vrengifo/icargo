<?php
include_once("adodb/tohtml.inc.php");
//include_once("class/c_bulto.php");
//include_once("class/c_detalledocumento.php");
include_once("class/c_parametro.php");
class c_des_paquete
{
  var $manemb_id; 
  var $detdoc_ref;
  var $bul_ref;
  var $ubicacion;
  var $usu_audit;
  var $usu_faudit;
  
  //objetos

  var $msg;
  var $separador;
  var $fechaLarga;
  var $fechaCorta;
  
  var $con;

  function c_des_paquete($conDb,$usuario)
  {
	  $this->detdoc_ref="";

	  $this->usu_audit=$usuario;
	  $this->usu_faudit=date("Y-m-d H:i:s");

	  $this->con=&$conDb;

	  $oParametro=new c_parametro($this->con);
	  $oParametro->info();
	  
	  $this->fechaCorta=$oParametro->fechaCorta;
	  $this->fechaLarga=$oParametro->fechaLarga;
	  
	  $this->msg="";
	  $this->separador=":";
  }

  /**
   * Carga los datos a la clase desde un arreglo
   *
   * @param array $dato
   * @param string $iou ingresooupdate
   * @return boolean
   */
  /*
  function cargar_dato($dato,$iou="i")
  {
  	if($iou=="i")
  	{
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
        $this->borrar=$dato[0];
	    $this->borrar=$dato[1];
	    $this->borrar=$dato[2];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
	    $this->borrar=$dato[0];
	    $this->borrar=$dato[1];
	    $this->borrar=$dato[2];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }
  */



  function mostrar_dato()
  {
	echo "<hr>Class c_des_paquete(conDb,$this->usu_audit)<br>";
  	echo "manemb_id:".$this->manemb_id."<br>";
	echo "detdoc_ref:".$this->detdoc_ref."<br>";
	echo "bul_ref:".$this->bul_ref."<br>";
	echo "ubicacion:".$this->ubicacion."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "<hr>";
  }

  function id2cad($manemb,$detdoc,$bul)
  {
  	$cad=$manemb.$this->separador.$detdoc.$this->separador.$bul;
  	return($cad);
  }
  
  function cad2id($cad)
  {
  	list($this->manemb_id,$this->detdoc_ref,$this->bul_ref)=explode($this->separador,$cad);
  	return($cad);
  }
  
  function armarSqlId($prefijo1,$prefijo2,$prefijo3)
  {
    $cad=$prefijo1."manemb_id||'".$this->separador."'||".$prefijo2."detdoc_ref||'".$this->separador."'||".$prefijo3."bul_ref";
    return($cad);
  }
  
  function existe($vid)
  {
  	$oAux=new c_des_paquete($this->con,$this->usu_audit);
  	$cadId=$oAux->armarSqlId("","","");
  	$oAux->cad2id($vid);
  	//$oAux->mostrar_dato();
    $sql=<<<va
    select $cadId
    from des_paquete
    where 
    manemb_id='$oAux->manemb_id'
    and detdoc_ref='$oAux->detdoc_ref'
    and bul_ref='$oAux->bul_ref'
va;
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else
	  $res=$rs->fields[0];
	return($res);
  }

  function add()
  {
  	//$oAux=new c_des_paquete($this->con,$this->usu_audit);
  	//$cad=$oAux->id2cad($this->manemb_id,$this->detdoc_ref,$this->bul_ref);
    //$existe=$oAux->existe($cad);
    $cad=$this->id2cad($this->manemb_id,$this->detdoc_ref,$this->bul_ref);
    $existe=$this->existe($cad);
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into des_paquete
	  (
	    manemb_id,bul_ref,detdoc_ref,
	    usu_audit,usu_faudit,ubicacion 
	  )
	  values
	  (
	    '$this->manemb_id','$this->bul_ref','$this->detdoc_ref',
	    '$this->usu_audit',to_date('$this->usu_faudit','$this->fechaLarga'),'$this->ubicacion' 
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($this->manemb_id,$this->detdoc_ref,$this->bul_ref);
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
  
  function update($id)
  {
	//$oAux=new c_des_paquete($this->con,$this->usu_audit);
  	//$cad=$oAux->id2cad($this->manemb_id,$this->detdoc_ref,$this->bul_ref);
    $existe=$this->existe($id);
	if($existe!="0")
	{
	  $fechaCorta=$this->fechaCorta;
	  $fechaLarga=$this->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE des_paquete
	  set
	  ubicacion='$this->ubicacion',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga') 
	  WHERE detdoc_ref='$this->detdoc_ref' 
	  and manemb_id='$this->manemb_id'
	  and bul_ref='$this->bul_ref'
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
  
  function crearActualizar()
  {
  	$oAux=new c_des_paquete($this->con,$this->usu_audit);
  	//$this->mostrar_dato();
  	$cad=$oAux->id2cad($this->manemb_id,$this->detdoc_ref,$this->bul_ref);
  	//echo "<hr>cad: $cad<hr>";
    $existe=$oAux->existe($cad);
    //echo "<hr>existe: $existe<hr>";
    //echo "<br>Talvez vació<br>";
    //$this->mostrar_dato();
    if($existe=="0")//crear
    {
      $res=$this->add();
    }
    else 
    {
      $res=$this->update($existe);
    }
    return($res);
  }

  function del($id)
  {
    $oAux=new c_des_paquete($this->con,$this->usu_audit);
    $oAux->cad2id($id);
  	$sql=<<<va
 	delete from des_paquete
	where manemb_id='$oAux->manemb_id'
	and detdoc_ref='$oAux->detdoc_ref'
	and bul_ref='$oAux->bul_ref'
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
  
  /*
  function delAll($bulto)
  {
  	$sql=<<<va
 	delete from des_paquete
	where bul_ref='$bulto'
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
  */
      
  

  function info($id)
  {
	$oAux=new c_des_paquete($this->con,$this->usu_audit);
	$oAux->cad2id($id);
	
  	$sql=<<<va
  	select
  	detdoc_ref,bul_ref,
  	usu_audit,to_char(usu_faudit,'$this->fechaLarga'),
  	manemb_id,ubicacion 
  	from des_paquete
  	where detdoc_ref='$oAux->detdoc_ref'
  	and bul_ref='$oAux->bul_ref'
  	and manemb_id='$oAux->manemb_id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->detdoc_ref="";
	  $this->msg="Dato no existe o problemas de conexión!!!";
	}
	else
	{
	  $res=$id;
	  $this->detdoc_ref=$rs->fields[0];
	  $this->bul_ref=$rs->fields[1];
	  $this->usu_audit=$rs->fields[2];
	  $this->usu_faudit=$rs->fields[3];
	  $this->manemb_id=$rs->fields[4];
	  $this->ubicacion=$rs->fields[5];
	}
	return($res);
  }

  function sqlSelect()
  {
    $oAux=new c_des_paquete($this->con,$this->usu_audit);
    $cadId=$oAux->armarSqlId("","");
  	$cad="select $cadId a1,detdoc_ref from des_paquete order by a1";
    return($cad);
  }

}
?>