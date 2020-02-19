<?php
  session_start();
  include('includes/main.php');
  extract($_REQUEST);

//pv
if(strlen($fecha)==0)
{
 $fecha=date('Y-m-d H:i:s');
}



/*
proceso:
    1)  Insertar en cii_cotizacion matreq_id,cot_asunto,cot_texto,cot_fecha
    2)  Recuperar el cot_id
    3)  Insertar en cii_cotizacion_detail los datos que se encuentran en matreq_detail_tmp y eliminar de matreq_detail_tmp una vez insertado
    3.1)  recuperar en base al chc[] matreqdet_id,matreqdet_description,matreqdet_pn,matreqdet_qty,matreqdet_unit de matreq_detail_tmp
    3.2)  insertar en cii_cotizacion_detail cot_id,matreqdet_id,matreqdet_description,matreqdet_pn,matreqdet_qty,matreqdet_unit
    3.3)  eliminar de matreq_detail_tmp el dato insertado
*/
// 1)
if(strlen($texto)<=0)
  $texto="-";
$sql1="insert into cii_cotizacion "
			." (matreq_id,cot_asunto,cot_texto,cot_fecha,cot_state) values "
			." ($matreq_id,'$asunto','$texto',to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),'0')";
$rs = &$conn->Execute($sql1);
// 2)
$sql2="select cot_id from cii_cotizacion "
    ."where matreq_id=$matreq_id and cot_asunto='$asunto' and "
    ."TO_CHAR(cot_fecha,'YYYY-MM-DD HH24:MI:SS')='$fecha' ";
$rs = &$conn->Execute($sql2);
$cot_id=$rs->fields[0];

// 3)
for($i=0;$i<$total;$i++)
{
 if(isset($chc[$i]))
 {
  $detalle=$chc[$i];
  // 3.1)
  $sql_det="select matreqdet_description,matreqdet_pn,matreqdet_qty,matreqdet_unit "
          ."from matreq_detail_tmp "
          ."where matreqdet_id=$detalle";
  $rs_det = &$conn->Execute($sql_det);
  $mrd_description=$rs_det->fields[0];
  $mrd_pn=$rs_det->fields[1];
  $mrd_qty=$rs_det->fields[2];
  $mrd_unit=$rs_det->fields[3];
  // 3.2)
  $sql_ins_cot="insert into cii_cotizacion_detail "
              ."(cot_id,matreqdet_id,matreqdet_description,matreqdet_pn,matreqdet_qty,matreqdet_unit) "
              ."values "
              ."($cot_id,$detalle,'$mrd_description','$mrd_pn','$mrd_qty','$mrd_unit')";
  $rs_ins_cot= &$conn->Execute($sql_ins_cot);
  // 3.3)
  $sql_del="delete from matreq_detail_tmp where "
          ."matreqdet_id=$detalle and matreq_id=$matreq_id";
  $rs_del= &$conn->Execute($sql_del);
 }
}

//destino
$destino="location:matreq_cot.php?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
$destino.="&principal=".$principal."&id=".$matreq_id."&cotizado=1";
header($destino);


?>	
