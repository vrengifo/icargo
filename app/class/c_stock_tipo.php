<?php
include_once("adodb/tohtml.inc.php");
include_once("class/c_verdadfalso.php");
class c_stock_tipo
{
  var $stotip_id;
  var $stotip_nombre;
  var $stotip_visual;//si tiene html o no
  var $stotip_formato;//DOC documentos o CB cod barra
  var $stotip_convenio;//si para el documento se necesita ser cliente con convenio o no
  
  //objetos
  var $oVerdadFalso;
  
  var $msg;
  
  var $con;
  
  function c_stock_tipo($conDb)
  {    
      $this->stotip_id="";
	  $this->stotip_nombre="";
	  $this->stotip_visual="0";
	  $this->stotip_formato="DOC";
	  $this->stotip_convenio="0";
	  
	  $this->con=&$conDb;
	  $this->oVerdadFalso=new c_verdadfalso($this->con);
	  
	  $this->msg="";
  }
    
  function cargar_dato($dato)			
  {
	if($iou=="i")
  	{
  	  $ncampos=5;
	  if($ncampos==count($dato))
	  {
        $this->stotip_id=$dato[0];
	    $this->stotip_nombre=$dato[1];
	    $this->stotip_visual=$dato[2];
	    $this->stotip_formato=$dato[3];
	    $this->stotip_convenio=$dato[4];

	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=4;
	  if($ncampos==count($dato))
	  {
	    $this->stotip_nombre=$dato[0];
	    $this->stotip_visual=$dato[1];
	    $this->stotip_formato=$dato[2];
	    $this->stotip_convenio=$dato[3];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }
  
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  <br>
va;
  	
  	$sql=<<<va
  	  select st.stotip_id a1,
	  st.stotip_id a2,st.stotip_nombre,vf.vf_texto,st.stotip_formato,vfconvenio.vf_texto,
	  st.stotip_id a3
	  from stock_tipo st, verdadfalso vf, verdadfalso vfconvenio 
	  where vf.vf_valor=st.stotip_visual
	  and vfconvenio.vf_valor=st.stotip_convenio
	  order by a1,st.stotip_nombre
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Del","Código","Nombre","Visual","Formato Imp","Convenio Cliente","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/360/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
	  //variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
	  $cextra="id_aplicacion|id_subaplicacion|principal";
	}
	
	$cad.=<<<va
	  <input type="hidden" name="cextra" value="$cextra">
	</form>
va;
	return($cad);
  }
  
  function validaJs()
  {
  	//<script language="JavaScript" src="js/validation.js"></script>
  	$cad=<<<va
  	
  	<script language="javascript">
	function valida()
	{
  	  define('tCodigo', 'string', 'Id',1,3,document);
  	  define('tNombre', 'string', 'Nombre',1,30,document);
  	  define('tFormato', 'string', 'Nombre',1,3,document);
  	}
  	
  	function vValidar()
  	{
  	  var res;
  	  res=validate();
  	  return(res);
  	}
  	
  	function vValidarB(forma,urldestino)
  	{
  	  var res;
  	  res=validate();
  	  if(res) 
  	  {
  	    cambiar_action(forma,urldestino);
  	    forma.submit();
  	  }  	  
  	}
  	
  	</script>
va;
	return($cad);
  }  
  
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo)
  {
	$campo=array(
				array("etiqueta"=>"* Id","nombre"=>"tCodigo","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Visual","nombre"=>"seVisual","tipo_campo"=>"select","sql"=>$this->oVerdadFalso->sqlSelect(),"valor"=>""),
				array("etiqueta"=>"* Formato","nombre"=>"tFormato","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Convenio Cliente","nombre"=>"seConvenio","tipo_campo"=>"select","sql"=>$this->oVerdadFalso->sqlSelect(),"valor"=>"")
				
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/360/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Add" value="Añadir" onClick="return vValidar();">
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)  
  {
    $oAux=new c_stock_tipo($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Id","nombre"=>"tCodigo","tipo_campo"=>"nada","sql"=>"","valor"=>$oAux->stotip_id),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->stotip_nombre),
				array("etiqueta"=>"* Visual","nombre"=>"seVisual","tipo_campo"=>"select","sql"=>$this->oVerdadFalso->sqlSelect(),"valor"=>$oAux->stotip_visual),
				array("etiqueta"=>"* Formato","nombre"=>"tFormato","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->stotip_formato),
				array("etiqueta"=>"* Convenio Cliente","nombre"=>"seConvenio","tipo_campo"=>"select","sql"=>$this->oVerdadFalso->sqlSelect(),"valor"=>$oAux->stotip_convenio)
				
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_updCad($this->con,'false',$titulo,'images/360/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden,$id);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Upd" value="Actualizar" onClick="return vValidar();">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function mostrar_dato()
  {
	echo "<hr>Class c_stock_tipo(conDb)<br>";
  	echo "stotip_id:".$this->stotip_id."<br>";
	echo "stotip_nombre:".$this->stotip_nombre."<br>";
	echo "stotip_visual:".$this->stotip_visual."<br>";
	echo "stotip_formato:".$this->stotip_formato."<br>";
	echo "stotip_convenio:".$this->stotip_convenio."<br>";
	echo "<hr>";
  }
  
  function existe($vid)
  {
  	$sql="select stotip_id from stock_tipo where stotip_id='$vid'";
	$rs = &$this->con->Execute($sql);
	if($rs->EOF)
	  $res="0";
	else 
	  $res=$rs->fields[0];
	return($res);
  }
  
  function add()
  {
	$existe=$this->existe($this->stotip_id);	
	if($existe=="0")
	{
	  $sql=<<<va
	  insert into stock_tipo
	  (stotip_id,stotip_nombre,stotip_visual,stotip_formato,stotip_convenio)
	  values 
	  ('$this->stotip_id','$this->stotip_nombre','$this->stotip_visual','$this->stotip_formato','$this->stotip_convenio') 
va;

	  //echo "<hr>$sql<hr>";
	  $rs = &$this->con->Execute($sql);
	  if($rs)
	    $res=$this->stotip_id;
	  else 
	    $res="0";  
	}
	else
	{
	  $res=$existe;	
	}
	return($res);				
  }
  
  function del($id)
  {
 	$sql=<<<va
 	delete from stock_tipo 
	where stotip_id='$id' 
va;
	$rs = &$this->con->Execute($sql);
	if($rs)
	  $res=$id;
	else
	  $res="0";
	return($res);
  }
  
  function update($id)
  {
	$existe=$this->existe($id);
	if($existe)
	{
	  $sql=<<<va
	  UPDATE stock_tipo 
	  set stotip_nombre='$this->stotip_nombre',
	  stotip_visual='$this->stotip_visual',
	  stotip_formato='$this->stotip_formato',
	  stotip_convenio='$this->stotip_convenio' 
	  WHERE stotip_id='$existe'
va;
      $rs=&$this->con->Execute($sql);
      $res=$existe;
	}
	else 
	{
	  $res=$id;	
	}
	return($res);	
  }
  
  function info($id)
  {
  	$sql=<<<va
  	select stotip_id,stotip_nombre,stotip_visual,stotip_formato,stotip_convenio 
  	from stock_tipo
  	where stotip_id='$id'
va;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->stotip_id="";
	  $this->stotip_nombre="";	
	}
	else 
	{
	  $res=$id;
	  $this->stotip_id=$rs->fields[0];
	  $this->stotip_nombre=$rs->fields[1];
	  $this->stotip_visual=$rs->fields[2];
	  $this->stotip_formato=$rs->fields[3];
	  $this->stotip_convenio=$rs->fields[4];
	}
	return($res);
  }
  
  function sqlSelect($visual="1")
  {
    if($visual=="1")
  	  $cad="select stotip_id,stotip_nombre from stock_tipo where stotip_visual='1' order by stotip_id ";
  	else 
  	  $cad="select stotip_id,stotip_nombre from stock_tipo order by stotip_id ";
    return($cad);
  }
  
}
?>