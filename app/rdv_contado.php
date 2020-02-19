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
	<form action="rdv_contado.php" method="post" name="form1">
	<?php
		$principal="rdv_contado.php";
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
            <td>Estaci&oacute;n: </td>
            <td> <select name="estacion">
                <option value="-" 
			  <?php
			    if(!isset($estacion))
				{
				  echo " selected";
				}
				if($estacion=="-")
				{
				  echo " selected";
				}
			  ?>
			    >Todas</option>
                <?php
		  	$sql="select est_codigo,est_nombre "
				."from estacion "
				."order by est_codigo,est_nombre";
			$rs = &$conn->Execute($sql);
			while(!$rs->EOF)
			{
			  $valor=$rs->fields[0];
			  $texto=$rs->fields[1];
	         ?>
                <option value="<?=$valor?>" 
			<?php
				if($valor==$estacion)
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
		
		$sql="select est_codigo,est_nombre "
		    ."from estacion ";
		if($estacion=="-")
		  $sql.="order by est_codigo,est_nombre";
		else
		  $sql.="where est_codigo='$estacion' ";	
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
			  <font color="#FFFFFF">Estaciones</font>
			</SPAN>
		  </td>
		</tr>
	  </table>
	  <table WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
	    <TR>
		  <TD>
		    <TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td width="8%" nowrap class='table_hd'>Estaci&oacute;n</td>
                  <td width="17%" nowrap class='table_hd'>Nro Documentos </td>                  
                  <td width="32%" nowrap class='table_hd'>Ver Reporte (Correspondencia 
                    y Carga)</td>
                </tr>
                <?php
		$tipo_guia="E"; //contado		
				
		while(!$rs->EOF)
		{
		    $vest_id=$rs->fields[0];
		    $vest_codigo=$rs->fields[0];
		    $vest_nombre=$rs->fields[1];
			$vtexto=$vest_codigo." : ".$vest_nombre;
			
			//calcular nro de facturas
			$sql1="select count(*)  "
				."from documento "
				."where doc_origen='$vest_id' "
				."and doc_formapago='$tipo_guia' "
				."and to_char(doc_fecharec,'YYYY-MM-DD')>='$fecha_ini' "
				."and to_char(doc_fecharec,'YYYY-MM-DD')<='$fecha_fin' ";
			//echo "<hr>$sql1<hr>";	
			$rs1=&$conn->Execute($sql1);
			$nrofacturas=$rs1->fields[0];	

?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD valign=top nowrap> 
                    <?=$vtexto?>
                  </TD>
                  <TD valign=top nowrap> 
                    <?=$nrofacturas?>
                  </TD>
                  <TD valign=top nowrap> <a href="rdv_contado1.php?est_id=<?=$vest_id?>&fecha_ini=<?=$fecha_ini?>&fecha_fin=<?=$fecha_fin?>" target="_blank">Click 
                    para Ver</a> </TD>
                </TR>
                <?php
			$rs->MoveNext();
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
