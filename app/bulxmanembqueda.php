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
		
  $principal="bulxmanembqueda.php";
  $param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
?>
	
	<br>
	<form action="bulxmanembqueda1.php" method="post" name="form1">
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
					Bajar bultos de Manifiesto&nbsp;</font></SPAN>
				</td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
					<TR BGCOLOR="#CCCCCC">						
                      <td nowrap class='table_hd'>&nbsp;</td>						
                      
                    <td nowrap class='table_hd'>Bulto (Fecha:Origen:Destino)</td>
					</TR>
					<?php
					  include_once("class/c_manifiesto_embarque.php");
					  $oManEmb=new c_manifiesto_embarque($conn,$sUsername);
					  $oManEmb->info($idp);
					  
					  $sql=<<<va
	select distinct bul_ref as identificador
	from manembxbulto
	where 
	manemb_id=$idp
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
						  from manembxbultoqueda
						  where 
						  manemb_id=$idp
						  and bul_ref='$core_id'
va;
						  $rs1=$conn->Execute($sqlVal);
						  $existe=$rs1->fields[0];
						  if($existe)
						    echo " checked";
						?>
						>&nbsp;
						<input type="hidden" name="cad[<?=$cont?>]" value="<?=$core_id?>" >
 						</TD>
						<TD valign=top nowrap><?=$core_des?>&nbsp;</TD>						
					</TR>
					<?php
							$cont=$cont+1;
							$rs->MoveNext();
						}
					?>	
					
					<?php
					//quedados
					  include_once("class/c_manifiesto_embarque.php");
					  $oManEmb=new c_manifiesto_embarque($conn,$sUsername);
					  $oManEmb->info($idp);
					  
					  $sql=<<<va
	select distinct (bq.bul_ref)
    from manembxbultoqueda bq, manembxbultoreal br
    where
    bq.bul_ref<>br.bul_ref
    and bq.manemb_id in 
    (
      select distinct manemb_id from manifiesto_embarque
      where manemb_origen='$oManEmb->manemb_origen' and manemb_destino='$oManEmb->manemb_destino'       )
    and bq.bul_ref not in 
    (  
      select distinct bul_ref 
	  from manembxbulto
	  where 
	  manemb_id=$idp
    )  
va;

					  //echo "<hr>$sql<hr>";
						$rs = &$conn->Execute($sql);
						//$cont=0;
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
						  from manembxbultoqueda
						  where 
						  manemb_id=$idp
						  and bul_ref='$core_id'
va;
						  $rs1=$conn->Execute($sqlVal);
						  $existe=$rs1->fields[0];
						  if($existe)
						    echo " checked";
						?>
						>&nbsp;
						<input type="hidden" name="cad[<?=$cont?>]" value="<?=$core_id?>" >
 						</TD>
						<TD valign=top nowrap><?=$core_des?>&nbsp;</TD>						
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