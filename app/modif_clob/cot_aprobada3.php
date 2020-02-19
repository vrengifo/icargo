<?php
  session_start();
  include('includes/main.php');
  extract($_REQUEST);

/*
proceso
  1)  insertar la compra
  2)  recuperar el id
  3)  insertar el detalle de la compra
  4)  actualizar cii_cotizacion.cot_state='3'
*/

$cad=date('YmdHis');
$fecha=date('Y-m-d H:i:s');

$total_dolar=$total_compra*$hmon_todolar;

if($tipo_compra=="PO")
{
 // 1)
 if(strlen($number)==0)
 {
   $number="PO".$cad;
 }
 
if(strlen($observation)<=0)
  $observation="-"; 
 
 $sql1="insert into cii_po "
      ."(prov_id,from_id,mon_id,po_number,po_date,"
      ."po_aircraft,po_priority,po_issuedby,po_observation,po_total,"
      ."po_total_dolar,po_state,cotprov_id) "
      ."values "
      ."($hprov_id,$from_id,$hmon_id,'$number',to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),"
      ."'$haircraft',$prioridad,'$issuedby','$observation','$total_compra',"
      ."'$total_dolar','0',$hcotprov_id)";
  $rs1=&$conn->Execute($sql1);
  // 2)
  $sql2="select po_id from cii_po where "
       ."prov_id=$hprov_id and from_id=$from_id and po_number='$number' and po_state='0' and cotprov_id=$hcotprov_id ";
  $rs2=&$conn->Execute($sql2);
  $identificador=$rs2->fields[0];
  // 3)
  $sql3="insert into cii_po_detail "
       ."(po_id,cotprovdet_description,cotprovdet_pn,cotprovdet_qty,cotprovdet_unit,cotprovdet_pu,cotprovdet_total,cotprovdet_pu_dolar,cotprovdet_total_dolar) "
       ."select ".$identificador.",cotprovdet_description,cotprovdet_pn,cotprovdet_qty,cotprovdet_unit,cotprovdet_pu,cotprovdet_total,(".$hmon_todolar."*cotprovdet_pu),(".$hmon_todolar."*cotprovdet_total) "
       ."from cii_cotxprov_det where cotprov_id=$hcotprov_id ";
  $rs3=&$conn->Execute($sql3);
}

if($tipo_compra=="BO")
{
 // 1)
 if(strlen($number)==0)
 {
   $number="BO".$cad;
 }
 
if(strlen($observation)<=0)
  $observation="-"; 
   
 $sql1="insert into cii_bo "
      ."(prov_id,from_id,mon_id,bo_number,bo_date,"
      ."bo_aircraft,bo_priority,bo_processedby,bo_observation,bo_total,"
      ."bo_total_dolar,bo_state,cotprov_id,botip_id) "
      ."values "
      ."($hprov_id,$from_id,$hmon_id,'$number',to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),"
      ."'$haircraft',$prioridad,'$processedby','$observation','$total_compra',"
      ."'$total_dolar','0',$hcotprov_id,$br_tipo)";
  $rs1=&$conn->Execute($sql1);
  // 2)
  $sql2="select bo_id from cii_bo where "
       ."prov_id=$hprov_id and from_id=$from_id and bo_number='$number' and bo_state='0' and cotprov_id=$hcotprov_id ";
  $rs2=&$conn->Execute($sql2);
  $identificador=$rs2->fields[0];
  // 3)
  $sql3="insert into cii_bo_detail "
       ."(bo_id,cotprovdet_description,cotprovdet_pn,cotprovdet_qty,cotprovdet_unit,cotprovdet_pu,cotprovdet_total,cotprovdet_pu_dolar,cotprovdet_total_dolar) "
       ."select ".$identificador.",cotprovdet_description,cotprovdet_pn,cotprovdet_qty,cotprovdet_unit,cotprovdet_pu,cotprovdet_total,(".$hmon_todolar."*cotprovdet_pu),(".$hmon_todolar."*cotprovdet_total) "
       ."from cii_cotxprov_det where cotprov_id=$hcotprov_id ";
  $rs3=&$conn->Execute($sql3);
}

