<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
		/*
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion);		
		*/
		$vf_act=date("Y-m-d");
		///todo  el html como se quiera
	?>

<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>

	
	<br>
	<form action="rdv_credito.php" method="post" name="form1">
<?php	        
      if(isset($cli_id))
	  {  
		/*
		//select de info de guías
		include_once("class/c_user.php");
		$cuser=new c_user();
		$estacion_usuario=$cuser->recuperar_estacion($conn,$username);
		$ciudad=$cuser->recuperar_ciudad($conn,$username);
		*/
		include_once("class/c_cliente.php");
		$oCliente=new c_cliente($conn);
		
		$oCliente->info($cli_id);
		/*
		$sql="select cli_descripcion "
		    ."from cliente "
		    ."where cli_codigo=$cli_id ";	
		$rs=&$conn->Execute($sql);
		
		$vclinombre=$rs->fields[0];		
		*/
	echo "<br>";

		//echo $sql."<br>";
?>

<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
  <TR>
    <TD>
	  <table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		<tr>
		  <td nowrap>
		    <SPAN class="title" STYLE="cursor:default;">
			  <img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2>
			  <font color="#FFFFFF">Listado de Guías a Facturar - Cliente <?=$vclinombre?></font>
			</SPAN>
		  </td>
		</tr>
	  </table>
	  <table WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
	    <TR>
		  <TD>
		    <TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td width="3%" nowrap class='table_hd'>Item</td>
                  <td width="10%" nowrap class='table_hd'>Tipo Documento</td>
				  <td width="9%" nowrap class='table_hd'>Nro. Documento </td>
                  <td width="7%" nowrap class='table_hd'>Fecha</td>
                  <td width="17%" nowrap class='table_hd'>Nombre Destinatario </td>
                  <td width="13%" nowrap class='table_hd'>RUC / CI Destinatario </td>
                  <td width="9%" nowrap class='table_hd'>Valor</td>                  
                  <td width="12%" nowrap class='table_hd'>IVA</td>
                  <td width="20%" nowrap class='table_hd'>TOTAL</td>
                </tr>
                <?php
		$tipo_guia="C"; //crédito		
		$sqlg="select d.sto_nro,to_char(d.doc_fecharec,'YYYY-MM-DD'),d.doc_destnombre,d.doc_destciruc,d.doc_subtotal,st.stotip_nombre,d.doc_iva,d.doc_total "
			 ."from documento d, stock_tipo st "
			 ."where d.cli_codigo='$cli_id' "
			 ."and st.stotip_id=d.stotip_id "
			 ."and d.doc_formapago='$tipo_guia' "
			 ."and to_char(d.doc_fecharec,'YYYY-MM-DD')>='$fecha_ini' "
			 ."and to_char(d.doc_fecharec,'YYYY-MM-DD')<='$fecha_fin' ";
		//echo"<hr>$sqlg<hr>";	 
		$rs=&$conn->Execute($sqlg);
		$item=1;	 
		while(!$rs->EOF)
		{
		    $vgui_nro=$rs->fields[0];
		    $vgui_fecha=$rs->fields[1];
		    $vgui_remitente=$rs->fields[2];
			$vgui_ced_remitente=$rs->fields[3];
		    $vgui_subtotal=$rs->fields[4];
		    $vgui_tipodocumento=$rs->fields[5];
		    $vgui_iva=$rs->fields[6];
			$vgui_total=$rs->fields[7];			
?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD valign=top nowrap> 
                    <?=$item?>
                  </TD>
				  <TD valign=top nowrap > 
                    <?=$vgui_tipodocumento?>
                    <div align="left"></div></TD>
                  <TD valign=top nowrap> 
                    <?=$vgui_nro?>
                  </TD>
                  <TD valign=top nowrap > 
                    <?=$vgui_fecha?>
                    <div align="left"></div></TD>
                  <TD valign=top nowrap > 
                    <?=$vgui_remitente?>
                    <div align="left"></div></TD>
                  <TD valign=top nowrap > 
                    <?=$vgui_ced_remitente?>
                    <div align="left"></div></TD>
                  <TD valign=top nowrap > 
                    <?=$vgui_subtotal?>
                    <div align="left"></div></TD>
                  
                  <TD valign=top nowrap > 
                    <?=$vgui_iva?>
                    <div align="left"></div></TD>
                  <TD valign=top nowrap > 
                    <?=$vgui_total?>
                    <div align="left"></div></TD>
                </TR>
                <?php
			$rs->MoveNext();
			$item=$item+1;
		}// fin del while q recupera la info de los aviones
?>
              </TABLE>
		</TABLE>  
	  </TABLE>
<?php
					
	  }//fin del isset procesar
?>
		<input type="hidden" name="cextra" value="<?=$cextra?>">
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
