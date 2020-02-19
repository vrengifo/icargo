<?php 
session_start(); 
?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php
		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
//		buildmenu($username);
//		buildsubmenu($id_aplicacion,$id_subaplicacion);
		///todo  el html como se quiera
?>
<?php
  //rec info en base a id
  $sql="select gui_nro,tipgui_id,tipro_id,gui_fecha,gui_piezas,gui_peso,gui_volumen,gui_vdeclarado,gui_descripcion,gui_condocumento,"
  	  ."gui_observacion,gui_obs_sindinenijoya,gui_obs_sujetoespacio,gui_obs_cuentariesgorem,ent_id,cli_codigo,usu_codigo,"
	  ."est_origen,gui_remitente,gui_ced_remitente,gui_dir_remitente,gui_telf_remitente,gui_destinatario,gui_ced_destinatario,"
	  ."gui_dir_destinatario,gui_telf_destinatario,gui_fletecarga,gui_entregadomicilio,gui_transadicional,gui_servadicional,"
	  ."gui_correspondencia,gui_seguros,gui_embalaje,gui_subtotal,gui_descuento,gui_iva,gui_total,ciu_codigo,gui_sobrepeso "
	  ."from guia "
	  ."where gui_id=$id";	  

  $rs=&$conn->Execute($sql);
  if(!$rs->EOF)
  {
    $gui_nro=$rs->fields[0];
	$tipgui_id=$rs->fields[1];
	$tipro_id=$rs->fields[2];
	$gui_fecha=$rs->fields[3];
	$gui_piezas=$rs->fields[4];
	$gui_peso=$rs->fields[5];
	$gui_volumen=$rs->fields[6];
	$gui_vdeclarado=$rs->fields[7];
	$gui_descripcion=$rs->fields[8];
	$gui_condocumento=$rs->fields[9];
  	$gui_observacion=$rs->fields[10];
	$gui_obs_sindinenijoya=$rs->fields[11];
	$gui_obs_sujetoespacio=$rs->fields[12];
	$gui_obs_cuentariesgorem=$rs->fields[13];
	$ent_id=$rs->fields[14];
	$cli_codigo=$rs->fields[15];
	$usu_codigo=$rs->fields[16];
	$est_origen=$rs->fields[17];
	$gui_remitente=$rs->fields[18];
	$gui_ced_remitente=$rs->fields[19];
	$gui_dir_remitente=$rs->fields[20];
	$gui_telf_remitente=$rs->fields[21];
	$gui_destinatario=$rs->fields[22];
	$gui_ced_destinatario=$rs->fields[23];
	$gui_dir_destinatario=$rs->fields[24];
	$gui_telf_destinatario=$rs->fields[25];
	$gui_fletecarga=$rs->fields[26];
	$gui_entregadomicilio=$rs->fields[27];
	$gui_transadicional=$rs->fields[28];
	$gui_servadicional=$rs->fields[29];
	$gui_correspondencia=$rs->fields[30];
	$gui_seguros=$rs->fields[31];
	$gui_embalaje=$rs->fields[32];
	$gui_subtotal=$rs->fields[33];
	$gui_descuento=$rs->fields[34];
	$gui_iva=$rs->fields[35];
	$gui_total=$rs->fields[36];
	$ciu_codigo=$rs->fields[37];
	$gui_sobrepeso=$rs->fields[38];
	
	//poner en los campos q hacen salida 
	$gui_nro=$gui_nro;
	$tipgui_id=$tipgui_id;
	$tipo_carga=$tipro_id;
	$fecha=$gui_fecha;
	$pieza=$gui_piezas;
	$peso=$gui_peso;
	$volumen=$gui_volumen;
	$vdeclarado=$gui_vdeclarado;
	$contenido=$gui_descripcion;
	$condocumento=$gui_condocumento;
  	$observacion=$gui_observacion;
	$sindinjo=$gui_obs_sindinenijoya;
	$suespacio=$gui_obs_sujetoespacio;
	$cuentarem=$gui_obs_cuentariesgorem;
	$entregadoen=$ent_id;
	$cliente=$cli_codigo;
	//$usu_codigo=$sUsername;
	$procedencia=$est_origen;
	$remite_nombre=$gui_remitente;
	$remite_ciruc=$gui_ced_remitente;
	$remite_direccion=$gui_dir_remitente;
	$remite_telf=$gui_telf_remitente;
	$destinatario_nombre=$gui_destinatario;
	$destinatario_ciruc=$gui_ced_destinatario;
	$destinatario_direccion=$gui_dir_destinatario;
	$destinatario_telf=$gui_telf_destinatario;
	$flete=$gui_fletecarga;
	$entdom=$gui_entregadomicilio;
	$tadic=$gui_transadicional;
	$sadic=$gui_servadicional;
	$corres=$gui_correspondencia;
	$seguro=$gui_seguros;
	$embalaje=$gui_embalaje;
	$subtotal=$gui_subtotal;
	$descuento=$gui_descuento;
	$iva=$gui_iva;
	$vtotal=$gui_total;
	$destino=$ciu_codigo;
	$sobrepeso=$gui_sobrepeso;
  }	  
