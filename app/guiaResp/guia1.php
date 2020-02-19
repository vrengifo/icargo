<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<? include('class/c_guia.php'); ?>
<?php

		extract($_REQUEST);		
		$clase=new c_guia();
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
	$clase->cli_codigo=$cliente;
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
	$clase->gui_entregadomicilio=$entdom=0;
	$clase->gui_transadicional=$tadic=0;
	$clase->gui_servadicional=$sadic=0;
	$clase->gui_correspondencia=$corres;
	$clase->gui_seguros=$seguro=0;
	$clase->gui_embalaje=$embalaje=0;
	$clase->gui_subtotal=$subtotal;
	$clase->gui_descuento=$descuento;
	$clase->gui_iva=$iva;
	$clase->gui_total=$vtotal;
	$clase->ciu_codigo=$destino;
	$clase->gui_sobrepeso=$sobrepeso;
	
	//campos seteados con 0
	$clase->repven_id=0;//reporte de ventas
	$clase->manemb_id=0;//manifiesto de embarque
	$clase->gui_entregada="0";//indica si la guía fue entregada
	
	$idp=$clase->add_guia($conn);
	//echo "$idp <br>";

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
