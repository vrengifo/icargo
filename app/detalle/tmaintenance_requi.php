<?php 
session_start(); 
//comentario
// idp es el id del padre, o id de la cabecera
// en este caso idp es el id del componente 
?>
<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<?php

		extract($_REQUEST);		
		require_once('includes/header.php');
		$username=$session_username;
		//buildmenu($username);
		//buildsubmenu($id_aplicacion,$id_subaplicacion);
		///todo  el html como se quiera
	?>
	
	<br>
	<form action="tmar_del.php" method="post" name="form1">
	<?php				
		//mostrar el componente seleccionado, solo los datos
		
		$sql="select tat_id,tas_number,tas_modif,tas_revision,"
			."tas_revision_date,tas_description,tas_remark,tas_issue_date,"
			."tas_start_by_date,tas_complete_by_date,tas_cancelled,tas_initial_hours2,"			
			."tas_initial_cycles2,tas_initial_days,tas_memo,tas_ata_code "
			."from mai_ota_task "
			."where tas_id=$idp";		
		
		//echo "$sql <br>";	
		$rs = &$conn->Execute($sql);
		if(!$rs->EOF)
		{
		  $v_tat_id=$rs->fields[0];
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
		,$campo,$campo_hidden,$idp);			
	?>
	<hr width="100%" align="center" size="2">
	
	<?php
		$principal="tmaintenance_requi.php";
		$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp;		
		//$idp es el id del componente
	?>


<SCRIPT LANGUAGE="JavaScript">
function valida() {

define('tas0', 'string', 'Task Type');
define('tas1', 'string', 'Task Number');
define('tas2', 'string', 'Modif');
define('tas15', 'string', 'Ata Code');
define('tas3', 'string', 'Revision');
define('tas4', 'string', 'Revision Date');
define('tas5', 'string', 'Description');
define('tas7', 'string', 'Issue Date');
define('tas8', 'string', 'Start by date');
define('tas9', 'string', 'Complete by date');
define('tas10', 'string', 'Cancelled');
define('tas11', 'string', 'Initial Hours 2');
define('tas12', 'string', 'Initial Cycles 2');
define('tas13', 'string', 'Initial Days');


}
</script>
	
  <input type="button" name="Add" value="Add" onClick="self.location='tmar_add.php<?=$param_destino?>';valida();return returnVal;">
  <input type="submit" name="Del" value="Del" onClick="return confirmdeletef();">
    <?php
    $principal="task.php";
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&id=".$idp;
  ?>
    <input type="button" name="back" value="Close"  onClick="self.opener.location.reload();window.close();">
		<br>			
<?php	
        //$sql="select m.tmr_id,ct.cot_code,m.tmr_limit,p.par_name,ty.typ_name,m.tmr_action,m.tmr_ref,m.tmr_id "
		$sql="select m.tmr_id,m.tmr_limit,p.par_name,ty.typ_name,m.tmr_action,m.tmr_ref,m.tmr_id "
			//."from mai_ota_task_maintenance_requi m,mai_ota_component_type ct,mai_ota_task t,mai_ota_parameter p,mai_ota_typ ty "
			."from mai_ota_task_maintenance_requi m,mai_ota_task t,mai_ota_parameter p,mai_ota_typ ty "
			//."where ct.cot_id=m.cot_id and p.par_id=m.par_id and ty.typ_id=m.typ_id and t.tas_id=m.tas_id "
			."where p.par_id=m.par_id and ty.typ_id=m.typ_id and t.tas_id=m.tas_id "
			." and m.tas_id=$idp "
			."order by m.tmr_id";
		
		//echo $sql."<br>";
		$recordSet = &$conn->Execute($sql);
        if (!$recordSet||$recordSet->EOF) 
			die(texterror('No Maintenance Requirements defined.'));
		else
		{
			$principal="tmaintenance_requi.php";
			$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idp;

			//$mainheaders=array("Del","O/H","Limit","Interval","Typ","Action","Reference","Applic/Mod","Modify");		
			$mainheaders=array("Del","Limit","Interval","Typ","Action","Reference","Modify");		
			build_table_admin($recordSet,false,$mainheaders,'Maintenance Requirements ',
			'images/360/yearview.gif','50%','true','chc','tmar_upd.php',$param_destino,"total");
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