?>	
	<br>
<script language="JavaScript">
function valida()
{

}
</script>

	
<form action="guia_entregar1.php" method="post" name="form1">
  <input type="hidden" name="id" value="<?=$id?>">
  
  <TABLE WIDTH="80%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR>
	  <TD>
		<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		  <tr>							
            <td nowrap><SPAN class="title" STYLE="cursor:default;"> 
                          <img src="images/360/taskwrite.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF"> 
                          Guía de Remisión Nro. <?=$gui_nro?></font></SPAN>
			</td>
		  </tr>
		</table>
		<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
		  <TR>
		    <TD> <table width='100%' border=1 cellpadding=2 cellspacing=1 bgcolor='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td width="45%" nowrap  bgcolor='#ffffff' valign="top"><div align="right"> 
                      <table width="100%" border="1">
                        <tr> 
                          <td width="26%" class="table_hd">Fecha de Recepci&oacute;n</td>
                          <td width="15%" class="table_hd">Piezas</td>
                          <td width="19%" class="table_hd">Peso (Kg)</td>
                          <td width="17%" class="table_hd">Volumen</td>
                          <td width="23%"class="table_hd">Valor Declarado</td>
                        </tr>
                        <tr> 
                          <td> 
                            <?php
							
							    $vvfecha=$fecha;							
							?>
                            <?=$vvfecha?>                            
                          </td>
                          <td><?=$pieza?></td>
                          <td><?=$peso?></td>
                          <td><?=$volumen?></td>
                          <td><?=$vdeclarado?></td>
                        </tr>
                        <tr> 
                          <td colspan="5" class="table_hd">Contenido Declarado</td>
                        </tr>
                        <tr> 
                          <td>Tipo de Carga</td>
                          <td colspan="4"> 
                             <?php
							   $sql="select tipro_descripcion from tipo_producto where tipro_id='$tipo_carga'";
							   $rs1=&$conn->Execute($sql);							   
							   $texto=$rs1->fields[0];
							 ?> 
							  <?=$texto?>							 
                            </td>
                        </tr>
                        <tr> 
                          <td>Descripci&oacute;n (Contenido)</td>
                          <td colspan="4"><?=$contenido?></td>
                        </tr>
                        <tr> 
                          <td>Sobre con Documentos</td>
                          <td colspan="4"><?php  if($condocumento=="1") echo "(Sí)"; else echo "(No)"; ?></td>
                        </tr>
						<tr> 
                          <td>Sobrepeso</td>
                          <td colspan="4"><?php  if($sobrepeso=="1") echo "(Sí)"; else echo "(No)"; ?></td>
                        </tr>
                        <tr> 
                          <td class="table_hd">Observaciones</td>
                          <td colspan="4"><?=$observacion?></td>
                        </tr>
                        <tr> 
                          <td colspan="5"><p>Sin dinero ni joyas 
                              <?php  if($sindinjo=="1") echo "(Sí)"; else echo "(No)";?>
							  
                              &nbsp;&nbsp; Sujeto a espacio 
							  <?php  if($suespacio=="1") echo "(Sí)"; else echo "(No)";?>
                              
                            </p>
                            <p>Viaja a cuenta y riesgo del Remitente 
                              <?php  if($cuentarem=="1") echo "(Sí)"; else echo "(No)";?>
                            </p></td>
                        </tr>
                        <tr> 
                          <td>&nbsp;</td>
                          <td colspan="4">&nbsp;</td>
                        </tr>
                      </table>
                    </div></td>
                  <td width="10%"></td>
                  <td width="45%" nowrap  bgcolor='#ffffff'> <table width="100%" border="1">
                      <tr> 
                        <td width="26%"  class="table_hd">Procedencia</td>
						<?php
						  $sql="select c.ciu_codigo,c.ciu_descripcion,e.est_id "
						  	  ." from estacion e,ciudad c "
							  ." where e.est_id=$procedencia "
							  ."and c.ciu_codigo=e.ciu_codigo ";
						  $rs1=&$conn->Execute($sql);
						  $ciudad_origen=$rs1->fields[0];
						  $nom=$rs1->fields[1];
						  $est=$rs1->fields[2];
						  	  
						?>
                        <td><?=$nom?> 
                        </td>
                      </tr>
                      <tr> 
                        <td>Cliente </td>
                        <td width="15%">
                            <?php
							   $sql="select cli_codigo,cli_descripcion from cliente where cli_codigo='$cliente' ";
							   $rs1=&$conn->Execute($sql);							   
							   $valor=$rs1->fields[0];
							   $texto=$rs1->fields[1];								 
							 ?>                            
                            <?=$texto?>
                            </td>
                      </tr>
					  <tr> 
                        <td>Remite </td>
                        <td width="15%"><?=$remite_nombre?></td>
                      </tr>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%"><?=$remite_ciruc?></td>
                      </tr>
                      <tr> 
                        <td>Dirección</td>
                        <td width="15%"><?=$remite_direccion?></td>
                      </tr>
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%"><?=$remite_telf?></td>
                      </tr>
                      <tr> 
                        <td class="table_hd">Entregado en</td>
                        <td>						 
						 <?php
						   $sql="select ent_id,ent_descripcion from entregado_en where ent_id='$entregadoen' ";
							   $rs1=&$conn->Execute($sql);						
							     $valor=$rs1->fields[0];
								 $texto=$rs1->fields[1];
						 ?>
                            <?=$texto?>
						 </td>
                      </tr>
                      <tr> 
                        <td class="table_hd">Destino</td>
                        <td> 
                            <?php
						   $sql="select ciu_codigo,ciu_descripcion from ciudad "
						   		."where ciu_codigo='$destino' ";
							   $rs1=&$conn->Execute($sql);
							 
							     $valor=$rs1->fields[0];
								 $texto=$rs1->fields[1];
						 ?>
                            <?=$texto?>
                            
                           </td>
                      </tr>
                      <tr> 
                        <td  class="table_hd">Destinatario</td>
                        <td width="15%"><?=$destinatario_nombre?></td>
                      </tr>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%"><?=$destinatario_ciruc?></td>
                      </tr>
                      <tr> 
                        <td>Dirección</td>
                        <td width="15%"><?=$destinatario_direccion?></td>
                      </tr>
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%"><?=$destinatario_telf?></td>
                      </tr>
                      <tr> 
                        <td colspan="2"><table width="100%" border="1">
                            <tr> 
                              <td class="table_hd">DETALLE</td>
                              <td class="table_hd">
							    <?php
								  if($tipgui_id=="PR")
								    $tgid="Contado";
								  if($tipgui_id=="CO")
								    $tgid="Collect";
								  if($tipgui_id=="CR")
								    $tgid="Crédito";		
								?>
							  <?=$tgid?></td>
                            </tr>
                            <tr> 
                              <td>Flete / Carga</td>
                              <td><?=$flete?></td>
                            </tr>
                            <tr> 
                              <td>Entrega Domicilio</td>
                              <td><?=$entdom?></td>
                            </tr>
                            <tr> 
                              <td>Transp. Adicional</td>
                              <td><?=$tadic?></td>
                            </tr>
                            <tr> 
                              <td>Servicio Adicional</td>
                              <td><?=$sadic?></td>
                            </tr>
                            <tr> 
                              <td>Correspondencia</td>
                              <td><?=$corres?></td>
                            </tr>
                            <tr> 
                              <td>Seguros</td>
                              <td><?=$seguro?></td>
                            </tr>
                            <tr> 
                              <td>Embalaje</td>
                              <td><?=$embalaje?></td>
                            </tr>
                            <tr> 
                              <td>Subtotal</td>
                              <td><?=$subtotal?></td>
                            </tr>
                            <tr> 
                              <td>Desc.</td>
                              <td><?=$descuento?></td>
                            </tr>
                            <tr> 
                              <td>IVA</td>
                              <td><?=$iva?></td>
                            </tr>
                            <tr> 
                              <td class="table_hd">Valor Total $</td>
                              <td><?=$vtotal?></td>
                            </tr>
                          </table></td>
                      </tr>					  
                    </table></td>
                </tr>
				<tr>
				  <td colspan="3" align="center">
				    <input type="button" name="Imprimir" value="Imprimir" onClick="print();">
					<input type="submit" name="Entregar" value="Entregar Guía" >
				  </td>
				</tr>                
              </table></TABLE>
				  </TABLE>

  <br>
<TABLE WIDTH="80%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
                      <TR> 
                        <TD> <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
                            <TR> 
                              <TD> </table></table>  
  <br>
  <br>
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>" >
    <input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>" >
    <input type="hidden" name="principal" value="<?=$principal?>" >
    <input type="hidden" name="campo_base" value="clp2|clp4|clp3" >
    <input type="hidden" name="campo_extra" value="id_aplicacion|id_subaplicacion|idp|principal" >
    <input type="hidden" name="cextra" value="id_aplicacion|id_subaplicacion|principal|idp">
    <br>
  
</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
