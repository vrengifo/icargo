<?php session_start(); ?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<? include('class/c_user.php'); ?>
<?php
	extract($_REQUEST);		
?>
<?php
//pv
$cait=new c_user();

//$t_chc=count(chc);
//echo $t_chc."<br>";
for($i=0;$i<$total;$i++)
{
  if(isset($chc[$i]))
  {
  	$id=$chc[$i];
	//echo "borrar $id <br>";	
	$cait->del($conn,$id);
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
	<br>
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
