<?php
  session_start();
  include('includes/main.php');
  extract($_REQUEST);

/*
proceso
  1)  insertar el nuevo estado de la importacion
  2)  recuperar el id del estado insertado
  3)  actualizar el id de la importacion anterior a 0
  4)  ir a la pag:  po_imp_tram1.php pasando el id nuevo como id=...
*/

$fecha=date('Y-m-d H:i:s');
if(strlen($nobservacion)<=0)
  $nobservacion="-";
//1)
$sql_i="insert into cii_impor_bo "
      ."(bo_id,itipo_id,ibo_fecha,"
      ."ibo_actual,ibo_bo_number,ibo_guia,ibo_factura,ibo_lugar,ibo_observacion) "
      ."values "
      ."($compra_id,$tipo_importacion,to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),"
      ."'1','$compra_number','$nguia','$nfactura','$nlugar','$nobservacion')";
$rs_i=&$conn->Execute($sql_i);
//2)
$sql_r="select ibo_id from cii_impor_bo where "
      ."bo_id=$compra_id and to_char(ibo_fecha,'YYYY-MM-DD HH24:MI:SS')='$fecha' and ibo_actual='1' ";
$rs_r=&$conn->Execute($sql_r);
$ibo_id=$rs_r->fields[0];
//3)
$sql="update cii_impor_bo set ibo_actual='0' "
    ."where ibo_id=$impor_id ";
$rs=&$conn->Execute($sql);
//4)  destino
$p1="bo_imp_tram1.php";
$destino="location:".$p1."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&id=".$ibo_id;
//echo "<br>$destino <br>";
header($destino);


?>	