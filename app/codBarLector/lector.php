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
<form name="forma" method="post" action="destino.php">
  <table width="75%" border="1" align="center">
    <tr>
      <td><div align="center">
          <input type="text" name="codigo"> 
		  <input type="button" name="Add" value="Añadir" onclick="addLista();">
        </div></td>
    </tr>
    <tr>
      <td><div align="center">
          <select name="codigos" size="10" multiple>
          </select>
          <br>
          <input type="button" name="Submit2" value="Eliminar" onclick="eliminaLista();">
          <br>
          <input type="hidden" name="cadena">
        </div></td>
    </tr>
    <tr>
      <td> <div align="right">
          <input type="submit" name="accion" value="Enviar" onclick="return enviaFormulario();">
        </div></td>
    </tr>
  </table>
</form>
</body>
</html>
