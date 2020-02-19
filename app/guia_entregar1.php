<?php 
session_start(); 
include('includes/main.php'); 
include('adodb/tohtml.inc.php'); 

extract($_REQUEST);		
//require_once('includes/header.php');
$username=$sUsername;

include("class/c_guia.php");
$oguia=new c_guia();

$oguia->despachar($conn,$id,$username);

$destino="location:guia_view_window.php?id=".$id;
header($destino);

?>