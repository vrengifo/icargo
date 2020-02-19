<?php
  session_start();  
  include_once('includes/main.php');
  
  include_once('class/c_terminal.php');
  $oTer=new c_terminal($conn);
  include_once('class/c_oficina.php');
  $oOfi=new c_oficina($conn);
  
  //echo "<hr>idTer $idTer --- ipTer $ipTer <br>";

  $res=$oTer->buscaxTeridTerip($idTer,$ipTer);
  if($res!="0")
  {
    
  	$sTerminal=$res;
  	if(!session_is_registered("sTerminal"))
    {
      session_register("sTerminal");
    }
    
  	$oTer->info($res);
  	$sOficina=$oTer->ofi_id;
    if(!session_is_registered("sOficina"))
    {
      session_register("sOficina");
    }
    
    
    $oOfi->info($sOficina);
  	$sEstacion=$oOfi->est_codigo;
    if(!session_is_registered("sEstacion"))
    {
      session_register("sEstacion");
    }
    
    //registro de variables de sesión de terminal
    session_register("idTer");
    session_register("ipTer");
    
  }
  /*
  echo "sTerminal: $sTerminal ";
  echo "sOficina: $sOficina ";
  echo "sEstacion: $sEstacion ";
  */
?>
<html>
<head><title>.:: Sistema de Carga - ICARGO (<?=$sEstacion?>) ::.</title>
<STYLE>
td { font-family: Tahoma, Verdana, Arial, sans-serif; }
body { font-family: Tahoma, Verdana, Arial, sans-serif; }
</STYLE>
</head>
<body link=#0000ff vlink=#0000ff bgcolor=#ffffff background="images/360/bgBasic.gif" topmargin=0 marginheight=0>
<table width=100% border=0 cellspacing=1 cellpadding=0 bgcolor=#224466>
 <tr height=1 bgcolor=#94ACC8>
  <td BGCOLOR="#FFFFFF" WIDTH="120" height="66"><a href="#" onClick="helpWindow();"><img src="images/logo.gif" border=0 alt="Ayuda"></a></td>
  <td align=center valign=middle nowrap>SISTEMA DE CARGA</td>
 </tr>
</table>
<SCRIPT LANGUAGE="JavaScript">function helpWindow() { window.open('documentacion/manualUsuario.pdf','Help','width=680,height=500,resizable=1,scrollbars=1'); }</SCRIPT>
<table width=100% border=0 cellspacing=0 cellpadding=0>
 <tr>
  <td width="99%" background="images/360/mb_top.gif" height=7><img src="images/360/spacer.gif" height=7></td>
  <td width=7 bgcolor="#224466"><img src="images/360/mb_topright.gif"></td>
  <td width="1%" bgcolor="#224466"><img src="images/360/spacer.gif"></td>
 </tr>
 <tr>
  <td bgcolor="#dddddd" align="center" width="70%">
<p><font size=+1>
<?php 
	extract($_REQUEST);
	if (!empty($reason)){
		echo "Error";
	}
?>
</font></p>
<?php
  if($res)
  {
?>
<form method="post" action="login.php">
  <table BORDER=0>
    <tr> 
      <td nowrap>Usuario</td>
      <td><input type=text name=username></td>
    </tr>
    <tr>
      <td nowrap>Contrase&ntilde;a</td>
      <td><input type=password name=password></td>
    </tr>
    <tr>
      <td></td>
      <td>
        <input type=submit name=Submit value="Entrar">
      </td>
    </tr>
  </table>
</form>
<?php
  }
  else 
  {
?>
  <table BORDER=0>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center">Problemas con el terminal, comuníquese con el Departamento de Sistemas</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
<?php
  }
?>

      <p ALIGN=left></p>
 </td>
  <td background="images/360/mb_right2.gif" width=7 height=14 valign=top bgcolor="#224466"><img src="images/360/mb_right1.gif"></td>
  <td width="1%" valign=top><table width=100% border=0 cellspacing=0 cellpadding=0 bgcolor="#224466"><tr><td height=14><img src="images/360/spacer.gif" height=14></td></tr></table></td>
 </tr>
 <tr>
  <td background="images/360/mb_bottom.gif" height=7><img src="images/360/spacer.gif" height=7></td>
  <td><img src="images/360/mb_bottomright.gif"></td>
  <td><img src="images/360/spacer.gif"></td>
 </tr>
</table>
<br>
<p STYLE="font-size:10px;">
Version: 1.0 
</P>
<p><font size="-1">Departamento de Tecnolog&iacute;as de Informaci&oacute;n</font></p>
<p><a href="#" onClick="helpWindow();">Ayuda</a></p>
<!-- 
<p><font size=-1>Copyright (C) 1997-2003 ICARO Corporation All rights reserved.</font></p>
-->
</body>
</html>
