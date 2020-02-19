<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
		if($enventana==0)
		{
		  buildmenu($username);
		  buildsubmenu($id_aplicacion,$id_subaplicacion);
		}  
		//todo  el html como se quiera
		$vuehis_id=$id;
		$vfecha=date("Y-m-d");
		include('class/c_user.php');
		$cusuario=new c_user();
		$vnombre_usuario=$cusuario->recuperar_nombre($conn,$username);
		$vorigen=$cusuario->recuperar_ciudad($conn,$username);
		//recuperar destino
		$sql_des="select v.vue_destino,vh.avi_id,vh.vue_codigo from vuelo v,vuelo_historial vh "
				."where v.vue_codigo=vh.vue_codigo "
				."and vh.vuehis_id=$vuehis_id ";
		$rs_des=&$conn->Execute($sql_des);
		$vdestino=$rs_des->fields[0];
		$vavion=$rs_des->fields[1];
		$vnrovuelo=$rs_des->fields[2];		
	?>

<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	
	<br>
	<form action="embarque2.php" method="post" name="form1">
	<?php						
		$campo=array(
						array("etiqueta"=>" Fecha","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$vfecha),
						array("etiqueta"=>" Elaborado Por","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vnombre_usuario),
						array("etiqueta"=>" Origen","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$vorigen),
						array("etiqueta"=>" Destino","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vdestino),
						array("etiqueta"=>" Avión","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$vavion),
						array("etiqueta"=>" Vuelo Nro.","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vnrovuelo)
					);

		$manemb_nro=$vfecha."-".trim($vnrovuelo);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"vuehis_id","valor"=>$vuehis_id),
							array("nombre"=>"manemb_nro","valor"=>$manemb_nro),
							array("nombre"=>"vorigen","valor"=>$vorigen),
							array("nombre"=>"vdestino","valor"=>$vdestino),
							array("nombre"=>"principal","valor"=>"embarque.php")
							);
		//construye el html para los campos relacionados
		$titulo="Manifiesto de Embarque de Carga Nro ".$manemb_nro;
		build_show($conn,'false',$titulo,"images/360/taskwrite.gif","50%",'true'
		,$campo,$campo_hidden,$idp);			
	?>
	<hr width="100%" align="center" size="2">
	
<input name="Apply" type="submit" value="Guardar">
<input type="button" name="back" value="Regresar" onClick="<?php
if($enventana==1)
{
  echo "window.close();";
}
else
{
  echo "self.location='".$principal."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."'";
}
?>">
<br>			
			
	<TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR><TD>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
			<tr>
				<td nowrap><SPAN class="title" STYLE="cursor:default;">
					<img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
					Guías por Enviar</font></SPAN>
				</td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td nowrap class='table_hd'>&nbsp;</td>
                  <td nowrap class='table_hd'>Nro de Guía</td>
                  <td nowrap class='table_hd'>Nro de Piezas</td>
                  <td nowrap class='table_hd'>Peso (Kg)</td>
                  <td nowrap class='table_hd'>Destinatario</td>
                </TR>
                <?php
						$tpiezas=0;
						$tpeso=0;
						$sql="select distinct g.gui_id,g.gui_nro,g.gui_piezas,g.gui_peso,g.gui_destinatario "
							."from guia g,estacion e,ciudad c "
							."where g.manemb_id=0 "
							."and to_char(g.gui_fecha,'YYYY-MM-DD')='$vfecha' "
							."and g.ciu_codigo='$vdestino' "
							."and e.est_id=g.est_origen and e.ciu_codigo='$vorigen' ";
						$rs = &$conn->Execute($sql);
						$cont=0;
						while(!$rs->EOF)
						{
		  					$gui_id=$rs->fields[0];
							$gui_nro=$rs->fields[1];
							$gui_piezas=$rs->fields[2];
							$gui_peso=$rs->fields[3];
							$gui_destinatario=$rs->fields[4];
							
							$tpiezas+=$gui_piezas;
							$tpeso+=$gui_peso;
					?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD valign=top nowrap> <input type='checkbox' name='chc[<?=$cont?>]' value='<?=$gui_id?>' checked> 
                    &nbsp;</TD>
                  <TD valign=top nowrap> 
                    <?=$gui_nro?>
                    &nbsp;</TD>
                  <TD valign=top nowrap> 
                    <?=$gui_piezas?>
                    &nbsp;</TD>
                  <TD valign=top nowrap> 
                    <?=$gui_peso?>
                    &nbsp;</TD>
                  <TD valign=top nowrap> 
                    <?=$gui_destinatario?>
                    &nbsp;</TD>
                </TR>
                <?php
							$cont=$cont+1;
							$rs->MoveNext();
						}
					?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD colspan="2" valign=top nowrap> TOTALES</TD>
                  <TD valign=top nowrap> 
                    <?=$tpiezas?>
                    &nbsp;</TD>
                  <TD valign=top nowrap> 
                    <?=$tpeso?>
                    &nbsp;</TD>
                  <TD valign=top nowrap>&nbsp; </TD>
                </TR>
              </TABLE>
				</TD></TR>
			</TABLE>				
			<TR><TD>
				<input type='hidden' name='total' value='<?=$cont?>'>
				<input type="hidden" name="cextra" value="id_aplicacion|id_subaplicacion|principal|idp">	
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
