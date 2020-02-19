<?php 
  session_start(); 
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);

  //include_once("class/c_reporte_venta.php");
  //$oRepVen=new c_reporte_venta($conn,$sUsername);
    
  include_once("class/c_oficina.php");
  $oOfi=new c_oficina($conn);
  
  include_once("class/c_estacion.php");
  $oEst=new c_estacion($conn);
  
  $fechaAct=date("Y-m-d");
  if(!isset($fecha))
    $fecha=$fechaAct;
  else 
    $fecha=$fecha;  
    
/*  //rec info en base a id
  $sql="select e.est_nombre,c.ciu_descripcion "
  	  ."from usuario u,estacion e,ciudad c "
	  ."where u.usu_codigo='$sUsername' and e.est_id=u.est_id and c.ciu_codigo=e.ciu_codigo ";	  

  $rs=&$conn->Execute($sql);
  if(!$rs->EOF)
  {
    $est_nombre=$rs->fields[0];
	$ciu_descripcion=$rs->fields[1];	
  }	  */

  $oOfi->info($sOficina);
  $oEst->info($oOfi->est_codigo);
  
  $cadOfiUbi=$oOfi->ofi_nombre." ( ".$oEst->ciu_codigo." - ".$oEst->est_nombre." )";
  
?>	
	<br>

<script language="JavaScript">
function valida()
{

}
</script>
	
<form action="repdiario.php" method="post" name="form1">    
  <TABLE WIDTH="80%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR>
	  <TD>
		<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		  <tr>							
            <td nowrap><SPAN class="title" STYLE="cursor:default;"> 
                          <img src="images/360/taskwrite.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF"> 
                          Reporte Diario de Ventas <br>
                          Fecha: <input type="text" name="fecha" value="<?=$fecha?>">
              <a href="javascript:show_calendar('form1.fecha');" 
					  onmouseover="window.status='Date Picker';return true;" 
					   onmouseout="window.status='';return true;"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0> 
              </a></font></SPAN>
			  &nbsp; <input name="Procesar" type="submit" value="Procesar" >
			</td>
		  </tr>
		</table>
		<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
		  <TR>
		    <TD> 
			  <table width='100%' border=1 cellpadding=2 cellspacing=1 bgcolor='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td width="100%" valign="top" nowrap  bgcolor='#ffffff'> <div align="right">                       <table width="100%" border="0">
                        <tr> 
                          <td width="120" height="66"><img src="images/logo.gif" width="120" height="66"></td>
                          <td><table width="100%" border="0">
                              <tr> 
                                <td>
								  Fecha y Hora: <?php $factual=date("Y-m-d H:i"); ?><?=$factual?>
								</td>
                                <td>Elaborado Por: 
                                  <?=$sUsername?>
                                </td>
                              </tr>
                              <tr> 
                                <td>Oficina (Estación-Ciudad): 
                                  <?=$cadOfiUbi?>
                                </td>
                                <td>&nbsp;</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                      &nbsp; </div></td>
                </tr>
                <TR BGCOLOR="#CCCCCC"> 
                  <td valign="top" nowrap  bgcolor='#ffffff'> <div align="right"> 
                      <table width="100%" border="1">
                        <tr> 
                          <td class="table_hd">Documento</td>
                          <td class="table_hd">Nro Documento</td>
                          <td class="table_hd">EFECTIVO</td>
                          <td class="table_hd">CREDITO</td>
                          <td class="table_hd">&nbsp;</td>
                        </tr>
                        <?php
	include_once("class/c_documento.php");
	$oDoc=new c_documento($conn,$sUsername,$sTerminal);
	$cadId=$oDoc->armarSqlId("d.","d.","d.","d.");

	$sql=<<<va
	select st.stotip_nombre,d.sto_nro,d.doc_formapago,d.doc_total,
	$cadId  
	from stock_tipo st, documento d 
	where
	st.stotip_id=d.stotip_id 
	and d.usu_audit='$sUsername'
	and d.doc_origen='$sEstacion' 
	and nvl(d.repven_id,0)=0
	and to_char(d.doc_fecharec,'YYYY-MM-DD')<='$fecha'
	order by d.doc_fecharec
