<?php
  include_once("class/c_socket.php");
  class c_impresion
  {    
    
	function writeFile2Socket($pathFile,&$sockCliente,$debug=0,$forma="t")
    {
      if($forma=="t")
        $mya="r";
      else 
        $mya="rb";  
      $handle = fopen($pathFile, $mya);
      $cadena = "";
      if(feof($handle))
      {
        if($debug)
          echo "error al abrir el archivo";
        $res=0;
      }
      else
      {  
      	while (!feof($handle)) 
        {
  	      $cadena="";
          $cadena = fread($handle, 4096);
  	      if($debug)
  	        echo"$cadena<br>";
  	      $sockCliente->escribir($cadena);
  	    }
  	    fclose($handle);
  	    $res=1;
      }
      return($res);
    }
    
    /**
     * Función usada para imprimir al socket del nuevo pocle
     *
     * @param string $ip
     * @param string $port
     * @param string $file o data
     * @param string $tipo "DOC documento","CB Codbarra"
     * @param bool $debug
     * @param string $forma texto o binario
     * @param int $max tiempo máximo de espera x el socket en segundos
     * @param int $intentomax número de intentos máximos para leer archivo
     * @return bool
     */
    function print2socket($ip,$port,$file,$tipo,$debug=0,$forma="t",$max=5,$intentomax=10)
    {
      if($debug)
      {
      	echo "<hr>print2socket(ip:$ip,port:$port,file:$file,forma:$forma)<hr>";
      }
      $sockCliente=new c_socket($ip,$port);
      $cont=0;
      while((!$varcon=$sockCliente->conectar())&&($cont<=$max))
      {
        $cont++;
      	sleep(1);
      }
      if($varcon)
      {
        
      	switch($tipo)
        {
      	  case "DOC": //documento
        	$sockCliente->escribir("LINICIO");
      	      $intento=0;
      	      do 
      	        $intento++;
      	      while((!$imprimio=$this->writeFile2Socket($file,$sockCliente,$debug,$forma))&&($intento<$intentomax));  
      	      if(!$imprimio)
      	      {
      	        if($debug)
      	      	echo "error al abrir el archivo";
          		$res=0;
      	      }
      	      else 
      	      {  
          	    $sockCliente->escribir("FINARCHIVO");
          	    $sockCliente->desconectar();
          	    $res=1;
      	      }
        	break;
      	  case "CB": //cod bar
        	$sockCliente->escribir("BINICIO");
        	//sleep(1);
        	$sockCliente->escribir($file);//formato: mcodigo@mcuantos@mempresa@morigenDestino
        	//sleep(1);
        	$sockCliente->escribir("FINARCHIVO");
      	    //cerrar socket
      	    $sockCliente->desconectar();
            break;
      	}
      }
      else
      {
        $res=0;
      	if($debug)
      	{
      	  echo "No se pudo conectar";
      	}
      }
      return($res);
    }
    
  }
?>