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
		
  $principal="im_usuxprov.php";
  $param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
?>
	
	<br>
	<form action="im_usuxprov1.php" method="post" name="form1">
	

	<hr width="100%" align="center" size="2">

	<input name="Apply" type="submit" id="Apply"  value="Apply">
	<input type="button" name="back" value="Close" onClick="window.close();">
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
	
<SCRIPT LANGUAGE="JavaScript">
function valida() {
}
</script>	
<br>			
			
	<TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR><TD>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
			<tr>
				<td nowrap><SPAN class="title" STYLE="cursor:default;">
					<img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
					Usuarios por Proveedor &nbsp;</font></SPAN>
				</td>
			</TR>
			</TABLE>
			<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
			<TR><TD>
				<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
					<TR BGCOLOR="#CCCCCC">						
                      <td nowrap class='table_hd'>&nbsp;</td>						
                      
                    <td nowrap class='table_hd'>Usuario</td>
					</TR>
					<?php
					  include_once("class/c_im_usuxprov.php");
					  $obj=new c_im_usuxprov($session_username,$conn);	
					  //recuperar assemblies q cumplan con ast_id y appl_id
						$sql="select username u1,username u2,nombre "
							."from usuario "
							."order by username,nombre ";
						$rs = &$conn->Execute($sql);
						$cont=0;
						while(!$rs->EOF)
						{
		  					$core_id=$rs->fields[0];
							$core_des=$rs->fields[2]." (".$rs->fields[1]." )";
					?>
					<TR valign=top bgcolor='#ffffff'>
						<TD valign=top nowrap> <input type='checkbox' name='chc[<?=$cont?>]' value='<?=$core_id?>' 
						<?php
						  $obj->pro_id=$id;
						  $obj->username=$core_id;
						  if($obj->validar($core_id))
						    echo " checked";
						?>
						>&nbsp;
						<input type="hidden" name="cad[<?=$cont?>]" value="<?=$obj->id2cad($id,$core_id)?>" >
 						</TD>
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
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>