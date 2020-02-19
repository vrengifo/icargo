<?php 
  session_start();
  
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  /*
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  */
  
  include_once("class/c_documento.php");
  $oDoc=new c_documento($conn,$sUsername,$sTerminal);
  $oDoc->info($id);

  //include_once("class/c_stock_historial.php");
  //$oStoHis=new c_stock_historial($conn,$username);
  //$oStoHis->anular($id,$tObservacion);
  $dObservacion="-";
  if(strlen($tObservacion)>0)
    $dObservacion=$tObservacion;
  $oDoc->anular($id,$dObservacion);
  
  /*
  if(isset($subAct))
  {
    include_once("class/c_kiloequivalenciaxcli.php");
    $oKilEqxCli=new c_kiloequivalenciaxcli($conn,$sUsername);
    $costoKilo=$oKilEqxCli->recuperarCosto($tDocOrigen,$seDocDestino,$seCliente);
    
    include_once("class/c_parametro.php");
    $oParametro=new c_parametro($conn);
    $oParametro->info();
  }
  */
  
?>
<br>
<form action="#" method="post" name="form1">
<input type="hidden" name="principal" value="<?=$principal?>">
<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
<input type="hidden" name="id" value="<?=$id?>">
  <h1>Documento Anulado!!!</h1>
  <input type="button" name="bCancelar" value="Cancelar" onclick="window.close();">
  <br />
  <TABLE WIDTH="80%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR>
	  <TD>
		<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		  <tr>							
            <td nowrap>
			  <SPAN class="title" STYLE="cursor:default;"> 
                <img src="images/360/taskwrite.gif" border=0 align=absmiddle HSPACE=2>
				<font color="#FFFFFF">
				<?php
				  include_once("class/c_stock_tipo.php");
				  $oStoTip=new c_stock_tipo($conn);
				  $sql=$oStoTip->info($oDoc->stotip_id);
				?>
                  <?=$oStoTip->stotip_nombre?> 
				  Nro:
				  <?=$oDoc->sto_nro?>				    
				</font>
			  </SPAN>
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
                            <?=$oDoc->doc_fecharec?>
                          </td>
                          <td> 
                            <?=$oDoc->doc_nropiezas?>
						  </td>
                          <td>
						    <?=$oDoc->doc_peso?>
                          </td>
                          <td> 
                            <?=$oDoc->doc_volumen?>
                          </td>
                          <td> 
                            <?=$oDoc->doc_valordeclarado?>
                          </td>
                        </tr>
                        <tr> 
                          <td colspan="5" class="table_hd">Contenido Declarado</td>
                        </tr>
                        <tr> 
                          <td>Tipo de Carga</td>
                          <td colspan="4">
                             <?php
							   include_once("class/c_tipo_carga.php");
							   $oTipCar=new c_tipo_carga($conn);
                               $sql=$oTipCar->info($oDoc->tipcar_id);
							 ?> 
							  <?=$oTipCar->tipcar_descripcion?>
                          </td>
                        </tr>
                        <tr> 
                          <td>Descripci&oacute;n (Contenido)</td>
                          <td colspan="4"><?=$oDoc->doc_descripcion?></td>
                        </tr>
                        <tr> 
                          <td>Sobre con Documentos</td>
                          <td colspan="4">
                            <?php
                              include_once("class/c_verdadfalso.php");
                              $oVF=new c_verdadfalso($conn);
                              $sql=$oVF->info($oDoc->doc_sobredocumento);
                            ?>
                            <?=$oVF->vf_texto?>
                          </td>
                        </tr>
                        <tr> 
                          <td class="table_hd">Observaciones</td>
                          <td colspan="4"><?=$oDoc->doc_observacion?></td>
                        </tr>
                        <tr>
                          <td colspan="5">
						    <p>Sin dinero ni joyas </p>
                            <p>Viaja a cuenta y riesgo del Remitente </p>
						  </td>
                        </tr>
                        <tr> 
                          <td>C&oacute;digos Barra Carga: </td>
                          <td colspan="4">
						  <?php
						    echo($oDoc->CargaDocumento($id));
						  ?>
						  </td>
                        </tr>
                      </table>
                    </div></td>
                  <td width="10%"></td>
                  <td width="45%" nowrap  bgcolor='#ffffff'> <table width="100%" border="1">
                      <tr> 
                        <td width="26%"  class="table_hd">Procedencia</td>
						<?php
						  $sql=<<<va
						  select e.est_codigo,e.est_nombre 
						  from estacion e, oficina o
						  where 
						  o.ofi_id='$oDoc->ofi_id'
						  and e.est_codigo=o.est_codigo
