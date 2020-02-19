<?php
include_once("adodb/tohtml.inc.php");
//include_once("class/c_manifiesto_embarque.php");
//include_once("class/c_bulto.php");
include_once("class/c_parametro.php");
class c_manembxbultoqueda
{
  var $manemb_id;
  var $bul_ref;
  var $usu_audit;
  var $usu_faudit;
  
  //objetos

  var $msg;
  var $separador;
  var $fechaLarga;
  var $fechaCorta;
  
  var $con;

  function c_manembxbultoqueda($conDb,$usuario)
  {
	  $this->manemb_id="";

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
	echo "<hr>Class c_manembxbultoqueda(conDb,$this->usu_audit)<br>";
  	echo "manemb_id:".$this->manemb_id."<br>";
	echo "bul_ref:".$this->bul_ref."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "<hr>";
  }

  function id2cad($manemb,$bul)
  {
  	$cad=$manemb.$this->separador.$bul;
  	return($cad);
  }
  
  function cad2id($cad)
  {
  	list($this->manemb_id,$this->bul_ref)=explode($this->separador,$cad);
  	return($cad);
  }
  
  function armarSqlId($prefijo1,$prefijo2)
  {
    $cad=$prefijo1."manemb_id||'".$this->separador."'||".$prefijo2."bul_ref";
    return($cad);
  }
  
  function existe($vid)
  {
  	$oAux=new c_manembxbultoqueda($this->con,$this->usu_audit);
  	$cadId=$oAux->armarSqlId("","");
  	$oAux->cad2id($vid);
    $sql=<<<va
    select $cadId
    from manembxbultoqueda
    where manemb_id=$oAux->manemb_id
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
  	$oAux=new c_manembxbultoqueda($this->con,$this->usu_audit);
  	$cad=$oAux->id2cad($this->manemb_id,$this->bul_ref);
    $existe=$oAux->existe($cad);
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into manembxbultoqueda
	  (
	    bul_ref,manemb_id,
	    usu_audit,usu_faudit
	  )
	  values
	  (
	    '$this->bul_ref','$this->manemb_id',
	    '$this->usu_audit',to_date('$this->usu_faudit','$fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    //$res=$this->id2cad($cad);
	    $res=$cad;
	  }
	  else
	  {
	    $res="0";
	    $this->msg="Error al a�adir dato!!!";
	  }
	}
	else
	{
	  $res=$existe;
	  $this->msg="Dato ya existe!!!";
	}
	return($res);
  }

  function del($id)
  {
    $oAux=new c_manembxbultoqueda($this->con,$this->usu_audit);
    $oAux->cad2id($id);
  	$sql=<<<va
 	delete from manembxbultoqueda
	where manemb_id=$oAux->manemb_id
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
  
  function delAll($manemb)
  {
  	$sql=<<<va
 	delete from manembxbultoqueda
	where manemb_id=$manemb
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
  function update($id)
  {
	$existe=$this->existe($id);
	if($existe!="0")
	{
	  $oAux=new c_manembxbultoqueda($this->con);
	  $oAux->cad2id($id);
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE manembxbultoqueda
	  set
	  borrar=to_date('$this->borrar','$fechaCorta'),
	  borrar=to_date('$this->borrar','$fechaCorta'),
	  iniNro='$this->iniNro',
	  borrar='$this->borrar',
	  borrar='$this->borrar',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga'),
	  WHERE manemb_id='$oAux->manemb_id' and borrar='$oAux->borrar'
	  and borrar='$oAux->borrar'
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
  */

  function info($id)
  {
	$oAux=new c_manembxbultoqueda($this->con,$this->usu_audit);
	$oAux->cad2id($id);
	
  	$sql=<<<va
  	select
  	manemb_id,bul_ref,
  	usu_audit,to_char(usu_faudit,'$fechaLarga') 
  	from manembxbultoqueda
  	where manemb_id=$oAux->manemb_id
  	and bul_ref='$oAux->bul_ref'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->manemb_id="";
	  $this->msg="Dato no existe o problemas de conexi�n!!!";
	}
	else
	{
	  $res=$id;
	  $this->manemb_id=$rs->fields[0];
	  $this->bul_ref=$rs->fields[1];
	  $this->usu_audit=$rs->fields[2];
	  $this->usu_faudit=$rs->fields[3];
	}
	return($res);
  }

  function sqlSelect()
  {
    $oAux=new c_manembxbultoqueda($this->con,$this->usu_audit);
    $cadId=$oAux->armarSqlId("","");
  	$cad="select $cadId a1,$cadId a2 from manembxbultoqueda order by a1";
    return($cad);
  }

}
?>