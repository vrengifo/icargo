<?php 
  session_start();
  
  //echo ("sesiones: ".$sOficina.":".$sTerminal);
  
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  $vf_act=date("Y-m-d");
?>

<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	<form action="reporteDocumentoAudit.php" method="post" name="form1">
	<?php
		$principal="busca_guia.php";
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
            <td>Tipo de Documento: </td>
		    <td>			  
			  <select name="tipo_doc">
			    <option value="-" 
			  <?php
			    include_once("class/c_stock_tipo.php");
			    $oStoTip=new c_stock_tipo($conn);
			    if(!isset($tipo_doc))
				{
				  echo " selected";
				  $tipdoc_selected="-"; 
				}
				if($tipo_doc=="-")
				{
				  echo " selected";
				  $tipdoc_selected="-";
				}
			  ?>
			    >Todos</option>
              <?php
              $sql=$oStoTip->sqlSelect();
              $rs = &$conn->Execute($sql);
              while(!$rs->EOF)
              {
			    $valor=$rs->fields[0];
			    $texto=$rs->fields[1];
	         ?>
				<option value="<?=$valor?>" 
			<?php
				if($valor==$tipo_doc)
				{  
				  echo " selected";
				  $tipdoc_selected=$tipo_doc;
				}  
			?>
			> 
                        <?=$texto?>
                        </option>
                        <?php
			  $rs->MoveNext();
			}					  
	  ?>
                      </select>
			</td>
	      </tr>
		  
	      <tr>	    
            <td>Nro de Documento: </td>
		    <td>
			  <input type="text" name="nrodoc" value="<?=$nrodoc?>">			  
			</td>
	      </tr>
		  <tr>	    
            <td>CI / RUC Destinatario: </td>
		    <td>
			  <input type="text" name="ciruc" value="<?=$ciruc?>">			  
			</td>
	      </tr>
		  <tr>	    
            <td>Remitente: </td>
		    <td>
			  <input type="text" name="remitente" value="<?=$remitente?>">
			</td>
	      </tr>
	      <tr>
            <td>Fecha Desde: </td>
		     <td>
			  <input type="text" name="fecha" value="<?php if(!isset($fecha)) echo"$vf_act"; else echo "$fecha"; ?>" >
              <a href="javascript:show_calendar('form1.fecha');" 
					  onmouseover="window.status='Date Picker';return true;" 
					   onmouseout="window.status='';return true;"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0> 
              </a>
			 </td>
	      </tr>
		  <tr>
            <td>Fecha Hasta: </td>
		     <td>
			  <input type="text" name="fechaHasta" value="<?php if(!isset($fechaHasta)) echo"$vf_act"; else echo "$fechaHasta"; ?>" >
              <a href="javascript:show_calendar('form1.fechaHasta');" 
					  onmouseover="window.status='Date Picker';return true;" 
					   onmouseout="window.status='';return true;"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0> 
              </a>
			 </td>
	      </tr>	  	  	
	    </table>
	    </td>
	    <td align="center"><p>
          <input type="submit" name="procesar" value="Procesar">
        </p>
        <!--
        <p>
          <input type="button" name="cerrar" value="Cerrar" onClick="window.close();">
        </p>
        --></td>
	  </tr>
	  
	</table>
	