va;
//echo "<hr>sql:$sql<hr>";
							  
						  $rs=&$conn->Execute($sql);
						  $total_fil=0;
						  while(!$rs->EOF)
						  {
						    $stotip_id=$rs->fields[0];
							$sto_nro=$rs->fields[1];
							$formapago=$rs->fields[2];
							$doc_total=$rs->fields[3];
							$doc_id=$rs->fields[4];
						?>
						<tr> 
                          <td>
						    <?=$stotip_id?>
							<input type="hidden" name="doc[<?=$total_fil?>]" value="<?=$doc_id?>">
						  </td>
                          <td>
						    <?=$sto_nro?>
						  </td>
                          <td>
						    <?php
							  if($formapago=="E")
							    echo "$doc_total";
							  else
							    echo "-";
							?>
						  </td>
						  <td>
						    <?php
							  if($formapago=="C")
							    echo "$doc_total";
							  else
							    echo "-";							  	
							?>
						  </td>
                          <td>&nbsp;</td>
                        </tr>
						<?php
						    $total_fil=$total_fil+1;
							$rs->MoveNext();
						  }
						?>
                        <tr> 
                          <td class="table_hd" colspan="2">TOTALES <input type="hidden" name="total_fil" value="<?=$total_fil?>"></td>						  
                          <td>
						    <?php
							  $sql="select sum(doc_total) "
						  	  ."from documento "
							  ."where usu_audit='$sUsername' "
							  ."and doc_origen='$sEstacion' "
							  ." and nvl(repven_id,0)=0 " //no asignadas a reporte de ventas
							  ." and to_date(doc_fecharec,'YYYY-MM-DD')<='$fecha' " //con fecha menor o igual a la de $repven_nro
							  ."and doc_formapago='E'";
						  	  $rss=&$conn->Execute($sql);							  
							  $valor_pr=$rss->fields[0];
							  if(strlen($valor_pr)<=0) 
							    $valor_pr=0;	
							?>
							<?=$valor_pr?>
						  </td>
                          <td>
						    <?php
						    //cambio de fechaactual por fecha
							  $sql="select sum(doc_total) "
						  	  ."from documento "
							  ."where usu_audit='$sUsername' "
							  ."and doc_origen='$sEstacion' "
							  ." and nvl(repven_id,0)=0 " //no asignadas a reporte de ventas
							  ." and to_date(doc_fecharec,'YYYY-MM-DD')<='$fecha' " //con fecha menor o igual a la de $repven_nro
							  ."and doc_formapago='C'";
						  	  $rss=&$conn->Execute($sql);							  
							  $valor_co=$rss->fields[0];
							  if(strlen($valor_co)<=0) 
							    $valor_co=0;	
							?>
							<?=$valor_co?>
						  </td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr> 
                          <td colspan="4"><table width="100%" border="0">
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><hr width="100%"></td>
                                <td>&nbsp;</td>
                                <td><hr width="100%"></td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><div align="center">Elaborado por</div></td>
                                <td>&nbsp;</td>
                                <td><div align="center">Recib&iacute; Conforme</div></td>
                                <td>&nbsp;</td>
                              </tr>
                            </table></td>
                          <td><table width="100%" border="1">
                              <tr>
                                <td width="54%" class="table_hd">EFECTIVO</td>
                                <td width="46%"><?=round($valor_pr,2)?><input type="hidden" name="rtotal_cash" value="<?=$valor_pr?>"></td>
                              </tr>
                              <tr>
                                <td class="table_hd">CREDITO</td>
                                <td><?=round($valor_co,2)?><input type="hidden" name="rtotal_credito" value="<?=$valor_co?>"></td>
                              </tr>
                              <tr>
                                <td class="table_hd">TOTAL</td>
                                <td class="table_hd">
								  <?php
								    $total_reporte=$valor_pr+$valor_co;
								  ?>
								  <?=round($total_reporte,2)?>
								  <input type="hidden" name="rtotal" value="<?=$total_reporte?>">
								</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                      &nbsp; </div></td>
                </tr>
                <tr> 
                  <td align="center"><input name="Guardar" type="submit" value="Guardar" onClick="cambiar_action(document.form1,'repdiario1.php')" > 
                    <input type="button" name="Imprimir" value="Imprimir" onClick="print();"> 
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
