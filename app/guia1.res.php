<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<? //include('class/c_application.php'); ?>
<?php

		extract($_REQUEST);		
/*		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion); */
		///todo  el html como se quiera
	?>
<?php

	$gui_nro=$gui_nro;
	$tipgui_id=$tipgui_id;
	$tipro_id=$tipo_carga;
	$gui_fecha=$fecha." ".$hora;
	$gui_piezas=$pieza;
	$gui_peso=$peso;
	$gui_volumen=$volumen;
	$gui_vdeclarado=$vdeclarado;
	$gui_descripcion=$contenido;	
	$gui_condocumento=$condocumento;
  	$gui_observacion=$observacion;
	$gui_obs_sindinenijoya=$sindinjo;
	$gui_obs_sujetoespacio=$suespacio;
	$gui_obs_cuentariesgorem=$cuentarem;
	$ent_id=$entregadoen;
	$cli_codigo=$cliente;
	$usu_codigo=$sUsername;
	$est_origen=$procedencia;
	$gui_remitente=$remite_nombre;
	$gui_ced_remitente=$remite_ciruc;
	$gui_dir_remitente=$remite_direccion;
	$gui_telf_remitente=$remite_telf;
	$gui_destinatario=$destinatario_nombre;
	$gui_ced_destinatario=$destinatario_ciruc;
	$gui_dir_destinatario=$destinatario_direccion;
	$gui_telf_destinatario=$destinatario_telf;
	$gui_fletecarga=$flete;
	$gui_entregadomicilio=$entdom;
	$gui_transadicional=$tadic;
	$gui_servadicional=$sadic;
	$gui_correspondencia=$corres;
	$gui_seguros=$seguro;
	$gui_embalaje=$embalaje;
	$gui_subtotal=$subtotal;
	$gui_descuento=$descuento;
	$gui_iva=$iva;
	$gui_total=$vtotal;
	$ciu_codigo=$destino;
	$gui_sobrepeso=$sobrepeso;
	
	//campos seteados con 0
	$repven_id=0;//reporte de ventas
	$manemb_id=0;//manifiesto de embarque
	$gui_entregada="0";//indica si la guía fue entregada
	
	$sql="insert into guia "
		."(gui_nro,tipgui_id,tipro_id,gui_fecha,gui_piezas,gui_peso,gui_volumen,gui_vdeclarado,gui_descripcion,gui_condocumento,"
  	    ."gui_observacion,gui_obs_sindinenijoya,gui_obs_sujetoespacio,gui_obs_cuentariesgorem,ent_id,cli_codigo,usu_codigo,"
	    ."est_origen,gui_remitente,gui_ced_remitente,gui_dir_remitente,gui_telf_remitente,gui_destinatario,gui_ced_destinatario,"
	    ."gui_dir_destinatario,gui_telf_destinatario,gui_fletecarga,gui_entregadomicilio,gui_transadicional,gui_servadicional,"
	    ."gui_correspondencia,gui_seguros,gui_embalaje,gui_subtotal,gui_descuento,gui_iva,gui_total,ciu_codigo,gui_sobrepeso,"
		."repven_id,manemb_id,gui_entregada) "
		."values "		
		."('$gui_nro','$tipgui_id','$tipro_id',to_date('".$gui_fecha."','YYYY-MM-DD HH24:MI'),$gui_piezas,'$gui_peso',$gui_volumen,'$gui_vdeclarado','$gui_descripcion','$gui_condocumento',"
  	    ."'$gui_observacion','$gui_obs_sindinenijoya','$gui_obs_sujetoespacio','$gui_obs_cuentariesgorem','$ent_id','$cli_codigo','$usu_codigo',"
	    ."$est_origen,'$gui_remitente','$gui_ced_remitente','$gui_dir_remitente','$gui_telf_remitente','$gui_destinatario','$gui_ced_destinatario',"
	    ."'$gui_dir_destinatario','$gui_telf_destinatario','$gui_fletecarga','$gui_entregadomicilio','$gui_transadicional','$gui_servadicional',"
	    ."'$gui_correspondencia','$gui_seguros','$gui_embalaje','$gui_subtotal','$gui_descuento','$gui_iva','$gui_total','$ciu_codigo','$gui_sobrepeso',"
		."$repven_id,$manemb_id,'$gui_entregada')";
		
	$rs=&$conn->Execute($sql);
	//echo "$sql <br>";	
	//recuperar el gui_id
	$sql_rec="select gui_id from guia where "
			."gui_nro='$gui_nro' and to_char(gui_fecha,'YYYY-MM-DD HH24:MI')='$gui_fecha' "
			." and usu_codigo='$sUsername' ";
	$rs=&$conn->Execute($sql_rec);		
	//echo "$sql_rec <br>";
	$idp=$rs->fields[0];
	//echo "$idp <br>";

/*
//pv
$cait=new c_application();
//recuperar todos los datos de la otra forma
$cbase=explode("|",$campo_base);
$t_cbase=count($cbase);

for ($i=0;$i<$t_cbase;$i++)
{
	$dato[$i]=$$cbase[$i];
}
$cait->cargar_dato($dato);
//$cait->mostrar_dato();
$idp=$cait->add($conn);
if($idp=="0")
  echo "Error";
*/

//destino
$cextra=explode("|",$campo_extra);
$t_cextra=count($cextra);
for ($i=0;$i<$t_cextra;$i++)
{
	$c1=$cextra[$i];
/*	if($c1=="principal")	
	  $destino="location:".$$c1."?";

	else	
*/	
	  $cad_dest.=$c1."=".$$c1."&";
}
$cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
$destino="location:guia_view.php?".$cad_dest."&id=".$idp;
echo "$destino";
header($destino);

?>	
	<br>
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
