<?php
	extract($_REQUEST);
	$cadena = substr($cadena,0,strlen($cadena) - 1);
	$arregloCadena = split(",",$cadena);
	for($i=0;$i<count($arregloCadena);$i++)
		echo $arregloCadena[$i]."<br>";
?>