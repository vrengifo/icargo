<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<? include('class/c_guia.php'); ?>
<?php
	$clase=new c_guia();
	extract($_REQUEST);		
/*		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion); */
		///todo  el html como se quiera
?>
<?php

	$clase->gui_nro=$gui_nro;
	$clase->tipgui_id=$tipgui_id;
	$clase->tipro_id=$tipo_carga;
	$clase->gui_fecha=$fecha." ".$hora;
	$clase->gui_piezas=$pieza;
	$clase->gui_peso=$peso;
	$clase->gui_volumen=$volumen;
	$clase->gui_vdeclarado=$vdeclarado;
	$clase->gui_descripcion=$contenido;
	$clase->gui_condocumento=$condocumento;
  	$clase->gui_observacion=$observacion;
	$clase->gui_obs_sindinenijoya=$sindinjo;
	$clase->gui_obs_sujetoespacio=$suespacio;
	$clase->gui_obs_cuentariesgorem=$cuentarem;
	$clase->ent_id=$entregadoen;
	$clase->cli_codigo="-";
	$clase->usu_codigo=$sUsername;
	$clase->est_origen=$procedencia;
	$clase->gui_remitente=$remite_nombre;
	$clase->gui_ced_remitente=$remite_ciruc;
	$clase->gui_dir_remitente=$remite_direccion;
	$clase->gui_telf_remitente=$remite_telf;
	$clase->gui_destinatario=$destinatario_nombre;
	$clase->gui_ced_destinatario=$destinatario_ciruc;
	$clase->gui_dir_destinatario=$destinatario_direccion;
	$clase->gui_telf_destinatario=$destinatario_telf;
	$clase->gui_fletecarga=$flete;
	$clase->gui_entregadomicilio=$entdom;
	$clase->gui_transadicional=$tadic;
	$clase->gui_servadicional=$sadic;
	$clase->gui_correspondencia=$corres;
	$clase->gui_seguros=$seguro;
	$clase->gui_embalaje=$embalaje;
	$clase->gui_subtotal=$subtotal;
	$clase->gui_descuento=$descuento;
	$clase->gui_iva=$iva;
	$clase->gui_total=$vtotal;
	$clase->ciu_codigo=$destino;
	
	$idp=$clase->add_factura($conn);	

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
$destino="location:factura_view.php?".$cad_dest."&id=".$idp;
//echo "$destino";
header($destino);

?>	
	<br>
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
