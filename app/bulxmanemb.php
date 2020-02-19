<?php 
  session_start(); 
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$session_username;
?>
	
	<br>
	<form action="bulxme_del.php" method="post" name="form1">
	<?php				
		include_once("class/c_manembxbulto.php");
		$oMEXB=new c_manembxbulto($conn,$sUsername);
		$cadId=$oMEXB->armarSqlId("mexb.","mexb.");
		$sql=<<<va
		select $cadId a1,
		to_char(me.manemb_fecha,'$oMEXB->fechaCorta') fecha,me.manemb_vuelo,
		b.bul_ref,
		$cadId a2
		from manifiesto_embarque me, bulto b, manembxbulto mexb
		where 
		me.manemb_id=mexb.manemb_id and b.bul_ref=mexb.bul_ref
		and mexb.manemb_id=$idp
		order by fecha desc, me.manemb_vuelo, b.bul_ref
va;
		$rs = &$conn->Execute($sql);
		
/*
Query de consulta de los datos q se han quedado
ojo ojo ojo ojo 		
		select bul_ref 
from manembxbultoqueda
where
manemb_id in 
(
  select manemb_id 
  from manifiesto_embarque
  where manemb_origen||manemb_destino = 
  (
    select manemb_origen||manemb_destino
    from manifiesto_embarque
    where manemb_id=101
  )
)
and bul_ref in
(
  select distinct bq.bul_ref
  from manembxbultoqueda bq, manembxbultoreal br
  where bq.bul_ref<>br.bul_ref
)
*/		
		
		
		//echo "$sql <br>";	
/*		
		if(!$rs->EOF)
		{
		  $vId=$rs->fields[0];
		  $v_number=$rs->fields[1];
		  $v_modif=$rs->fields[2];
		  $v_revision=$rs->fields[3];
		  $v_revision_date=$rs->fields[4];
		  $v_description=$rs->fields[5];
		  $v_remark=$rs->fields[6];
		  $v_issue_date=$rs->fields[7];
		  
		  $v_start_by_date=$rs->fields[8];
		  $v_complete_by_date=$rs->fields[9];
		  $v_cancelled=$rs->fields[10];
		  $v_initial_hours2=$rs->fields[11];
		  $v_initial_cycles2=$rs->fields[12];
		  $v_initial_days=$rs->fields[13];
		  $v_memo=$rs->fields[14];
		  $v_ata_code=$rs->fields[15];		  
		}
		$rs->Close();
		$sql_tat="select tat_id,tat_code from mai_ota_task_type order by tat_code";		
		$campo=array(
						array("etiqueta"=>"* Task Type","nombre"=>"tas0","tipo_campo"=>"select","sql"=>$sql_tat,"valor"=>$v_tat_id),
						array("etiqueta"=>"* Task Number","nombre"=>"tas1","tipo_campo"=>"text","sql"=>"","valor"=>$v_number),
						array("etiqueta"=>"* Modif","nombre"=>"tas2","tipo_campo"=>"text","sql"=>"","valor"=>$v_modif),
						array("etiqueta"=>"* Ata Code","nombre"=>"tas15","tipo_campo"=>"text","sql"=>"","valor"=>$v_ata_code),						
						array("etiqueta"=>"* Revision","nombre"=>"tas3","tipo_campo"=>"text","sql"=>"","valor"=>$v_revision),
						array("etiqueta"=>"* Revision Date","nombre"=>"tas4","tipo_campo"=>"text","sql"=>"","valor"=>$v_revision_date),
						array("etiqueta"=>"* Description","nombre"=>"tas5","tipo_campo"=>"text","sql"=>"","valor"=>$v_description),
						array("etiqueta"=>" Remark","nombre"=>"tas6","tipo_campo"=>"text","sql"=>"","valor"=>$v_remark),
						array("etiqueta"=>"* Issue Date","nombre"=>"tas7","tipo_campo"=>"text","sql"=>"","valor"=>$v_issue_date),
						
						array("etiqueta"=>"* Start by date","nombre"=>"tas8","tipo_campo"=>"text","sql"=>"","valor"=>$v_start_by_date),
						array("etiqueta"=>"* Complete by date","nombre"=>"tas9","tipo_campo"=>"text","sql"=>"","valor"=>$v_complete_by_date),
						array("etiqueta"=>"* Cancelled","nombre"=>"tas10","tipo_campo"=>"text","sql"=>"","valor"=>$v_cancelled),
						array("etiqueta"=>"* Initial Hours 2","nombre"=>"tas11","tipo_campo"=>"text","sql"=>"","valor"=>$v_initial_hours2),
						array("etiqueta"=>"* Initial Cycles 2","nombre"=>"tas12","tipo_campo"=>"text","sql"=>"","valor"=>$v_initial_cycles2),
						array("etiqueta"=>"* Initial Days","nombre"=>"tas13","tipo_campo"=>"text","sql"=>"","valor"=>$v_initial_days),
						array("etiqueta"=>" Memo","nombre"=>"tas14","tipo_campo"=>"area","sql"=>"","valor"=>$v_memo)						
					);
		$campo_hidden=array(
							array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
					  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
							array("nombre"=>"idp","valor"=>$idp),
							array("nombre"=>"principal","valor"=>"tmaintenance_requi.php")
							);
		//construye el html para los campos relacionados
		build_show($conn,'false',"Task's Information","images/360/taskwrite.gif","50%",'true'
		,$campo,$campo_hidden,$idp);			*/
	?>
	<hr width="100%" align="center" size="2">
	
<?php
		$principal="bulxmanemb.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp;
?>

  <input type="hidden" name="principal" value="<?=$principal?>">
  <input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
  <input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
  <input type="hidden" name="idp" value="<?=$idp?>">
	
  <input type="button" name="Add" value="Crear Bulto" onClick="self.location='bulxme_add.php<?=$param_destino?>';valida();return returnVal;">
  
  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  <!--
  <input type="button" name="back" value="Cerrar"  onClick="self.opener.location.reload();window.close();">
  -->
  <input type="button" name="back" value="Cerrar"  onClick="window.close();">
		<br>			
<?php	
		$rs= &$conn->Execute($sql);
        if ($rs->EOF) 
			echo('<hr>No se encontraron datos.!!!<hr>');
		else
		{
			//cambiar # por bulxme_upd.php
			$mainheaders=array("Eliminar","Fecha","Vuelo","Bulto","Modificar");		
			build_table_admin($rs,false,$mainheaders,'Bultos por Manifiesto',
			'images/360/yearview.gif','50%','true','chc','bulxme_upd.php',$param_destino,"total");
			//variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
			$cextra="id_aplicacion|id_subaplicacion|principal|idp";
		}
?>
		<input type="hidden" name="cextra" value="<?=$cextra?>">
	</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>