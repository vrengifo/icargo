<?php
$day=29;
$month=1;
$year=1979;

$bdayunix=mktime("","","",$month,$day,$year);
$nowunix=mktime();
$ageunix=$nowunix-$bdayunix;
$age=floor($ageunix / (365*24*60*60));//convert seconds to years
echo "the age is $age";

echo "<br>las variables ingresadas son:<br>";
echo "bdayunix $bdayunix<br>";
echo "nowunix $nowunix<br>";
echo "ageunix $ageunix<br>";
echo "age $age<br>";

//horas
$horas=29458.5;
$dateact=array(1988,8,17);
$h2s=floor($horas*(60*60));//valor en segundos
//$th2s=getdate($h2s);
$origendate=mktime("","","",$dateact[1],$dateact[2],$dateact[0]);
$nextdate=$th2s+$origendate;
$fecha=date("Y-m-d",$nextdate);
echo "$fecha <br>";


?>