<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  //$conn->debug=true;		
?>
	<br>
	<form action="" method="post" name="form1">
	<?php
		$principal="desembarque.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;		
	?>
	<input type="hidden" name="principal" value="<?=$principal?>">
	<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
    <br>
<?php	
  include_once("class/c_oficina.php");
  $oOfi=new c_oficina($conn);
  $oOfi->info($sOficina);
  
  include_once("class/c_manifiesto_embarque.php");
  $oME=new c_manifiesto_embarque($conn,$sUsername);
  
  //$oME->envioEmbarque($idEmb);
  	
  //listar todos los manifiestos q hayan sido enviados y que tengan como destino la est de Soficina
  
  $sql=<<<va
  select to_char(me.manemb_fecha,'$oME->fechaCorta'),me.manemb_vuelo,me.manemb_nro,
  e1.est_nombre,e2.est_nombre,
  me.manemb_id 
  from manifiesto_embarque me, estacion e1, estacion e2 
  where
  e1.est_codigo=me.manemb_origen and e2.est_codigo=me.manemb_destino
  and me.manemb_enviado='1' 
  and nvl(me.manemb_finalizado,'0')<>'1'
  and e2.est_codigo='$oOfi->est_codigo' 
  order by me.manemb_fecha desc,me.manemb_id desc,e1.est_nombre
va;

  //echo $sql."<br>";
  $rs = &$conn->Execute($sql);
  if ($rs->EOF) 
    echo('<hr>No Records found.<hr>');
  else
  {
    $mainheaders=array("Fecha","Vuelo","Nro. Manifiesto","Est. Origen","Est. Destino","Escoja Manifiesto");

  $titulo="Manifiestos ";		
			build_table_sindel($rs,false,$mainheaders,$titulo,
			'images/360/yearview.gif','50%','true','desembarque1.php',$param_destino,"total",0);
			//variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
			$cextra="id_aplicacion|id_subaplicacion|principal";
		}
?>
		<input type="hidden" name="cextra" value="<?=$cextra?>">
	</form>	
<?php
		buildsubmenufooter();		
?>
</body>
</html>