<?php 
session_start(); 
?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php
		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion);
		///todo  el html como se quiera
	?>
	<br>
	
<form action="guia1.php" method="post" name="form1">
  
  
  <TABLE WIDTH="80%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR>
	  <TD>
		<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		  <tr>							
            <td nowrap><SPAN class="title" STYLE="cursor:default;"> 
                          <img src="images/360/taskwrite.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF"> 
                          Guía de Remisión Nro. <input name="gui_nro" type="text" value="<?=$gui_nro?>" size="8" maxlength="7"></font></SPAN>
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
							if (isset($fecha))
							    $vvfecha=$fecha;
							  else
							    $vvfecha=date("Y-m-d");
							if (isset($hora))
							    $vvhora=$hora;
							  else
							    $vvhora=date("H:i");	
							?>                            
                            <input name="fecha" type="text" size="10" maxlength="10" value="<?=$vvfecha?>">
                            <input name="hora" type="text" size="5" maxlength="5" value="<?=$vvhora?>"> </td>
                          <td> 
                            <select name="pieza">
							<?php
							  $vini=1;
							  $vfin=20;
							  $vincremento=1;							  
							  for($i=$vini;$i<=$vfin;$i+=$vincremento)
							  {
							?>
							  <option value="<?=$i?>" <?php
							    if($pieza==$i)
								  echo " selected";
							  ?>><?=$i?></option>
							<?php
							  }
							?>
							</select>
						  </td>
                          <td>
						    <!--<input name="peso" type="text" size="4" maxlength="4" value="<?=$peso?>">-->
                            <select name="peso">
							<?php
							  $vini=1;
							  $vfin=100;
							  $vincremento=1;							  
							  for($i=$vini;$i<=$vfin;$i+=$vincremento)
							  {
							?>
							  <option value="<?=$i?>" <?php
							    if($peso==$i)
								  echo " selected";
							  ?>><?=$i?></option>
							<?php
							  }
							?>
							</select>							
                          </td>
                          <td> 
                            <select name="volumen">
                              <?php
							  $vini=0;
							  $vfin=30;
							  $vincremento=1;							  
							  for($i=$vini;$i<=$vfin;$i+=$vincremento)
							  {
							?>
                              <option value="<?=$i?>" <?php
							    if($volumen==$i)
								  echo " selected";
							  ?>>
                              <?=$i?>
                              </option>
                              <?php
							  }
							?>
                            </select> </td>
                          <td> 
                            <select name="vdeclarado">
							  <option value="0" <?php
							    if($vdeclarado==0)
								  echo " selected";
							  ?>>S.V.D.</option>
                              <?php
							  $vini=5;
							  $vfin=100;
							  $vincremento=5;							  
							  for($i=$vini;$i<=$vfin;$i+=$vincremento)
							  {
							?>
                              <option value="<?=$i?>" <?php
							    if($vdeclarado==$i)
								  echo " selected";
							  ?>>
                              <?=$i?>
                              </option>
                              <?php
							  }
							?>
                            </select> </td>
                        </tr>
                        <tr> 
                          <td colspan="5" class="table_hd">Contenido Declarado</td>
                        </tr>
                        <tr> 
                          <td>Tipo de Carga</td>
                          <td colspan="4"> <select name="tipo_carga">
                             <?php
							   $sql="select tipro_id,tipro_descripcion from tipo_producto order by tipro_descripcion ";
							   $rs1=&$conn->Execute($sql);
							   while (!$rs1->EOF)
							   {
							     $valor=$rs1->fields[0];
								 $texto=$rs1->fields[1];								 
							 ?> 
							  <option value="<?=$valor?>"><?=$texto?></option>
							 <?php
							     $rs1->MoveNext();
							   }
							 ?>
                            </select> </td>
                        </tr>
                        <tr> 
                          <td>Descripci&oacute;n (Contenido)</td>
                          <td colspan="4"><textarea name="contenido" ><?=$contenido?></textarea></td>
                        </tr>
                        <tr> 
                          <td>Sobre con Documentos</td>
                          <td colspan="4"><input name="condocumento" type="checkbox" value="1"></td>
                        </tr>
						<tr> 
                          <td>Sobrepeso</td>
                          <td colspan="4"><input name="sobrepeso" type="checkbox" value="1"></td>
                        </tr>
                        <tr> 
                          <td class="table_hd">Observaciones</td>
                          <td colspan="4"><textarea name="observacion"><?=$observacion?></textarea></td>
                        </tr>
                        <tr> 
                          <td colspan="5"><p>Sin dinero ni joyas 
                              <input type="checkbox" name="sindinjo" value="1" checked>
                              &nbsp;&nbsp; Sujeto a espacio 
                              <input type="checkbox" name="suespacio" value="1" checked>
                            </p>
                            <p>Viaja a cuenta y riesgo del Remitente 
                              <input type="checkbox" name="cuentarem" value="1" checked>
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
						  	  ." from usuario u,estacion e,ciudad c "
							  ." where u.usu_codigo='$sUsername' and e.est_id=u.est_id "
							  ."and c.ciu_codigo=e.ciu_codigo ";
						  $rs1=&$conn->Execute($sql);
						  $ciudad_origen=$rs1->fields[0];
						  $nom=$rs1->fields[1];
						  $est=$rs1->fields[2];
						  	  
						?>
                        <td><?=$nom?> <input type="hidden" name="procedencia" value="<?=$est?>"> 
                        </td>
                      </tr>
                      <tr> 
                        <td>Cliente </td>
                        <td width="15%"><select name="cliente">
                            <?php
							   $sql="select cli_codigo,cli_descripcion from cliente order by cli_descripcion ";
							   $rs1=&$conn->Execute($sql);
							   while (!$rs1->EOF)
							   {
							     $valor=$rs1->fields[0];
								 $texto=$rs1->fields[1];								 
							 ?>
                            <option value="<?=$valor?>">
                            <?=$texto?>
                            </option>
                            <?php
							     $rs1->MoveNext();
							   }
							 ?>
                          </select></td>
                      </tr>
					  <tr> 
                        <td>Remite </td>
                        <td width="15%"><input name="remite_nombre" type="text" size="30" value="<?=$remite_nombre?>"></td>
                      </tr>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%">
						  <!--<input name="remite_ciruc" type="text" size="30" value="<?=$remite_ciruc?>" onChange="valida_ci(document.form1.remite_ciruc.value);">-->
						  <input name="remite_ciruc" type="text" size="30" value="<?=$remite_ciruc?>">
                          <!--<input name="validarci" type="button" value="Verificar CI/RUC" onClick="return valida_ci(document.form1.remite_ciruc.value);"></td>-->
                      </tr>
                      <tr> 
                        <td>Dirección</td>
                        <td width="15%"><input name="remite_direccion" type="text" size="30" value="<?=$remite_direccion?>"></td>
                      </tr>
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%"><input name="remite_telf" type="text" size="30" value="<?=$remite_telf?>"></td>
                      </tr>
                      <tr> 
                        <td class="table_hd">Entregado en</td>
                        <td>
						 <select name="entregadoen">
						 <?php
						   $sql="select ent_id,ent_descripcion from entregado_en order by ent_descripcion ";
							   $rs1=&$conn->Execute($sql);
							   while (!$rs1->EOF)
							   {
							     $valor=$rs1->fields[0];
								 $texto=$rs1->fields[1];
						 ?>
                            <option value="<?=$valor?>"><?=$texto?></option>
						 <?php
						      $rs1->MoveNext();	
							}	
						 ?>	
                          </select> </td>
                      </tr>
                      <tr> 
                        <td class="table_hd">Destino</td>
                        <td> </select> <select name="destino">
                            <?php
						   $sql="select ciu_codigo,ciu_descripcion from ciudad "
						   		."where ciu_codigo<>'$ciudad_origen' "
						   		."order by ciu_descripcion ";
							   $rs1=&$conn->Execute($sql);
							   while (!$rs1->EOF)
							   {
							     $valor=$rs1->fields[0];
								 $texto=$rs1->fields[1];
						 ?>
                            <option value="<?=$valor?>"><?=$texto?></option>
                            <?php
						      $rs1->MoveNext();	
							}	
						 ?>
                          </select> </td>
                      </tr>					  
                      <tr> 
                        <td  class="table_hd">Destinatario</td>
                        <td width="15%"><input name="destinatario_nombre" type="text" size="30" value="<?=$destinatario_nombre?>"></td>
                      </tr>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%">
						  <!--<input name="destinatario_ciruc" type="text" size="30" value="<?=$destinatario_ciruc?>"  onChange="valida_ci(document.form1.destinatario_ciruc.value);">-->
						  <input name="destinatario_ciruc" type="text" size="30" value="<?=$destinatario_ciruc?>">
                          <!--<input name="validarci2" type="button" value="Verificar CI/RUC" onClick="return valida_ci(document.form1.destinatario_ciruc.value);">-->
						</td>
                      </tr>
                      <tr> 
                        <td>Dirección</td>
                        <td width="15%"><input name="destinatario_direccion" type="text" size="30" value="<?=$destinatario_direccion?>"></td>
                      </tr>
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%"><input name="destinatario_telf" type="text" size="30" value="<?=$destinatario_telf?>"></td>
                      </tr>
                      <tr> 
                        <td colspan="2"><table width="100%" border="1">
                            <tr> 
                              <td class="table_hd">DETALLE</td>
                              <td class="table_hd"><p>CR&Eacute;DITO 
                                  <input name="tipgui_id" type="radio" value="CR" checked>
                                </p>
                                </td>
                            </tr>