if($tipo_compra=="RO")
{
 // 1)
 if(strlen($number)==0)
 {
   $number="RO".$cad;
 }

if(strlen($observation)<=0)
  $observation="-";  
 
 $sql1="insert into cii_ro "
      ."(prov_id,from_id,mon_id,ro_number,ro_date,"
      ."ro_aircraft,ro_priority,ro_processedby,ro_observation,ro_total,"
      ."ro_total_dolar,ro_state,cotprov_id,ro_work,botip_id) "
      ."values "
      ."($hprov_id,$from_id,$hmon_id,'$number',to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),"
      ."'$haircraft',$prioridad,'$processedby','$observation','$total_compra',"
      ."'$total_dolar','0',$hcotprov_id,'$work',$br_tipo)";
  $rs1=&$conn->Execute($sql1);
  // 2)
  $sql2="select ro_id from cii_ro where "
       ."prov_id=$hprov_id and from_id=$from_id and ro_number='$number' and ro_state='0' and cotprov_id=$hcotprov_id ";
  $rs2=&$conn->Execute($sql2);
  $identificador=$rs2->fields[0];
  // 3)

  for($i=0;$i<$cpd_total_item;$i++)
  {
    $vsn=$cpd_sn[$i];
    $vid=$cpd_id[$i];
    $vvalue=$cpd_value[$i];
    
    $sql_des="select cotprovdet_description,cotprovdet_pn,cotprovdet_qty "
          ."from cii_cotxprov_det where cotprovdet_id=$vid";
    $rsa=&$conn->Execute($sql_des);
    $vdes=$rsa->fields[0];
    $vpn=$rsa->fields[1];
    $vqty=$rsa->fields[2];

    $sql3="insert into cii_ro_detail "
       ."(ro_id,rodet_description,rodet_pn,rodet_qty,rodet_sn,rodet_value) "
       ."values "
       ."($identificador,'$vdes','$vpn','$vqty','$vsn','$vvalue')";
    $rs3=&$conn->Execute($sql3);
  }
}

if($tipo_compra=="OR")
{
 // 1)
 if(strlen($number)==0)
 {
   $number="CL".$cad;
 }
if(strlen($observation)<=0)
  $observation="-";  
 $sql1="insert into cii_orden "
      ."(prov_id,from_id,mon_id,orden_number,orden_date,"
      ."orden_ac,orden_pedidopor,orden_revisadopor,orden_observation,orden_total,"
      ."orden_total_dolar,orden_state,cotprov_id) "
      ."values "
      ."($hprov_id,$from_id,$hmon_id,'$number',to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS'),"
      ."'$haircraft','$pedidopor','$revisadopor','$observation','$total_compra',"
      ."'$total_dolar','0',$hcotprov_id)";
  $rs1=&$conn->Execute($sql1);
  // 2)
  $sql2="select orden_id from cii_orden where "
       ."prov_id=$hprov_id and from_id=$from_id and orden_number='$number' and orden_state='0' and cotprov_id=$hcotprov_id ";
  $rs2=&$conn->Execute($sql2);
  $identificador=$rs2->fields[0];
  // 3)
  $sql3="insert into cii_orden_detail "
       ."(orden_id,ordendet_descripcion,ordendet_codigo,ordendet_qty,ordendet_unit,ordendet_pu,ordendet_total,ordendet_pu_dolar,ordendet_total_dolar) "
       ."select ".$identificador.",cotprovdet_description,cotprovdet_pn,cotprovdet_qty,cotprovdet_unit,cotprovdet_pu,cotprovdet_total,(".$hmon_todolar."*cotprovdet_pu),(".$hmon_todolar."*cotprovdet_total) "
       ."from cii_cotxprov_det where cotprov_id=$hcotprov_id ";
  $rs3=&$conn->Execute($sql3);
}

  // 4)
  $sql4="update cii_cotizacion set cot_state='3' where cot_id=$hcot_id ";
  $rs4=&$conn->Execute($sql4);

//ir al listado de las que estan por aprobar
//destino
$destino="location:".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
//echo "<br>$destino <br>";
header($destino);


?>	
