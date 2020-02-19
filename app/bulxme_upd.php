<?php
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  
  include_once("class/c_manembxbulto.php");
  $oMEXB=new c_manembxbulto($conn,$sUsername);
  $oMEXB->cad2id($id);
  $idBulto=$oMEXB->bul_ref;
  $idME=$oMEXB->manemb_id;
?>
<html>
<head>
<title>Valida Código</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<SCRIPT language=JavaScript>
function ponerFoco() {
	forma.codigo.focus();
}

function validaCodigo12() {
	var codigo;
	var i;
	var retorno;
	retorno = true;
	codigo = forma.codigo.value;
	if(codigo.length == 12)
		if(codigo.substring(0,1) == "E")
			if(codigo.substring(1,8) == "0000000") {
				alert('Codigo No Válido');
				retorno = false;
			}
			else
				if(codigo.substring(8,10) == "00" || codigo.substring(10,12) == "00") {
					alert('Codigo No Válido');
					retorno = false;
				}
				else
					if(codigo.substring(8,10)*1 > codigo.substring(10,12)*1) {
						alert('Codigo No Válido');
						retorno = false;
					}
					else
						for(i=1;i<12;i++)
							if(codigo.substring(i,i+1) != "0" && codigo.substring(i,i+1) != "1" && codigo.substring(i,i+1) != "2" && codigo.substring(i,i+1) != "3" && codigo.substring(i,i+1) != "4" && codigo.substring(i,i+1) != "5" && codigo.substring(i,i+1) != "6" && codigo.substring(i,i+1) != "7" && codigo.substring(i,i+1) != "8" && codigo.substring(i,i+1) != "9") {
								alert('Codigo No Válido');
								retorno = false;
							}
							else {}
		else {
			alert('Codigo No Válido');
			retorno = false;
		}
	else {
		alert('Codigo No Válido');
		retorno = false;
	}
	return retorno;
}

function validaCodigo() {
	var codigo;
	var i;
	var retorno;
	retorno = true;
	codigo = forma.codigo.value;
	if(codigo.length == 16)
	{
	  retorno= true;
	}
	else
	{
	  alert('Codigo No Válido');
	  retorno=false;
	}
	return retorno;
}

function checkLista(cadena) {
	var i;
	var retorno = true;
	var tamano = forma.codigos.options.length;
	for(i=0;i<tamano;i++)
		if(forma.codigos.options[i].value == cadena) {
			alert('Código ya registrado');
			retorno = false;
		}
	return retorno;
}

function eliminaLista() {
	var opt;
	opt = forma.codigos.options.selectedIndex;
	forma.codigos.options[opt] = null;
	forma.codigo.value = "";
	forma.codigo.focus();	
}

function addLista() 
{
	if(validaCodigo()) 
	{
	  if(checkLista(forma.codigo.value)) 
	  {
		forma.codigos.options[forma.codigos.options.length] = new Option(forma.codigo.value,forma.codigo.value);
		forma.codigo.value = "";
		window.event.keyCode = 505;
		event.keyCode=505;
		return(false);
	  }
	  else 
	  {
	    forma.codigo.value = "";
		window.event.keyCode = 505;
		event.keyCode=505;
		return(false);
	  }
	}
	else
	{
	  forma.codigo.value = "";
	  window.event.keyCode = 505;
	  event.keyCode=505;
	  return(false);
	}
}

function enviaFormulario() {
	var i;
	var n = forma.codigos.options.length;
	if(n == 0) 
	{
		alert('Error no existen datos...!!!');
		forma.codigo.focus();
		return false;
	}
	else 
	{
		forma.cadena.value = "";
		for(i=0;i<n;i++) 
		{
			forma.cadena.value = forma.cadena.value + forma.codigos.options[i].value + ",";
		}
		//forma.submit();
		return true;
	}
}

/*
function disableEnterKey()
{
     var key;

     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = event.which;     //firefox

	alert(key);

     if(key == 13)
          return false;
     else
          return true;
}
*/

document.onkeydown = function() {
	var key;
	
	if(window.event)
      key = window.event.keyCode;     //IE
     else
       key = event.which;     //firefox
	//alert(key);
	if(key && key!= 9) {
		if(key == 13)
			if(validaCodigo()) {
				if(checkLista(forma.codigo.value)) {
					forma.codigos.options[forma.codigos.options.length] = new Option(forma.codigo.value,forma.codigo.value);
					forma.codigo.value = "";
					window.event.keyCode = 505;
					event.keyCode=505;
					return(false);
				}
				else {
					forma.codigo.value = "";
					window.event.keyCode = 505;
					event.keyCode=505;
					return(false);
				}
			}
			else {
				forma.codigo.value = "";
				window.event.keyCode = 505;
				event.keyCode=505;
				return(false);
			}
	}
	else {
		window.event.keyCode = 505;
		event.keyCode=505;
		return(false);
	}
}


</SCRIPT>
<body onload="ponerFoco();">
<form name="forma" method="post" action="bulxme_upd1.php">
  <input type="hidden" name="principal" value="<?=$principal?>">
  <input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
  <input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
  <input type="hidden" name="idp" value="<?=$idp?>">
  <input type="hidden" name="idBulto" value="<?=$idBulto?>">
  <table width="75%" border="1" align="center">
    <tr>
	  <td align="center">Creación de Bultos y carga de paquetes a bulto</td>
	</tr>
	<?php
	  include_once("class/c_manifiesto_embarque.php");
	  $oME=new c_manifiesto_embarque($conn,$sUsername);
	  $oME->info($idp);
	?>
	<tr>
	  <td align="center">
	    <table>
		  <tr>
		    <td colspan="2">Manifiesto <?=$oME->manemb_nro?></td>
		  </tr>
		  <tr>
		    <td>Fecha:</td>
			<td><?=$oME->manemb_fecha?></td>
		  </tr>
		  <tr>
		    <td>Bulto:</td>
			<td><?=$idBulto?></td>
		  </tr>
		</table>
	  </td>
	</tr>
	<tr>
      <td><div align="center">
          <input type="text" name="codigo"> 
		  <input type="button" name="Add" value="Añadir" onclick="addLista();">
        </div></td>
    </tr>
    <tr>
      <td><div align="center">
          <select name="codigos" size="10" multiple>
          <?php
    include_once("class/c_detalle_bulto.php");
    $oDB=new c_detalle_bulto($conn,$sUsername);
    $oDB->info($idBulto);
    $sql=<<<va
    select detdoc_ref from detalle_bulto
    where bul_ref='$idBulto'
    order by detdoc_ref 
va;
	$rs=&$conn->Execute($sql);
	while(!$rs->EOF)
	{
	  $detdoc=$rs->fields[0];
	  ?>
	  <option value="<?=$detdoc?>"><?=$detdoc?></option>
	  <?
	  
	  $rs->MoveNext();	
	}
          ?>
          </select>
          <br>
          <input type="button" name="Submit2" value="Eliminar" onclick="eliminaLista();">
          <br>
          <input type="hidden" name="cadena">
        </div></td>
    </tr>
    <tr>
      <td> <div align="center">
          <input type="submit" name="accion" value="Procesar" onclick="return enviaFormulario();">
		  <?php
		    $dest="bulxmanemb.php?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&idp=".$idp;
		  ?>
		  <input type="button" name="regresar" value="Regresar" onClick="self.location='<?=$dest?>';">
        </div></td>
    </tr>
  </table>
</form>
</body>
</html>
