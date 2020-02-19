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
		
  $principal="ubicacionpaq.php";
  $param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
  
  include_once("class/c_bulto.php");
  $oBulto=new c_bulto($conn,$sUsername);
  
  $oBulto->info($idBul);
?>
	
	<br>
	<form action="ubicacionpaq1.php" method="post" name="form1">
	<hr width="100%" align="center" size="2">
	<input name="Apply" type="submit" id="Apply"  value="Guardar">
	<input type="button" name="back" value="Cerrar Ventana" onClick="window.close();">
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
	<input type="hidden" name="idp" value="<?=$idp?>">
	<input type="hidden" name="idBul" value="<?=$idBul?>">
	
<SCRIPT LANGUAGE="JavaScript">
function valida() {
}
</script>	
<br>			
			
	<TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR><TD>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
			<tr>
			  <td nowrap>
			    <SPAN class="title" STYLE="cursor:default;">
			      <img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2>
			      <font color="#FFFFFF">
				    Ubicación de Paquetes en Bulto #: <?=$oBulto->bul_ref?> 
				    &nbsp;&nbsp;&nbsp;(<?=$oBulto->bul_origen?>-<?=$oBulto->bul_destino?>)
				  </font>
				</SPAN>
			  </td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
					<TR BGCOLOR="#CCCCCC">
                      <td nowrap class='table_hd'>&nbsp;</td>
                      <td nowrap class='table_hd'>Paquete</td>
                      <td nowrap class='table_hd'>Ubicación</td>
					</TR>
					<?php
					  include_once("class/c_detalle_bulto.php");
					  $oDetBul=new c_detalle_bulto($conn,$sUsername);
					  
					  include_once("class/c_des_paquete.php");
					  $oDesPaq=new c_des_paquete($conn,$sUsername);
					  
					  $cadId=$oDetBul->armarSqlId("","");
					  $sql=<<<va
	select distinct $cadId as identificador,detdoc_ref 
	from detalle_bulto
	where 
	bul_ref='$oBulto->bul_ref'
	order by identificador
va;
						$rs = &$conn->Execute($sql);
						$cont=0;
						while(!$rs->EOF)
						{
		  					$core_id=$rs->fields[0];
		  					$core_des=$rs->fields[1];
		  					
		  					$oDetBul->cad2id($core_id);
		  					
		  					$idDesPaq=$oDesPaq->id2cad($idp,$oDetBul->detdoc_ref,$oBulto->bul_ref);
		  					$resDesPaq=$oDesPaq->info($idDesPaq);
		  					//if((strlen($oDesPaq->ubicacion)>0)&&($resDesPaq!="0"))
		  					if(($idDesPaq==$resDesPaq)&&($resDesPaq!="0"))
		  					  $textoChecked=" checked ";
					?>
					<TR valign=top bgcolor='#ffffff'>
						<TD valign=top nowrap> 
						  <input type='checkbox' name='chc[<?=$cont?>]' value='<?=$core_id?>' <?=$textoChecked?>>&nbsp;
						  <input type="hidden" name="cad[<?=$cont?>]" value="<?=$core_id?>" >
 						</TD>
						<TD valign=top nowrap><?=$core_des?>&nbsp;</TD>
						<TD valign=top nowrap>
						  <input type="text" name="ubi[<?=$cont?>]" value="<?=$oDesPaq->ubicacion?>">
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
?>
</body>
</html>