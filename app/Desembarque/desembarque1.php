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
		//recuperar el manemb_id con el vuehis_id
		$sql_me="select manemb_id from manifiesto_embarque where vuehis_id=$vuehis_id";
		$rs_me=&$conn->Execute($sql_me);
		$id=$rs_me->fields[0];
		$manemb_id=$id;
		
		$sql="select m.manemb_nro,to_char(m.manemb_fecha,'YYYY-MM-DD'),u.usu_nombre,m.manemb_origen,m.manemb_destino,vh.avi_id,vh.vue_codigo "
			."from manifiesto_embarque m,usuario u,vuelo_historial vh "
			."where "
			."m.manemb_id=$manemb_id and u.usu_codigo=m.manemb_por and vh.vuehis_id=m.vuehis_id ";
		$rs=&$conn->Execute($sql);
		$manemb_nro=$rs->fields[0];
		$manemb_fecha=$rs->fields[1];
		$usu_nombre=$rs->fields[2];
		$origen=$rs->fields[3];
		$destino=$rs->fields[4];
		$avi_id=$rs->fields[5];
		$vue_codigo=$rs->fields[6];			
	?>

<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	
	<br>
	<form action="desembarque2.php" method="post" name="form1">
	<?php						
		$campo=array(
						array("etiqueta"=>" Fecha","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$manemb_fecha),
						array("etiqueta"=>" Elaborado Por","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$usu_nombre),
						array("etiqueta"=>" Origen","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$origen),
						array("etiqueta"=>" Destino","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$destino),
						array("etiqueta"=>" Avión","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$avi_id),
						array("etiqueta"=>" Vuelo Nro.","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vue_codigo)
					);
		
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"vuehis_id","valor"=>$vuehis_id),
							array("nombre"=>"manemb_id","valor"=>$manemb_id),
							array("nombre"=>"vorigen","valor"=>$vorigen),
							array("nombre"=>"vdestino","valor"=>$vdestino),
							array("nombre"=>"principal","valor"=>$principal)
							);
		//construye el html para los campos relacionados
		$titulo="Manifiesto de Embarque de Carga Nro ".$manemb_nro;
		build_show($conn,'false',$titulo,"images/360/taskwrite.gif","50%",'true'
		,$campo,$campo_hidden,$idp);			
	?>
	<hr width="100%" align="center" size="2">
	

<br>			
			
	<TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR><TD>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
			<tr>
				<td nowrap><SPAN class="title" STYLE="cursor:default;">
					<img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
					Listado de Guías</font></SPAN>
				</td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC">                   
                  <td nowrap class='table_hd'>Nro de Guía</td>
                  <td nowrap class='table_hd'>Nro de Piezas</td>
                  <td nowrap class='table_hd'>Peso (Kg)</td>
                  <td nowrap class='table_hd'>Destinatario</td>
                </TR>
                <?php
						$tpiezas=0;
						$tpeso=0;
						$sql="select g.gui_nro,g.gui_piezas,g.gui_peso,g.gui_destinatario "
							."from manemb_detalle med,guia g "
							."where med.manemb_id=$manemb_id "
							."and g.gui_id=med.gui_id "
							."order by med.manembdet_id ";
						$rs = &$conn->Execute($sql);
						$cont=0;
						while(!$rs->EOF)
						{
							$gui_nro=$rs->fields[0];
							$gui_piezas=$rs->fields[1];
							$gui_peso=$rs->fields[2];
							$gui_destinatario=$rs->fields[3];
							
							$tpiezas+=$gui_piezas;
							$tpeso+=$gui_peso;
					?>
                <TR valign=top bgcolor='#ffffff'>                   
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
                  <TD valign=top nowrap> TOTALES</TD>
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
		<input name="imprimir" type="button" value="Imprimir" onClick="fOpenWindow('vembarque_print.php?id=<?=$id?>','Imprimir_Embarque','600','600')">
  <input type="submit" name="siguiente" value="Siguiente (Confirmar Manifiesto de Desembarque)">
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
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
