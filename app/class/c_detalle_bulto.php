<?php
include_once("adodb/tohtml.inc.php");
//include_once("class/c_bulto.php");
//include_once("class/c_detalledocumento.php");
include_once("class/c_parametro.php");
include_once("class/c_manifiesto_embarque.php");
class c_detalle_bulto
{
  var $detdoc_ref;
  var $bul_ref;
  var $usu_audit;
  var $usu_faudit;
  
  //referencia 
  var $manemb;
  
  //objetos

  var $msg;
  var $separador;
  var $fechaLarga;
  var $fechaCorta;
  
  var $con;

  function c_detalle_bulto($conDb,$usuario)
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
	echo "<hr>Class c_detalle_bulto(conDb,$this->usu_audit)<br>";
  	echo "detdoc_ref:".$this->detdoc_ref."<br>";
	echo "bul_ref:".$this->bul_ref."<br>";
	echo "usu_audit:".$this->usu_audit."<br>";
	echo "usu_faudit:".$this->usu_faudit."<br>";
	echo "<hr>";
  }

  function id2cad($detdoc,$bul)
  {
  	$cad=$detdoc.$this->separador.$bul;
  	return($cad);
  }
  
  function cad2id($cad)
  {
  	list($this->detdoc_ref,$this->bul_ref)=explode($this->separador,$cad);
  	return($cad);
  }
  
  function armarSqlId($prefijo1,$prefijo2)
  {
    $cad=$prefijo1."detdoc_ref||'".$this->separador."'||".$prefijo2."bul_ref";
    return($cad);
  }
  
  function bultoSimilar($bulto,$manifiesto)
  {
    //recuperar bultos del manifiesto
    $sql=<<<sql
    select bul_ref
    from manembxbulto
    where manemb_id=$manifiesto
sql;

    $rs=&$this->con->Execute($sql);
    $cad="";
    $sep=",";
    while(!$rs->EOF)
    {
      $cad.="'".$rs->fields[0]."'".$sep;
      $rs->MoveNext();
    }
    
    //recuperar bultos quedados de igual origen y destino
    $oManEmb=new c_manifiesto_embarque($this->con,$this->usu_audit);
    $oManEmb->info($manifiesto);
    $sql=<<<sql
    select distinct(bq.bul_ref)
    from manembxbultoqueda bq, manembxbultoreal br
    where
    bq.bul_ref<>br.bul_ref
    and bq.manemb_id in 
    (
      select distinct manemb_id from manifiesto_embarque
      where manemb_origen='$oManEmb->manemb_origen' and manemb_destino='$oManEmb->manemb_destino'       )    
sql;
    $rs=&$this->con->Execute($sql);
    while(!$rs->EOF)
    {
      $cad.="'".$rs->fields[0]."'".$sep; 
      $rs->MoveNext();   
    }
    $cad=substr($cad,0,(strlen($cad)-1));
    return($cad);
  }
  
  function existe($vid)
  {
  	$oAux=new c_detalle_bulto($this->con,$this->usu_audit);
  	$cadId=$oAux->armarSqlId("","");
  	$cadId1=$oAux->armarSqlId("d.","d.");
  	
  	$oAux->cad2id($vid);
  	
    /*$sql=<<<va
    select $cadId
    from detalle_bulto
    where detdoc_ref='$oAux->detdoc_ref'
    and bul_ref='$oAux->bul_ref'
va;*/

    /*$sql=<<<va
    select $cadId
    from detalle_bulto
    where detdoc_ref='$oAux->detdoc_ref'
    and bul_ref='$oAux->bul_ref' 
    and detdoc_ref not in 
    (
      select distinct $cadId1  
      from detalle_bulto d, manembxbulto me 
      where me.manemb_id=$this->manemb 
      and d.bul_ref=me.bul_ref
    )
va;*/
    
    $cadBul=$oAux->bultoSimilar($oAux->bul_ref,$this->manemb);
    $sql=<<<sql
    select $cadId 
    from detalle_bulto
    where 
    detdoc_ref='$oAux->detdoc_ref' 
    and bul_ref in 
    ($cadBul)
sql;
    
    //echo "<hr>$sql<hr>";
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else
	  $res=$rs->fields[0];
	return($res);
  }

  function add()
  {
  	$oAux=new c_detalle_bulto($this->con,$this->usu_audit);
  	$cad=$oAux->id2cad($this->detdoc_ref,$this->bul_ref);
  	$oAux->manemb=$this->manemb;
    $existe=$oAux->existe($cad);
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into detalle_bulto
	  (
	    bul_ref,detdoc_ref,
	    usu_audit,usu_faudit
	  )
	  values
	  (
	    '$this->bul_ref','$this->detdoc_ref',
	    '$this->usu_audit',to_date('$this->usu_faudit','$fechaLarga')
	  )
va;
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	  {
	    $res=$this->id2cad($cad,"");//ojo
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

  function del($id)
  {
    $oAux=new c_detalle_bulto($this->con,$this->usu_audit);
    $oAux->cad2id($id);
  	$sql=<<<va
 	delete from detalle_bulto
	where detdoc_ref='$oAux->detdoc_ref'
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
  
  function delAll($bulto)
  {
  	$sql=<<<va
 	delete from detalle_bulto
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

  /*
  function update($id)
  {
	$existe=$this->existe($id);
	if($existe!="0")
	{
	  $oAux=new c_detalle_bulto($this->con);
	  $oAux->cad2id($id);
	  
	  $fechaCorta=$this->oParametro->fechaCorta;
	  $fechaLarga=$this->oParametro->fechaLarga;
	  
	  $sql=<<<va
	  UPDATE detalle_bulto
	  set
	  borrar=to_date('$this->borrar','$fechaCorta'),
	  borrar=to_date('$this->borrar','$fechaCorta'),
	  iniNro='$this->iniNro',
	  borrar='$this->borrar',
	  borrar='$this->borrar',
	  usu_audit='$this->usu_audit',
	  usu_faudit=to_date('$this->usu_faudit','$fechaLarga'),
	  WHERE detdoc_ref='$oAux->detdoc_ref' and borrar='$oAux->borrar'
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
	$oAux=new c_detalle_bulto($this->con,$this->usu_audit);
	$oAux->cad2id($id);
	
  	$sql=<<<va
  	select
  	detdoc_ref,bul_ref,
  	usu_audit,to_char(usu_faudit,'$fechaLarga') 
  	from detalle_bulto
  	where detdoc_ref='$oAux->detdoc_ref'
  	and bul_ref='$oAux->bul_ref'
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
	}
	return($res);
  }

  function sqlSelect()
  {
    $oAux=new c_detalle_bulto($this->con,$this->usu_audit);
    $cadId=$oAux->armarSqlId("","");
  	$cad="select $cadId a1,$cadId a2 from detalle_bulto order by a1";
    return($cad);
  }

}
?>