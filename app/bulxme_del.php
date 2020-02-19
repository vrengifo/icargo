<?php
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include('class/c_manembxbulto.php');
  include('class/c_bulto.php');
  
  extract($_REQUEST);
  
  $oMEXB=new c_manembxbulto($conn,$sUsername);
  $oBulto=new c_bulto($conn,$sUsername);
  for($i=0;$i<$total;$i++)
  {
    if(isset($chc[$i]))
    {
      $oMEXB->cad2id($chc[$i]);
      $bulref=$oMEXB->bul_ref;
      $oMEXB->del($chc[$i]);
      $oBulto->del($bulref);
    }
  }

//destino
$cextra=explode("|",$cextra);
$t_cextra=count($cextra);
$cad_dest="";
for ($i=0;$i<$t_cextra;$i++)
{
	$c1=$cextra[$i];
	if($c1=="principal")
	  $destino="location:".$$c1."?";
	else	
	  $cad_dest.=$c1."=".$$c1."&";
	//echo "c1 $c1 -- ".$$c1." <br>";  
}
$cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
$destino=$destino.$cad_dest;
header($destino);
?>