<script language="JavaScript">
function calcular()
{
  var vsubtotal=0;
  var viva=0;
  var vtotal=0;
						  
  //alert(form1.flete.value);
  vsubtotal=eval(form1.flete.value)+eval(form1.entdom.value)+eval(form1.tadic.value)+eval(form1.sadic.value)+eval(form1.corres.value);
  vsubtotal=vsubtotal+eval(form1.seguro.value)+eval(form1.embalaje.value);
  form1.subtotal.value=vsubtotal;
  
  viva=vsubtotal*0.12;  
  form1.iva.value=viva;
  
  vtotal=vsubtotal+viva-eval(form1.descuento.value);
  form1.vtotal.value=vtotal;
}

function calcular_new()
{
  var vsubtotal=0;
  var viva=0;
  var vtotal=0;
						  
  //alert(form1.flete.value);
  vsubtotal=eval(form1.flete.value)+eval(form1.corres.value);  
  form1.subtotal.value=vsubtotal;
  
  viva=vsubtotal*0.12;  
  form1.iva.value=viva;
  
  vtotal=vsubtotal+viva-eval(form1.descuento.value);
  form1.vtotal.value=vtotal;
}
</script>
                            <tr> 
                              <td>Flete / Carga</td>
                              <td><input type="text" name="flete" value="<?php 
							    if(!isset($flete))
								  echo "0";
								  else
								  echo $flete;
								?>" onChange="calcular_new();">
								</td>
                            </tr>
                            <!--
							<tr> 
                              <td>Entrega Domicilio</td>
                              <td><input type="text" name="entdom" value="<?php 
							    if(!isset($entdom))
								  echo "0";
								  else
								  echo $entdom;
								?>" onChange="calcular();"></td>
                            </tr>
                            <tr> 
                              <td>Transp. Adicional</td>
                              <td><input type="text" name="tadic" value="<?php 
							    if(!isset($tadic))
								  echo "0";
								  else
								  echo $tadic;
								?>" onChange="calcular();"></td>
                            </tr>
                            <tr> 
                              <td>Servicio Adicional</td>
                              <td><input type="text" name="sadic" value="<?php 
							    if(!isset($sadic))
								  echo "0";
								  else
								  echo $sadic;
								?>" onChange="calcular();"></td>
                            </tr>
                            -->
							<tr> 
                              <td>Correspondencia</td>
                              <td><input type="text" name="corres" value="<?php 
							    if(!isset($corres))
								  echo "0";
								  else
								  echo $corres;
								?>" onChange="calcular_new();"></td>
                            </tr>
                            <!--
							<tr> 
                              <td>Seguros</td>
                              <td><input type="text" name="seguro" value="<?php 
							    if(!isset($seguro))
								  echo "0";
								  else
								  echo $seguro;
								?>" onChange="calcular();"></td>
                            </tr>
                            <tr> 
                              <td>Embalaje</td>
                              <td><input type="text" name="embalaje" value="<?php 
							    if(!isset($embalaje))
								  echo "0";
								  else
								  echo $embalaje;
								?>" onChange="calcular();"></td>
                            </tr>
                            -->
							<tr> 
                              <td>Subtotal</td>
                              <td><input type="text" name="subtotal" value="<?php 
							    if(!isset($subtotal))
								  echo "0";
								  else
								  echo $subtotal;
								?>" disabled>
                              </td>
                            </tr>
                            <tr> 
                              <td>Desc.</td>
                              <td><input type="text" name="descuento" value="<?php 
							    if(!isset($descuento))
								  echo "0";
								  else
								  echo $descuento;
								?>" onChange="calcular_new();"></td>
                            </tr>
                            <tr> 
                              <td>IVA</td>
                              <td><input type="text" name="iva" value="<?php 
							    if(!isset($iva))
								  echo "0";
								  else
								  echo $iva;
								?>" disabled></td>
                            </tr>
                            <tr> 
                              <td class="table_hd">Valor Total $</td>
                              <td><input type="text" name="vtotal" value="<?php 
							    if(!isset($vtotal))
								  echo "0";
								  else
								  echo $vtotal;
								?>" disabled>
                                <input type="button" name="Button" value="Calcular" onClick="calcular_new();"></td>
                            </tr>
                          </table></td>
                      </tr>					  
                    </table></td>
                </tr>
				<tr>
				  <td colspan="3" align="center">
				    <input type="submit" name="procesar" value="Procesar">
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
