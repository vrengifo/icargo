<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
/*  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);*/
  $vf_act=date("Y-m-d");
?>

<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	<form action="#" method="post" name="form1">
	<?php
		$principal="reporteDocumentoAudit.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
<?php	        
	  	include_once("class/c_documento.php");
		$oDoc=new c_documento($conn,$sUsername,$sTerminal);
		$oDoc->info($id);
		$res=$oDoc->historial($id);
		
		include_once("class/c_stock_tipo.php");
		$oStoTip=new c_stock_tipo($conn);
		$oStoTip->info($oDoc->stotip_id);
		
		include_once("class/c_cliente.php");
		$oCliente=new c_cliente($conn);
		$oCliente->info($oDoc->cli_codigo);
?>

<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
  <TR>
    <TD>
	  <table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		<tr>
		  <td nowrap>
		    <SPAN class="title" STYLE="cursor:default;">
			  <img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2>
			  <font color="#FFFFFF">Documento</font>
			</SPAN>
		  </td>
		</tr>
	  </table>
	  <table WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
	    <TR>
		  <TD>
		    <TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td nowrap class='table_hd'>Tipo de Documento</td>
                  <td nowrap class='table_hd'>Nro</td>
                  <td nowrap class='table_hd'>Fecha</td>
				  <td nowrap class='table_hd'>Remitente</td>
                  <td nowrap class='table_hd'>Destinatario</td>
                </tr>
              <TR  bgcolor='#ffffff'> 
                  <td nowrap ><?=$oStoTip->stotip_nombre?></td>
                  <td nowrap ><?=$oDoc->completarCeros($oDoc->sto_nro,7)?></td>
                  <td nowrap ><?=$oDoc->doc_fecharec?></td>
				  <td nowrap ><?=$oCliente->cli_nombre?></td>
                  <td nowrap ><?=$oDoc->doc_destnombre?></td>
                </tr> 
              </table>
        </td>
      </tr>
      </table>
    </td>
  </tr>
</table>             
                
<br>
<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
  <TR>
    <TD>
	  <table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		<tr>
		  <td nowrap>
		    <SPAN class="title" STYLE="cursor:default;">
			  <img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2>
			  <font color="#FFFFFF">Historial</font>
			</SPAN>
		  </td>
		</tr>
	  </table>
	  <table WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
	    <TR>
		  <TD>
		    <TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td width="33%" nowrap class='table_hd'>Estado</td>
                  <td width="33%" nowrap class='table_hd'>Usuario</td>
                  <td width="34%" nowrap class='table_hd'>Fecha</td>
				  
				  
                </tr>
<?php
		for($i=0;$i<count($res);$i++)
		{
		  $vEstado=$res[$i]["estado"];
		  $vUsuario=$res[$i]["usuario"];
		  $vFecha=$res[$i]["fecha"];
?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD valign=top nowrap> 
                    <?=$vEstado?>
                  </TD>
                  <TD valign=top nowrap> 
                    <?=$vUsuario?>
                  </TD>
                  <TD valign=top nowrap> 
                    <?=$vFecha?>
                  </TD>
                </TR>
<?php
		}
?>
              </TABLE>
		</TABLE>  
	  </TABLE>
		<input type="hidden" name="cextra" value="<?=$cextra?>">
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
