<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php
//$conn->debug=true;
		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$sUsername;
		if($enventana==0)
		{
		  buildmenu($username);
		  buildsubmenu($id_aplicacion,$id_subaplicacion);
		}  
		///todo  el html como se quiera
	?>

<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>
	
	<br>
	<form action="perfil_modu_upd1.php" method="post" name="form1">
	<?php				
		//mostrar el usuario seleccionado, solo los datos
		
		$sql="select perfil_nombre "
			."from perfil "
			."where perfil_id='$id'";
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{		  
		  $vnombre=$rs->fields[0];
		}
		$rs->Close();
		$campo=array(
						array("etiqueta"=>"* Código Perfil","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$id),
						array("etiqueta"=>"* Nombre","nombre"=>"clp2","tipo_campo"=>"text","sql"=>"","valor"=>$vnombre)
					);

		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"id","valor"=>$id),
							array("nombre"=>"principal","valor"=>"perfil_modu_upd.php")
							);
		//construye el html para los campos relacionados
		build_show($conn,'false',"Perfil - Módulos","images/360/taskwrite.gif","50%",'true'
		,$campo,$campo_hidden,$idp);			
	?>
	<hr width="100%" align="center" size="2">
	
<input name="Apply" type="submit" id="Apply"  value="Aplicar">
<input type="button" name="back" value="Cancelar" onClick="<?php
if($enventana==1)
{
  echo "window.close();";
}
else
{
  echo "self.location='perfil_modu.php?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."'";
}
?>">
<br>			
			
	<TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR><TD>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
			<tr>
				<td nowrap><SPAN class="title" STYLE="cursor:default;">
					<img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
					Modules by User <?=$id?>&nbsp;</font></SPAN>
				</td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
					<TR BGCOLOR="#CCCCCC">						
                      <td nowrap class='table_hd'>&nbsp;</td>						
                      
                    <td nowrap class='table_hd'>Módulo</td>
					</TR>
					<?php
						$sql="select id_aplicacion,nombre_aplicacion from aplicacion order by nombre_aplicacion";
						$rs = &$conn->Execute($sql);
						$cont=0;
						while(!$rs->EOF)
						{
		  					$core_id=$rs->fields[0];
							$core_des=$rs->fields[1];
					?>
					<TR valign=top bgcolor='#ffffff'>
						<TD valign=top nowrap> <input type='checkbox' name='chc[<?=$cont?>]' value='<?=$core_id?>' 
						<?php
						  $sql_si="select id_aplicacion from perfil_aplicacion "
						  		."where perfil_id='$id' and id_aplicacion=$core_id";
						  $rs1 = &$conn->Execute($sql_si);
						  if(!$rs1->EOF)
						    echo " checked";
						?>
						>&nbsp;</TD>
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