<?php	        
      if(isset($procesar))
	  {  
		include_once("class/c_oficina.php");
		$oOfi=new c_oficina($conn);
		
		$oOfi->info($sOficina);
	  	
	  	//$conn->debug=true;
	  	include_once("class/c_documento.php");
		$oDoc=new c_documento($conn,$sUsername,$sTerminal);
		$cadId=$oDoc->armarSqlId("g.","g.","g.","g.");
	  	$sql=<<<va
		select tg.stotip_nombre,g.sto_nro,to_char(g.doc_fecharec,'YYYY-MM-DD'),
		c.cli_nombre,g.doc_destnombre,g.doc_estado,$cadId as idG
		from stock_tipo tg, documento g, cliente c 
		where g.stotip_id=tg.stotip_id 
		and 
		(
		  (g.ofi_id='$sOficina') 
		  or
		  (g.doc_destino='$oOfi->est_codigo')
		)
		and (c.cli_codigo=g.cli_codigo)
va;
		if($tipo_doc!="-")
		{	  
		  $sql.=" and g.stotip_id='$tipo_doc' ";
		}
		
		if(strlen($nrodoc)>0)
		{	  
		  $sql.=" and g.sto_nro like '$nrodoc%' ";
		}
		
		if(strlen($ciruc)>0)
		{	  
		  $sql.=" and g.doc_destciruc like '$ciruc%' ";
		}		
		
		if(strlen($remitente)>0)
		{	  
		  $sql.=" and c.cli_nombre like '$remitente%' ";
		}				
		
		if(strlen($fecha)>0)
		{	  
		  $sql.=" and to_char(g.doc_fecharec,'YYYY-MM-DD') >= '$fecha' ";
		}
		
		if(strlen($fechaHasta)>0)
		{	  
		  $sql.=" and to_char(g.doc_fecharec,'YYYY-MM-DD') <= '$fechaHasta' ";
		}
				
		$sql.=" order by g.doc_fecharec ";		
		$rs = &$conn->Execute($sql);
		//echo "<br>consulta 1: <br> $sql <br>";		
						
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
			  <font color="#FFFFFF">Documentos Encontrados</font>
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
				  <td nowrap class='table_hd'>Estado</td>
				  <td nowrap class='table_hd'>Ver</td>
				  <td nowrap class='table_hd'>Historial</td>
				  
                </tr>			
                <?php
		while(!$rs->EOF)
		{
//tg.stotip_nombre,g.sto_nro,to_char(g.doc_fecharec,'YYYY-MM-DD'),c.cli_nombre,g.doc_destnombre,nvl(g.doc_estado,'0'),$cadId as idG
		    $vtipdoc=$rs->fields[0];
		    $vnrodoc=$rs->fields[1];
		    $vfecha=$rs->fields[2];
		    $vremitente=$rs->fields[3];
		    $vdestinatario=$rs->fields[4];
		    $vestado=$rs->fields[5];			
			$vdoc_id=$rs->fields[6];
			
			$vCadEstado=$oDoc->estado($vestado);
			
?>
                <TR valign=top bgcolor='#ffffff'> 
                  <TD valign=top nowrap> 
                    <?=$vtipdoc?>
                  </TD>
                  <TD valign=top nowrap> 
                    <?=$vnrodoc?>
                  </TD>
                  <TD valign=top nowrap align=right> 
                    <?=$vfecha?>
                  </TD>
                  <TD valign=top nowrap align=right> 
                    <?=$vremitente?>
                  </TD>
                  <TD valign=top nowrap> 
                    <?=$vdestinatario?>
                  </TD>
				  <TD valign=top nowrap> 
                    <?PHP
                      
                      if(strlen($vestado)==0)//enviada
                      {
                        $estaCompleta=$oDoc->estaCompleto($vdoc_id);
                        if($estaCompleta)
                        {
                          $cadUrl=<<<va
&nbsp <a href="documento_entregar.php?id=$vdoc_id" target="_blank">Click para entregar</a>
va;
						  echo($cadUrl); 
                        }
                        else 
                          echo("Documento está incompleto, faltan paquetes");	
                      }
                      if($vestado=="E")
                      {
                      	echo("Documento Entregado");
                      }
                      if($vestado=="A")
                      {
                      	echo("Documento Anulado");
                      }  
                    ?>
                  </TD>
				  <TD valign=top nowrap> 
                    <a href="documento_viewWindow.php?id=<?=$vdoc_id?>" target="_blank">Click para Ver</a>
                  </TD>
                  <TD valign=top nowrap> 
                    <a href="documentoHistorial.php?id=<?=$vdoc_id?>" target="_blank">Ver Historial	</a>
                  </TD>
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
