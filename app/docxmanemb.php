<?php 
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php');

  extract($_REQUEST);		
  require_once('includes/header.php');
  $username=$session_username;
		
  /*
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  */
		
  $principal="docxmanemb.php";
  $param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
?>
	
	<br>
	<form action="docxmanemb1.php" method="post" name="form1">
	

	<hr width="100%" align="center" size="2">

	<input name="Apply" type="submit" id="Apply"  value="Guardar">
	<input type="button" name="back" value="Cerrar Ventana" onClick="window.close();">
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
	<input type="hidden" name="idp" value="<?=$idp?>">
	
<SCRIPT LANGUAGE="JavaScript">
function valida() {
}
</script>	
<br>			
			
	<TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR><TD>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
			<tr>
				<td nowrap><SPAN class="title" STYLE="cursor:default;">
					<img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
					Documentos por Manifiesto de Embarque&nbsp;</font></SPAN>
				</td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
					<TR BGCOLOR="#CCCCCC">						
                      <td nowrap class='table_hd'>&nbsp;</td>						
                      
                    <td nowrap class='table_hd'>Tipo Doc.</td>
					<td nowrap class='table_hd'>Documento</td>
					<td nowrap class='table_hd'>Fecha</td>
					</TR>
					<?php
					  include_once("class/c_manifiesto_embarque.php");
					  $oManEmb=new c_manifiesto_embarque($conn,$sUsername);
					  $oManEmb->info($idp);
					  
					  include_once("class/c_documento.php");
					  $oDoc=new c_documento($conn,$sUsername,$sTerminal);
					  $cadIdDoc=$oDoc->armarSqlId("d.","d.","d.","d.");
					  $sql=<<<va
	select distinct $cadIdDoc as identificador, st.stotip_nombre
	from documento d, stock_tipo st
	where 
	st.stotip_id=d.stotip_id 
	and doc_origen='$sEstacion' 
	and doc_destino='$oManEmb->manemb_destino' 
	and d.ofi_id||':'||d.stotip_id||':'||d.sto_id||':'||d.sto_nro not in 
    (
      select distinct d.ofi_id||':'||d.stotip_id||':'||d.sto_id||':'||d.sto_nro 
      from manemb_detalle d 
      where manemb_id<>$idp
    )

	order by identificador
va;
	//echo "<hr>$sql<hr>";					
					  $rs = &$conn->Execute($sql);
						$cont=0;
						while(!$rs->EOF)
						{
		  					$core_id=$rs->fields[0];
							$tipoDoc=$rs->fields[1];
							$oDoc->info($core_id);
		  					$core_des=$oDoc->stotip_id.":".$oDoc->sto_id.":".$oDoc->sto_nro;
					?>
					<TR valign=top bgcolor='#ffffff'>
						<TD valign=top nowrap> 
						  <input type='checkbox' name='chc[<?=$cont?>]' value='<?=$core_id?>' 
						<?php
						  $sqlVal=<<<va
						  select nvl(count(manemb_id),0)
						  from manemb_detalle
						  where 
						  manemb_id=$idp
						  and sto_id='$oDoc->sto_id'
						  and ofi_id='$oDoc->ofi_id'
						  and stotip_id='$oDoc->stotip_id'
						  and sto_nro='$oDoc->sto_nro'
va;
						  $rs1=$conn->Execute($sqlVal);
						  $existe=$rs1->fields[0];
						  if($existe)
						    echo " checked";
						?>
						>&nbsp;
						<input type="hidden" name="cad[<?=$cont?>]" value="<?=$core_id?>" >
 						</TD>
						<TD valign=top nowrap><?=$tipoDoc?>&nbsp;</TD>
						<TD valign=top nowrap><?=$oDoc->sto_nro?>&nbsp;</TD>
						<TD valign=top nowrap><?=$oDoc->doc_fecharec?>&nbsp;</TD>
					</TR>
					<?php
							$cont=$cont+1;
							$rs->MoveNext();
						}
					?>					
				</TABLE>
				</TD></TR>
			</TABLE>				
			<TR>
			  <TD>
				<input type="hidden" name="total" value="<?=$cont?>">
				<input type="hidden" name="id" value="<?=$id?>">
				<input type="hidden" name="cextra" value="id_aplicacion|id_subaplicacion|principal|id">	
			  </td>
        	</tr>
		</table>      

		</table> 	
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>