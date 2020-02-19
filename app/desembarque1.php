<?php 
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php');

  extract($_REQUEST);		
  require_once('includes/header.php');
  $username=$sUsername;
		
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
		
  $principal="desembarque.php";
  $param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
?>
	
	<br>
	<form action="desembarque2.php" method="post" name="form1">
	<hr width="100%" align="center" size="2">

	<input name="Apply" type="submit" id="Apply"  value="Guardar">
	<input type="button" name="back" value="Cerrar" onClick="self.location='<?=$principal.$param_destino?>';">
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
					Bultos recibidos en Desembarque&nbsp;</font></SPAN>
				</td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
					<TR BGCOLOR="#CCCCCC">						
                      <td nowrap class='table_hd'>&nbsp;</td>						
                      
                    <td nowrap class='table_hd'>Bulto (Fecha:Origen:Destino)</td>
                    <td nowrap class='table_hd'>Ubicar Paquetes</td>
					</TR>
					<?php
					
					  include_once("class/c_manifiesto_embarque.php");
					  $oManEmb=new c_manifiesto_embarque($conn,$sUsername);
					  $oManEmb->info($id);
					  
					  $sql=<<<va
	select distinct bul_ref as identificador
	from manembxbultoreal
	where 
	manemb_id=$oManEmb->manemb_id
	order by identificador
va;
						$rs = &$conn->Execute($sql);
						$cont=0;
						include_once("class/c_bulto.php");
						$oBulto=new c_bulto($conn,$sUsername);
						while(!$rs->EOF)
						{
		  					$core_id=$rs->fields[0];
							$oBulto->info($core_id);
		  					$core_des=$oBulto->bul_fecha.":".$oBulto->bul_origen.":".$oBulto->bul_destino.":".$oBulto->bul_ref;
					?>
					<TR valign=top bgcolor='#ffffff'>
						<TD valign=top nowrap> 
						  <input type='checkbox' name='chc[<?=$cont?>]' value='<?=$core_id?>' 
						<?php
						  $sqlVal=<<<va
						  select nvl(count(bul_ref),0)
						  from mandesxbulto
						  where 
						  manemb_id=$oManEmb->manemb_id
						  and bul_ref='$core_id'
va;
						  $rs1=$conn->Execute($sqlVal);
						  $existe=$rs1->fields[0];
						  $desactivarBoton=" disabled=\"disabled\"";
						  if($existe)
						  {
						    echo " checked";
						    $desactivarBoton= " ";
						  }
						?>
						>&nbsp;
						<input type="hidden" name="cad[<?=$cont?>]" value="<?=$core_id?>" >
 						</TD>
						<TD valign=top nowrap><?=$core_des?>&nbsp;</TD>
						<TD valign=top nowrap>
						  <input type="button" name="bot[<?=$cont?>]" value="Ubicar Paquetes" onclick="fOpenWindow('ubicacionpaq.php?id_aplicacion=<?=$id_aplicacion?>&id_subaplicacion=<?=$id_subaplicacion?>&principal=<?=$principal?>&idp=<?=$id?>&idBul=<?=$core_id?>','BulxManE','450','550')" <?=$desactivarBoton?> >
						</TD>
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