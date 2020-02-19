<?php
class c_socket
{
 var $domain;//dominio para crear socket
 var $type;//tipo de socket
 var $protocol;//protocolo --- x defecto 0
 
 var $ip;//ip del equipo al que se conecta
 var $puerto;//puerto al que se conecta
 var $socket;//variable que almacena el socket
 
 var $buffer;//tamaño de la longitud retornada para leer
 
 function c_socket($ip,$puerto)
 {
   $this->domain=AF_INET;
   //$this->type=SOCK_STREAM;//socket tcp
   $this->type=SOCK_DGRAM;//socket udp
   $this->protocol=0;
   $this->ip=$ip;
   $this->puerto=$puerto;
   $this->socket=socket_create($this->domain,$this->type,$this->protocol);
   
   $this->buffer=2048;
 }
 
 function conectar()
 {
   $res=@socket_connect($this->socket,$this->ip,$this->puerto);
   return ($res);
 }
 
 function escribir($comando)
 {
   $res=socket_write($this->socket, $comando, strlen($comando));
   return($res);
 }
 
 function leer()
 {
   $out=socket_read($this->socket,$this->buffer);
   return ($out);
 }
 
 function desconectar()
 {
   socket_close($this->socket);
 }
}
?>
