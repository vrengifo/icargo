<?php 
session_start(); 
include('includes/main.php'); 
include('adodb/tohtml.inc.php'); 
//$conn->debug=true;
extract($_REQUEST);		
//require_once('includes/header.php');
$username=$sUsername;

include("class/c_documento.php");
$oDoc=new c_documento($conn,$sUsername,$sTerminal);

$oDoc->despachar($id);

$destino="location:documento_viewWindow.php?id=".$id;
header($destino);

?>