va;
						  $rs=&$conn->Execute($sql);
						  $estCodigo=$rs->fields[0];
						  $estNombre=$rs->fields[1];						  
						?>
                        <td>
                          <?=$estNombre?>               
                        </td>
                      </tr>
                      <tr> 
                        <td>Cliente </td>
                        <td width="15%">
						  <?php
						    include_once("class/c_cliente.php");
						    $oCliente=new c_cliente($conn);
						    $sql=$oCliente->info($oDoc->cli_codigo);
						  ?>  
						  <?=$oCliente->cli_nombre?>   
                        </td>
                      </tr>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%">
                          <?=$oCliente->cli_ciruc?>
                        </td>  
                      </tr>
					  <tr> 
                        <td>Remite </td>
                        <td width="15%">
                          <?=$oCliente->cli_nombre?>
                        </td>
                      </tr>
                      <tr> 
                        <td>Dirección</td>
                        <td width="15%">
                          <?=$oCliente->cli_direccion?>
                        </td>
                      </tr>
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%">
                          <?=$oCliente->cli_telefono?>
                        </td>
                      </tr>
                      <tr> 
                        <td class="table_hd">Destino</td>
                        <td>
                         <?php
                            include_once("class/c_estacion.php");
                            $oEst=new c_estacion($conn);
						    $sql=$oEst->info($oDoc->doc_destino);
						 ?>
                         <?=$oEst->est_nombre?>
                        </td>
                      </tr>					  
                      <tr> 
                        <td  class="table_hd">Destinatario</td>
                        <td width="15%"><?=$oDoc->doc_destnombre?></td>
                      </tr>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%">
						  <?=$oDoc->doc_destciruc?>
						</td>
                      </tr>
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%"><?=$oDoc->doc_desttelf?></td>
                      </tr>
                      <tr> 
                        <td colspan="2"><table width="100%" border="1">
                            <tr> 
                              <td class="table_hd">DETALLE</td>
                              <td class="table_hd">
							    <p>
								  <?php
								    if($oDoc->doc_formapago=="C")
								      $fpago="CREDITO";
								    if($oDoc->doc_formapago=="E")
								      $fpago="EFECTIVO";  
								  ?>
								</p>
                              </td>
                            </tr>
                            <tr> 
                              <td>Carga / Corresp.</td>
                              <td><?=$oDoc->doc_costo?></td>
                            </tr>
							<tr> 
                              <td>Seguro</td>
                              <td><?=$oDoc->doc_seguro?></td>
                            </tr>
							<tr> 
                              <td>Subtotal</td>
                              <td><?=$oDoc->doc_subtotal?>
                              </td>
                            </tr>
                            <tr> 
                              <td>IVA</td>
                              <td><?=$oDoc->doc_iva?></td>
                            </tr>
                            <tr> 
                              <td class="table_hd">Valor Total $</td>
                              <td><?=$oDoc->doc_total?></td>
                            </tr>
                          </table></td>
                      </tr>					  
                    </table></td>
                </tr>
				<tr>
				  <td colspan="3" align="center">&nbsp;</td>
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
  
</form>	
<?php
  buildsubmenufooter();  
?>
</body>
</html>