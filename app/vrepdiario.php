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
<?php
  if(!isset($Procesar))
    $repven_nro=date("Y-m-d");
   else
    $repven_nro=$fecha;	
  //rec info en base a id
  $sql="select e.est_nombre,c.ciu_descripcion "
  	  ."from usuario u,estacion e,ciudad c "
	  ."where u.usu_codigo='$sUsername' and e.est_id=u.est_id and c.ciu_codigo=e.ciu_codigo ";	  

  $rs=&$conn->Execute($sql);
  if(!$rs->EOF)
  {
    $est_nombre=$rs->fields[0];
	$ciu_descripcion=$rs->fields[1];	
  }	  
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
                          Reporte Diario de Ventas Nro. <input type="text" name="fecha" value="<?php if(!isset($fecha)) echo"$repven_nro"; else echo "$fecha"; ?>" >
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
                  <td width="100%" valign="top" nowrap  bgcolor='#ffffff'> <div align="right"> 
                      <table width="100%" border="0">
                        <tr> 
                          <td width="120" height="66"><img src="images/logo.gif" width="120" height="66"></td>
                          <td><table width="100%" border="0">
                              <tr> 
                                <td>
								  Fecha: <?php $factual=date("Y-m-d H:i"); ?><?=$factual?>
								  <input type="hidden" name="repven_fecha" value="<?=$factual?>">
								</td>
                                <td>Elaborado Por: 
                                  <?=$sUsername?>
                                </td>
                              </tr>
                              <tr> 
                                <td>Origen (Est-Ciudad): 
                                  <?=$est_nombre?> - <?=$ciu_descripcion?>
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
                          <td class="table_hd">Nro Gu&iacute;a / Factura</td>
                          <td class="table_hd">PREPAID</td>
                          <td class="table_hd">COLLECT</td>
                          <td class="table_hd">CREDITO</td>
                          <td class="table_hd">&nbsp;</td>
                        </tr>
                        <?php
						  $sql="select gui_id,gui_nro,tipgui_id,gui_total "
						  	  ."from guia "
							  ."where usu_codigo='$sUsername' "
							  ." and repven_id=0 " //no asignadas a reporte de ventas
							  ." and to_date(gui_fecha,'YYYY-MM-DD')<='$repven_nro' " //con fecha menor o igual a la de $repven_nro							  
							  ."order by gui_fecha";
						  $rs=&$conn->Execute($sql);
						  $total_fil=0;
						  while(!$rs->EOF)
						  {
						    $gui_id=$rs->fields[0];
							$gui_nro=$rs->fields[1];
							$tipgui_id=$rs->fields[2];
							$gui_total=$rs->fields[3];	  
						?>
						<tr> 
                          <td>
						    <?=$gui_nro?>
							<input type="hidden" name="guia[<?=$total_fil?>]" value="<?=$gui_id?>">
						  </td>
                          <td>
						    <?php
							  if($tipgui_id=="PR")
							    echo "$gui_total";
							  else
							    echo "-";							  	
							?>
						  </td>
                          <td>
						    <?php
							  if($tipgui_id=="CO")
							    echo "$gui_total";
							  else
							    echo "-";
							?>
						  </td>
                          <td>
						    <?php
							  if($tipgui_id=="CR")
							    echo "$gui_total";
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
                          <td class="table_hd">TOTALES <input type="hidden" name="total_fil" value="<?=$total_fil?>"></td>						  
                          <td>
						    <?php
							  $sql="select sum(gui_total) "
						  	  ."from guia "
							  ."where usu_codigo='$sUsername' "
							  ." and repven_id=0 " //no asignadas a reporte de ventas
							  ." and to_date(gui_fecha,'YYYY-MM-DD')<='$repven_nro' " //con fecha menor o igual a la de $repven_nro
							  ."and tipgui_id='PR'";
						  	  $rss=&$conn->Execute($sql);							  
							  $valor_pr=$rss->fields[0];
							  if(strlen($valor_pr)<=0) 
							    $valor_pr=0;	
							?>
							<?=$valor_pr?>
						  </td>
                          <td>
						    <?php
							  $sql="select sum(gui_total) "
						  	  ."from guia "
							  ."where usu_codigo='$sUsername' "
							  //."and to_char(gui_fecha,'YYYY-MM-DD')='$repven_nro' "
							  ." and repven_id=0 " //no asignadas a reporte de ventas
							  ." and to_date(gui_fecha,'YYYY-MM-DD')<='$repven_nro' " //con fecha menor o igual a la de $repven_nro
							  ."and tipgui_id='CO'";
						  	  $rss=&$conn->Execute($sql);							  
							  $valor_co=$rss->fields[0];
							  if(strlen($valor_co)<=0) 
							    $valor_co=0;	
							?>
							<?=$valor_co?>
						  </td>
                          <td>
						    <?php
							  $sql="select sum(gui_total) "
						  	  ."from guia "
							  ."where usu_codigo='$sUsername' "
							  ." and repven_id=0 " //no asignadas a reporte de ventas
							  ." and to_date(gui_fecha,'YYYY-MM-DD')<='$repven_nro' " //con fecha menor o igual a la de $repven_nro
							  //."and to_char(gui_fecha,'YYYY-MM-DD')='$repven_nro' "
							  ."and tipgui_id='CR'";
						  	  $rss=&$conn->Execute($sql);							  
							  $valor_cr=$rss->fields[0];
							  if(strlen($valor_cr)<=0) 
							    $valor_cr=0;	
							?>
							<?=$valor_cr?>
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
                                <td width="54%" class="table_hd">PREPAID</td>
                                <td width="46%"><?=$valor_pr?><input type="hidden" name="rtotal_cash" value="<?=$valor_pr?>"></td>
                              </tr>
                              <tr>
                                <td class="table_hd">COLLECT</td>
                                <td><?=$valor_co?><input type="hidden" name="rtotal_collect" value="<?=$valor_co?>"></td>
                              </tr>
                              <tr>
                                <td class="table_hd">CREDITO</td>
                                <td><?=$valor_cr?><input type="hidden" name="rtotal_credito" value="<?=$valor_cr?>"></td>
                              </tr>
                              <tr>
                                <td class="table_hd">TOTAL</td>
                                <td class="table_hd">
								  <?php
								    $total_reporte=$valor_pr+$valor_co+$valor_cr;
								  ?>
								  <?=$total_reporte?>
								  <input type="hidden" name="rtotal" value="<?=$total_reporte?>">
								</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                      &nbsp; </div></td>
                </tr>
                <tr> 
                  <td align="center"><input name="Guardar" type="submit" value="Guardar" onClick="cambiar_action(form1,'repdiario1.php')" > 
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
