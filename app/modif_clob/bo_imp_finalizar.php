<?php
  session_start();
  include('includes/main.php');
  extract($_REQUEST);

/*
proceso
  1)  insertar el nuevo estado de la importacion, con el ipo_arribo='1'
  2)  actualizar el id de la importacion anterior a 0
  3)  actualizar el estado del po cii_po.po_state='3'
  4)  ir a la pag:  po_imp_tram.php
*/

$fecha=date('Y-m-d H:i:s');

//1)
if(strlen($nlugar)==0)
  $nlugar="Arribado";
if(strlen($nobservacion)==0)
  $nobservacion="Arribado!!!";



$sql_i="insert into cii_impor_bo "
      ."(bo_id,itipo_id,ibo_fecha,"
      ."ibo_actual,ibo_bo_number,ibo_guia,ibo_factura,ibo_lugar,ibo_observacion,ibo_arribo) "
      ."values "
      ."($compra_id,$tipo_importacion,to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),"
      ."'1','$compra_number','$nguia','$nfactura','$nlugar','$nobservacion','1')";
$rs_i=&$conn->Execute($sql_i);
//2)
$sql="update cii_impor_bo set ibo_actual='0' "
    ."where ibo_id=$impor_id ";
$rs=&$conn->Execute($sql);
//3)
$sql="update cii_bo set bo_state='3' "
    ."where bo_id=$compra_id ";
$rs=&$conn->Execute($sql);
//4)  destino
$p1="bo_imp_tram.php";
$destino="location:".$p1."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
//echo "<br>$destino <br>";
header($destino);


?>	
