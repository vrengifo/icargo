<?php
  session_start();
  include('includes/main.php');
  extract($_REQUEST);

/*
proceso
  1)  actualizar el estado
  2)  insertar cii_impor_po
*/

$fecha=date('Y-m-d H:i:s');

//recuperar el po_number
$sql_n="select ro_number from cii_ro where ro_id=$compra_id";
$rs_n=&$conn->Execute($sql_n);
$number=$rs_n->fields[0];

$sql="update cii_ro set ro_state='2' "
    ."where ro_id=$compra_id ";
$rs=&$conn->Execute($sql);


$sql1="insert into cii_impor_ro (ro_id,itipo_id,iro_fecha,iro_actual,iro_ro_number,iro_observacion) "
     ."values "
     ."($compra_id,$impor_tipo,to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),'1','$number','-')";
$rs1=&$conn->Execute($sql1);

//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
//echo "<br>$destino <br>";
header($destino);


?>	
