<HTML>
	<HEAD>
		<title>.::ICARGO::.</title>
<SCRIPT LANGUAGE="JavaScript">
  <!--
  var ok=1;
  
  function abrirVentana()
  {
	var cadena;
	var ancho, alto;
	
	ancho = screen.availWidth - 10;
	alto = screen.availHeight - 50;
	cadena = 'left=0,top=0,border=0,status=yes,scrollbars=yes,' + 'width=' + ancho + ',height=' + alto;
	//cadena = 'status=yes,scrollbars=yes,' + 'width=' + ancho + ',height=' + alto;
	window.open("index1.php", "ICARGO", cadena);
  }
  
  function tempo() 
  {
	if(ok)
	  setTimeout("checkEstado();",200);
  }
  
  function checkEstado() 
  {
	var estado=document.client.obtenerEstado();
	if(estado != "") 
	{
	  if(estado == "A") 
	  {				   
		revisaTerminal(estado);
	  }
	}
	tempo();
  }
  
  //Ajax
  function objetus()
  {
    try
    {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } 
    catch (e) 
    {
      try 
      {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } 
      catch (E) 
      {
        xmlhttp = false;
      }
    }
    
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') 
    {
      xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp
  }
  
  /*function actualizarBase(estado)
  {
	//creamos el objeto
    _objetus=objetus()
    
    var IP=document.client.ObtieneIP();
	var idente=document.client.obtenerId();
	var IpTermMen=document.client.ObtieneIPTerminalMensaje();
	var IpTermBP=document.client.ObtieneIPTerminalBP();
	var PtoTermMen=document.client.ObtienePuertoTerminalMensaje();
	var PtoTermBP=document.client.ObtienePuertoTerminalBP();
	var Formato=document.client.ObtieneFormato();
    
    _target=document.getElementById('datos')
    _target.style.display=''
    //cargamos una varible con las variables que vamos a enviar
     _values_send="idente="+idente;
     _values_send=_values_send+"&IP="+IP;
     _values_send=_values_send+"&IpTermMen="+IpTermMen;
     _values_send=_values_send+"&IpTermBP="+IpTermBP;
     _values_send=_values_send+"&PtoTermMen="+PtoTermMen;
     _values_send=_values_send+"&PtoTermBP="+PtoTermBP;
     _values_send=_values_send+"&Formato="+Formato;

    _URL_="actualizacionprimera.php?"
    _objetus.open("GET",_URL_+"&"+_values_send,true);
    //una vez enviado los valores inmediatamente llamamos a la propiedad 
    //onreadystatechange
    _objetus.onreadystatechange=function() 
    {
      //dentro de la funcion comprobamos el estado del proceso
      //si es 4 (terminado) pedimos lo que nos han mandado
      
      if (_objetus.readyState==4)
      {
      	//usamos la propiedad responseText para recibir en una cadena
      	//lo que nos mandaron
      	_envio=_objetus.responseText;
      	//window.alert(_envio);
      	_target.innerHTML = _envio;
      }
    }
       
    _objetus.send(null);
    //return _objetus.responseText;
  }*/
	
  function revisaTerminal(estado)
  {
	var idTer=document.client.obtenerIdentificador();
	var ipTer=document.client.obtenerIP();
	
	//creamos el objeto
    _objetus=objetus()
   
    _target=document.getElementById('datosrevisa')
    _target.style.display=''
    //cargamos una varible con las variables que vamos a enviar
     _values_send="idTer="+idTer;
     _values_send=_values_send+"&ipTer="+ipTer;

    _URL_="revisaTerminal.php?"
    _objetus.open("GET",_URL_+"&"+_values_send,true);
    //una vez enviado los valores inmediatamente llamamos a la propiedad 
    //onreadystatechange
    _objetus.onreadystatechange=function() 
    {
      //dentro de la funcion comprobamos el estado del proceso
      //si es 4 (terminado) pedimos lo que nos han mandado
      if (_objetus.readyState==4)
      {
      	//usamos la propiedad responseText para recibir en una cadena
      	//lo que nos mandaron
      	_envio=_objetus.responseText;
      	//window.alert(_envio);
           
        if (_envio!="0")
        {
          ok=0;
          abrirVentana();
          
          _target.innerHTML = "Configuracion Correcta";
        }
        else
        {
          _target.innerHTML = "Revisar Configuracion";
        }
      }
    }
    _objetus.send(null);
    return
  }
	
		//-->
		</SCRIPT>		
	</HEAD>
	<body onload="tempo();" bgcolor="#E8ECF8" marginwidth="0" marginheight="0" topmargin="0" leftmargin="0">
	  <table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
	    <tr>
		  <td align="center" valign="middle" >
		    <!--<img src="images/fondo.gif" align="absmiddle"> -->			
			<OBJECT ID="client" 
			  CLASSID="CLSID:8C400D8D-67F1-418F-8032-B6E9650B1275" 
			  CODEBASE="icargoCli.CAB#version=1,0,0,1">
			</OBJECT>
		  </td>
		</tr>
		<tr>
		  <td align="center" valign="middle" id="datosrevisa"></td>
		</tr>
	  </table>
	</body>
</HTML>
