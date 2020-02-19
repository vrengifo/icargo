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
	<form action="rdv_collect.php" method="post" name="form1">
<?php	        
      if(isset($est_id))
	  {  
		/*
		//select de info de guías
		include_once("class/c_user.php");
		$cuser=new c_user();
		$estacion_usuario=$cuser->recuperar_estacion($conn,$username);
		$ciudad=$cuser->recuperar_ciudad($conn,$username);
		*/
		
		$sql="select est_codigo,est_nombre "
		    ."from estacion "
		    ."where est_id=$est_id ";	
		$rs=&$conn->Execute($sql);
		
		$vest_codigo=$rs->fields[0];
		$vest_nombre=$rs->fields[1];
		$estacion_nombre=$vest_codigo." : ".$vest_nombre;		
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
			  <font color="#FFFFFF">Listado de Facturas Collect - Estación <?=$estacion_nombre?></font>
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
                  <td width="9%" nowrap class='table_hd'>Nro. Factura</td>
                  <td width="7%" nowrap class='table_hd'>Fecha</td>
                  <td width="17%" nowrap class='table_hd'>Nombre</td>
                  <td width="13%" nowrap class='table_hd'>RUC / CI</td>
                  <td width="9%" nowrap class='table_hd'>Valor</td>
                  <td width="10%" nowrap class='table_hd'>Descuento</td>
                  <td width="12%" nowrap class='table_hd'>IVA</td>
                  <td width="20%" nowrap class='table_hd'>TOTAL</td>
                </tr>
                <?php
		$tipo_guia="CO"; //contado		
		$sqlg="select gui_nro,to_char(gui_fecha,'YYYY-MM-DD'),gui_remitente,gui_ced_remitente,gui_subtotal,gui_descuento,gui_iva,gui_total "
			 ."from guia "
			 ."where est_origen=$est_id "
			 ."and tipgui_id='$tipo_guia' "
			 ."and to_char(gui_fecha,'YYYY-MM-DD')>='$fecha_ini' "
			 ."and to_char(gui_fecha,'YYYY-MM-DD')<='$fecha_fin' ";
		$rs=&$conn->Execute($sqlg);
		$item=1;	 
		while(!$rs->EOF)
		{
		    $vgui_nro=$rs->fields[0];
		    $vgui_fecha=$rs->fields[1];
		    $vgui_remitente=$rs->fields[2];
			$vgui_ced_remitente=$rs->fields[3];
		    $vgui_subtotal=$rs->fields[4];
		    $vgui_descuento=$rs->fields[5];
		    $vgui_iva=$rs->fields[6];
			$vgui_total=$rs->fields[7];			
?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD valign=top nowrap> 
                    <?=$item?>
                  </TD>
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
                    <?=$vgui_descuento?>
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
