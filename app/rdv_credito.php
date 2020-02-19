<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
		buildmenu($username);
		buildsubmenu($id_aplicacion,$id_subaplicacion);		
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
		$principal="rdv_credito.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">		
	<table>
	<tr>
	  <td>	
	    <table>
          <tr> 
            <td>Cliente: </td>
            <td> <select name="cliente">
                <option value="-" 
			  <?php
			    if(!isset($cliente))
				{
				  echo " selected";
				}
				if($cliente=="-")
				{
				  echo " selected";
				}
			  ?>
			    >Todos</option>
                <?php
		  	$sql="select c.cli_codigo,c.cli_nombre "
				."from cliente c "
				."where c.cli_convenio='1' "
				."order by cli_codigo,cli_nombre ";
			$rs = &$conn->Execute($sql);
			while(!$rs->EOF)
			{
			  $valor=$rs->fields[0];
			  $texto=$rs->fields[1];
	         ?>
                <option value="<?=$valor?>" 
			<?php
				if($valor==$cliente)
				{  
				  echo " selected";				  
				}  
			?>
			> 
                <?=$texto?>
                </option>
                <?php
			  $rs->MoveNext();
			}					  
	  ?>
              </select> </td>
          </tr>
          <tr> 
            <td>Fecha Inicio: </td>
            <td> <input type="text" name="fecha_ini" value="<?php if(!isset($fecha_ini)) echo"$vf_act"; else echo "$fecha_ini"; ?>" > 
              <a href="javascript:show_calendar('form1.fecha_ini');" 
					  onmouseover="window.status='Date Picker';return true;" 
					   onmouseout="window.status='';return true;"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0> 
              </a> </td>
          </tr>
          <tr> 
            <td>Fecha Final: </td>
            <td> <input type="text" name="fecha_fin" value="<?php if(!isset($fecha_fin)) echo"$vf_act"; else echo "$fecha_fin"; ?>" > 
              <a href="javascript:show_calendar('form1.fecha_fin');" 
					  onmouseover="window.status='Date Picker';return true;" 
					   onmouseout="window.status='';return true;"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0> 
              </a> </td>
          </tr>
        </table>
	    </td>
	    <td align="center"><p>
          <input type="submit" name="procesar" value="Procesar">
        </p>
		</td>
	  </tr>	  
	</table>

	<br><br>	

<?php	        
      if(isset($procesar))
	  {  
		//select de info de guías
		/*
		include_once("class/c_user.php");
		$cuser=new c_user();
		$estacion_usuario=$cuser->recuperar_estacion($conn,$username);
		$ciudad=$cuser->recuperar_ciudad($conn,$username);
		*/
		
		$sql="select c.cli_codigo,c.cli_nombre "
		    ."from cliente c "
			."where c.cli_convenio='1' ";
		if($cliente!="-")
		  $sql.="and cli_codigo='$cliente' ";	
		$sql.="order by c.cli_codigo,c.cli_nombre ";  
		$rs=&$conn->Execute($sql);				
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
			  <font color="#FFFFFF">Cliente</font>
			</SPAN>
		  </td>
		</tr>
	  </table>
	  <table WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
	    <TR>
		  <TD>
		    <TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td width="8%" nowrap class='table_hd'>Item</td>
				  <td width="8%" nowrap class='table_hd'>Cliente</td>
                  <td width="17%" nowrap class='table_hd'>Nro Documentos (Gu&iacute;as o con convenio) </td>                  
                  <td width="32%" nowrap class='table_hd'>Ver Reporte </td>
                </tr>
                <?php
		$tipo_guia="C"; //credito
		$item=1;		
		while(!$rs->EOF)
		{
		    $vcli_codigo=$rs->fields[0];
		    $vcli_descripcion=$rs->fields[1];
			
			
			//calcular nro de facturas
			$sql1="select count(*)  "
				."from documento "
				."where cli_codigo='$vcli_codigo' "
				."and doc_formapago='$tipo_guia' "
				."and to_char(doc_fecharec,'YYYY-MM-DD')>='$fecha_ini' "
				."and to_char(doc_fecharec,'YYYY-MM-DD')<='$fecha_fin' ";
			$rs1=&$conn->Execute($sql1);
			$nrofacturas=$rs1->fields[0];	

?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD valign=top nowrap> 
                    <?=$item?>
                  </TD>
                  <TD valign=top nowrap> 
                    <?=$vcli_descripcion?>
                  </TD>
				  <TD valign=top nowrap> 
                    <?=$nrofacturas?>
                  </TD>
                  <TD valign=top nowrap> <a href="rdv_credito1.php?cli_id=<?=$vcli_codigo?>&fecha_ini=<?=$fecha_ini?>&fecha_fin=<?=$fecha_fin?>" target="_blank">Click 
                    para Ver</a> </TD>
                </TR>
                <?php
			$item++;
			$rs->MoveNext();
		}
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
