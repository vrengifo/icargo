<?php 

include("config.php");

// End of Editing ----------------------------------------------------------------------------------------//

$from = $HTTP_REFERER;

	if (preg_match("/$domain/", $from)) {
		header("Location: noaccess.php");
		exit;
	} 
	else{

		if ($from != ""){
			$read = fopen($HTTP_REFERER, "rb");
			$value = "";
				while(!feof($read)){
					$value .= fread($read, 4096); 
				}				
				fclose($read);
			$start= strpos($value, "$startingpoint"); 
			$finish= strpos($value, "$endingpoint"); 
			$length= $finish-$start;
			$value=substr($value, $start, $length);

		function imgsrc_strip($variable){
			return(eregi_replace("<img src=[^>]*>", "", $variable));
		}
		function imgbor_strip($variable){
			return(eregi_replace("<img border=[^>]*>", "", $variable));
		}	
		function i_stripf($variable){
			return(eregi_replace("<font[^>]*>", "", $variable));
		}

	$printpage = ("$value"); 
	$printpage = imgsrc_strip("$printpage");
	$printpage = imgbor_strip("$printpage");

	$printpage = i_stripf("$printpage");
	$printpage = str_replace( "</font>", "", $printpage );
	$printpage = stripslashes("$printpage"); 

	}
}

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-gb">
<META NAME="Title" CONTENT="<? echo $title?>">
<META NAME="Author" CONTENT="CJ Website Design">
<title><? echo $title?></title>
<link rel="stylesheet" href="css.php" type="text/css">
<body>
<p align="right"><font size="4"><? echo $website?> - Printer Friendly Page<br></font><font size="2">© V1.0</font></p>
<hr>
<? echo $printpage?>
<?
if ($from == ""){
	echo "$nonreferer";
	$from = "$nosent";
}
?>
<hr>
<br><br><i>Printed from: <a href="<? echo $from?>"><? echo $from?></a></i><br><br>
</body>
</html>
