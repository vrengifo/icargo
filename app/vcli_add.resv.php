<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  /*
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  */
  
  include_once("class/c_cliente.php");
  $oObj=new c_cliente($conn);
  
  if(!isset($Add))
  {
    echo($oObj->adminAddSinConvenio("vcli_add.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Cliente Sin Convenio"));
    echo'<input type="button" name="clickb" value="clickb" onclick="self.opener.location.reload();" >';
  }
  else //se hizo el submit de la pagina 
  {
    $cbase=explode("|",$campo_base);
    $t_cbase=count($cbase);
    
    for($i=0;$i<$t_cbase;$i++)
    {
	  $dato[$i]=$$cbase[$i];
    }
  
    $resCarga=$oObj->cargar_dato($dato);
    if($resCarga)
    {
  	  $idp=$oObj->add();
  	/*
  	if($idp=="0")
  	  $cait->msg="Faltan datos!!!";  	    
  	*/  
    }
    
    //destino
    $cextra=explode("|",$campo_extra);
    $t_cextra=count($cextra);
    for ($i=0;$i<$t_cextra;$i++)
    {
	  $c1=$cextra[$i];
	  /*
	  if($c1=="principal")	
	    $destino="location:".$$c1."?";
	  else	
	  */	
	  $cad_dest.=$c1."=".$$c1."&";
    }
    $cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
    $destino="location:vcli_add.php?".$cad_dest."&id=".$idp."&act=add";
    $destinoE=$principal."?".$cad_dest."&id=".$idp;
    
    //html de page cuando se hizo submit
   if(strlen($cait->msg)>0)
    {
  ?>
   <html>
   <script language="javascript">
     function mensaje(msg)
	 {
	   alert(msg);
	 }
   </script>
     <body onLoad="mensaje('<?=$cait->msg?>');self.location='<?=$destinoE?>';">
	 </body>
   </html>
   <?
    }
  else
  {
    //echo "$destino";
    //header($destino);
   ?>
   <html>
     <body>
     <br><br>
     <center>
       Cliente Añadido: <?=$cait->cli_nombre?>
       <input type="button" name="Cerrar" value="Cerrar" onclick="alert(self.opener);self.opener.reload();window.close();">
     </center>
	 </body>
   </html>
   <?
    }
    
    
    
  }
  
  buildsubmenufooter();
?>