<? session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php
  extract($_REQUEST);		
  $fecha=date('Y-m-d H:i:s');
  
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);

  
  /*
    pasos:
	1) buscar el componente dependiendo de tipegr y de los datos que se requieren para buscar como pn / sn
	   si se encontro el componente, recuperar en base al id bod_id, per_id, descripcion, cantidad actual
	2) calcular la nueva cantidad y actualizar en la tabla de inventarios
	3) insertar en la tabla de egresobo el respectivo egreso efectuado con los datos requeridos
  */	
  //armar includes dependiendo de que egreso a bodega se va a hacer
  //rotables
  
if(strlen($observacion)<=0)
  $observacion="-";   
  
  if($tipegr=="ROT")
  {
	include('class/cinv_rotable.php');
	$objeto=new cinv_rotable();
    //1)
    $sql="select rot_id,bod_id,per_id,rot_descripcion from inv_rotable where rot_pn='$pn' and rot_sn='$sn' ";
    $rs=&$conn->Execute($sql);
    if(!$rs->EOF)
    {
      $vid=$rs->fields[0];
      $vbod_id=$rs->fields[1];
      $vper_id=$rs->fields[2];
      $vdescripcion=$rs->fields[3];
      
      //2)
      $resultado=$objeto->egreso_producto($conn,$vid,$cantidad);
      if($resultado[0]==1)
      {
        //3)
        $sqli="insert into inv_egresobo values "
             ."(0,$vbod_id,$vper_id,'$pn','$sn','$vdescripcion','$cantidad','$aeronave','$retiradopor',"
	         ."to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),'$tipegr','$observacion')";
        $rsi=&$conn->Execute($sqli);
        //mensaje
        $mensaje="<br>Egreso efectuado satisfactoriamente!!!<br>PN:".$pn."<br>SN:".$sn."<br>Cantidad Actual:".$resultado[1];
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
      else
      {
        $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>SN:".$sn."<br>Insuficiente stock."
                ."  Reduzca la cantidad del egreso o efectue un ingreso a bodega!!!";
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
    }
    else
    {
      //no se debe insertar porque no se encontro
      $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>SN:".$sn."<br>No encontrado!!!";
      $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
      $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
    }
    
  }
  //fungibles
  if($tipegr=="FUN")
  {
	include('class/cinv_fungible.php');
	$objeto=new cinv_fungible();
    //1)
    $sql="select fun_id,bod_id,per_id,fun_descripcion from inv_fungible where fun_pn='$pn'";
    $rs=&$conn->Execute($sql);
    if(!$rs->EOF)
    {
      $vid=$rs->fields[0];
      $vbod_id=$rs->fields[1];
      $vper_id=$rs->fields[2];
      $vdescripcion=$rs->fields[3];

      //2)
      $resultado=$objeto->egreso_producto($conn,$vid,$cantidad);
      if($resultado[0]==1)
      {
        //3)
        $sqli="insert into inv_egresobo values "
             ."(0,$vbod_id,$vper_id,'$pn','$sn','$vdescripcion','$cantidad','$aeronave','$retiradopor',"
	         ."to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),'$tipegr','$observacion')";
        $rsi=&$conn->Execute($sqli);
        //mensaje
        $mensaje="<br>Egreso efectuado satisfactoriamente!!!<br>PN:".$pn."<br>Cantidad Actual:".$resultado[1];
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
      else
      {
        $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>Insuficiente stock."
                ."  Reduzca la cantidad del egreso o efectue un ingreso a bodega!!!";
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
    }
    else
    {
      //no se debe insertar porque no se encontro
      $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>SN:".$sn."<br>No encontrado!!!";
      $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
      $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
    }

  }
  if($tipegr=="HCA")
  {
	include('class/cinv_herr_calibracion.php');
	$objeto=new cinv_herr_calibracion();		
    //1)
    $sql="select hca_id,bod_id,per_id,hca_descripcion from inv_herra_calibracion where hca_pn='$pn' and hca_sn='$sn' ";
    $rs=&$conn->Execute($sql);
    if(!$rs->EOF)
    {
      $vid=$rs->fields[0];
      $vbod_id=$rs->fields[1];
      $vper_id=$rs->fields[2];
      $vdescripcion=$rs->fields[3];

      //2)
      $resultado=$objeto->egreso_producto($conn,$vid,$cantidad);
      if($resultado[0]==1)
      {
        //3)
        $sqli="insert into inv_egresobo values "
             ."(0,$vbod_id,$vper_id,'$pn','$sn','$vdescripcion','$cantidad','$aeronave','$retiradopor',"
	         ."to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),'$tipegr','$observacion')";
        $rsi=&$conn->Execute($sqli);
        //mensaje
        $mensaje="<br>Egreso efectuado satisfactoriamente!!!<br>PN:".$pn."<br>SN:".$sn."<br>Cantidad Actual:".$resultado[1];
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
      else
      {
        $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>SN:".$sn."<br>Insuficiente stock."
                ."  Reduzca la cantidad del egreso o efectue un ingreso a bodega!!!";
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
    }
    else
    {
      //no se debe insertar porque no se encontro
      $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>SN:".$sn."<br>No encontrado!!!";
      $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
      $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
    }

  }
  if($tipegr=="HES")
  {
	include('class/cinv_herr_especial.php');
	$objeto=new cinv_herr_especial();	  
    //1)
    $sql="select hes_id,bod_id,per_id,hes_descripcion from inv_herra_especial where hes_pn='$pn' ";
    $rs=&$conn->Execute($sql);
    if(!$rs->EOF)
    {
      $vid=$rs->fields[0];
      $vbod_id=$rs->fields[1];
      $vper_id=$rs->fields[2];
      $vdescripcion=$rs->fields[3];

      //2)
      $resultado=$objeto->egreso_producto($conn,$vid,$cantidad);
      if($resultado[0]==1)
      {
        //3)
        $sqli="insert into inv_egresobo values "
             ."(0,$vbod_id,$vper_id,'$pn','$sn','$vdescripcion','$cantidad','$aeronave','$retiradopor',"
	         ."to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),'$tipegr','$observacion')";
        $rsi=&$conn->Execute($sqli);
        //mensaje
        $mensaje="<br>Egreso efectuado satisfactoriamente!!!<br>PN:".$pn."<br>Cantidad Actual:".$resultado[1];
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
      else
      {
        $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>Insuficiente stock."
                ."  Reduzca la cantidad del egreso o efectue un ingreso a bodega!!!";
        $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
        $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
      }
    }
    else
    {
      //no se debe insertar porque no se encontro
      $mensaje="<br>Egreso no efectuado!!!<br>PN:".$pn."<br>No encontrado!!!";
      $url=$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
      $mensaje.="<br><a href='".$url."'>Ir a Listado de Egresos</a>";
    }

  }
  echo "$mensaje";
/*
//destino
$cextra=explode("|",$campo_extra);
$t_cextra=count($cextra);
for ($i=0;$i<$t_cextra;$i++)
{
	$c1=$cextra[$i];
    $cad_dest.=$c1."=".$$c1."&";
}
$cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
$destino="location:inv_ingresobo.php?".$cad_dest."&id=".$idp;
//echo "$destino";
header($destino);
*/

?>	
<?php
		buildsubmenufooter();
		//rs2html($recordSet,"border=3 bgcolor='#effee'");

?>
</body>
</html>
