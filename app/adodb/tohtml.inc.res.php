<?php 
/*
V3.31 17 March 2003  (c) 2000-2003 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence.
  
  Some pretty-printing by Chris Oxenreider <oxenreid@state.net>
*/ 
  
// specific code for tohtml
GLOBAL $gSQLMaxRows,$gSQLBlockRows;
	 
$gSQLMaxRows = 1000; // max no of rows to download
$gSQLBlockRows=20; // max no of rows per table block

// RecordSet to HTML Table
//------------------------------------------------------------
// Convert a recordset to a html table. Multiple tables are generated
// if the number of rows is > $gSQLBlockRows. This is because
// web browsers normally require the whole table to be downloaded
// before it can be rendered, so we break the output into several
// smaller faster rendering tables.
//
// $rs: the recordset
// $ztabhtml: the table tag attributes (optional)
// $zheaderarray: contains the replacement strings for the headers (optional)
//
//  USAGE:
//	include('adodb.inc.php');
//	$db = ADONewConnection('mysql');
//	$db->Connect('mysql','userid','password','database');
//	$rs = $db->Execute('select col1,col2,col3 from table');
//	rs2html($rs, 'BORDER=2', array('Title1', 'Title2', 'Title3'));
//	$rs->Close();
//
// RETURNS: number of rows displayed

function donde_estoy(){
	global $id_aplicacion;
	global $id_subaplicacion;
	global $conn;
	$query='select nombre_aplicacion,file_aplicacion ';
	$query.=' from aplicaciones ';
	$query.=' where id_aplicacion='.$id_aplicacion;
	$rs = &$conn->Execute($query);
	if (!$rs||$rs->EOF) die(texterror('No usuario.'));
	echo "<a href=".trim($rs->fields[1])."?id_aplicacion=".$id_aplicacion.">".trim($rs->fields[0])."</a>";
	
	if($id_subaplicacion!=""){
		$query='select nombre_subaplicacion,file_subaplicacion ';
		$query.=' from subaplicacion ';
		$query.=' where id_subaplicacion='.$id_subaplicacion;
		$rs = &$conn->Execute($query);
		if (!$rs||$rs->EOF) die(texterror('No usuario.'));
		echo "&nbsp;&nbsp;--->&nbsp;&nbsp;<a href=".trim($rs->fields[1])."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion.">".trim($rs->fields[0])."</a>";
	}
	
}

function build_table(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';
					
	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC"><td nowrap class="table_hd">Item</td>';
	for ($i=0; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);	
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	while (!$rs->EOF) {
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
        $s .= "	<TD valign=top nowrap align=right>".($rows+1)."&nbsp;</TD>\n";
		
		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
			$i < $ncols; 
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {
			
			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"D d, M Y, h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"D d, M Y") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
	
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";

	if ($docnt) print "<H2>".$rows." Rows</H2>";
	
	return $rows;
 }

//pv
/*
	* funcion para mostrar en una tabla datos con checkboxs para eliminar y para modificar
	* retorna el nro de filas
	*parametros:

	* $rs: the recordset
	* $ztabhtml: the table tag attributes (optional)
	* $zheaderarray: contains the replacement strings for the headers (optional)
	* $titulo: titulo de la tabla
	* $icono: 
	* $width: ancho de la tabla
	* $htmlspecialchars=true
	* $check_nombre: nombre de los campos checkboxs
	* $url_modify: url de destino para modificar los datos
	* $total_hidden: nombre para el campo hidden que tiene el nro total de filas
	* $param_modify: parametros que deban pasarse al url_modify para ser concatenados 
*/
function build_table_admin(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';
					
	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);	
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	//pv
	$vic=0;
	while (!$rs->EOF) {
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
			$i < $ncols; 
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {
			
			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"D d, M Y, h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"D d, M Y") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				//pv
				if(($i==0) || ($i==($ncols-1)))
				{
				  if($i==0)
				  {
					$s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$vic. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
				  }
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click Here</a></TD>\n";	
					}
					else
					{
						$vvalor=trim($v);
						$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
						$s .= "	<TD valign=top nowrap> <a href='#' onClick='".$caux."'>Click Here</a></TD>\n";
						//$s .= "	<TD valign=top nowrap> <a href='#' onClick='fOpenWindow('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')'>Modificar</a></TD>\n";
					}	
				 }
				}	 
				else
					$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				//pv
				if(($i==0) || ($i==($ncols-1)))
				{
				  if($i==0)
				  {
					$s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$vic. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
				  }
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click Here</a></TD>\n";	
					}
					else
					{
						$s .= "	<TD valign=top nowrap> <a href='#' onClick=('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')>Click Here</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
		$vic++;
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";
	print "<input type='hidden' name='$total_hidden' value='$vic'>\n";	
	if ($docnt) print "<H2>".$rows." Rows</H2>";
	
	return $rows;
 }
 
//
function build_table_sindel(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';

	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) {
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";

		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	//pv
	$vic=0;
	while (!$rs->EOF) {

		$s .= "<TR valign=top bgcolor='#ffffff'>\n";

		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
			$i < $ncols;
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {

			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"D d, M Y, h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"D d, M Y") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				//pv
				if($i==($ncols-1))
				{
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click Here</a></TD>\n";
					}
					else
					{
						$vvalor=trim($v);
						$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
						$s .= "	<TD valign=top nowrap> <a href='#' onClick='".$caux."'>Click Here</a></TD>\n";
						//$s .= "	<TD valign=top nowrap> <a href='#' onClick='fOpenWindow('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')'>Modificar</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";

			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				//pv
				if($i==($ncols-1))
				{
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click Here</a></TD>\n";
					}
					else
					{
						$s .= "	<TD valign=top nowrap> <a href='#' onClick=('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')>Click Here</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";

			}
		} // for
		$s .= "</TR>\n\n";

		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
		$vic++;
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {

		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";
	print "<input type='hidden' name='$total_hidden' value='$vic'>\n";
	if ($docnt) print "<H2>".$rows." Rows</H2>";

	return $rows;
 }

/*
	* funcion para mostrar en una tabla datos para anadir datos
*/
function build_add($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "date":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//link para fecha
						$resto='<a href="javascript:show_calendar(';
						$resto.="'form1.".$c_nombre."');"; 
						$resto.='" onmouseover="window.status=';
						$resto.="'Date Picker';return true;";
						$resto.='"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0>'; 
                      	$resto.='</a>';
						$campito.=$resto;
						break;						
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;						
			case "area":
						$campito='<textarea name="'.$c_nombre.'" >'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "select":
						$campito="<select name='".$c_nombre."' >";
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.='<option value="'.$valor.'">'.$texto.'</option>';	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto.'</select>';
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}

/*
	* funcion para mostrar en una tabla datos para anadir datos
	* el campo id toma el valor del id del dato q se va a actualizar, usado para recuperar los datos
*/
function build_upd($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden,$id)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
						case "date":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//link para fecha
						$resto='<a href="javascript:show_calendar(';
						$resto.="'form1.".$c_nombre."');"; 
						$resto.='" onmouseover="window.status=';
						$resto.="'Date Picker';return true;";
						$resto.='"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0>'; 
                      	$resto.='</a>';
						$campito.=$resto;
						break;
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;
			case "nada":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto=$c_valor;						  						
						$campito.=$resto;						
						break;												
			case "area":
						$campito='<textarea name="'.$c_nombre.'" >'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "radio":
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.="<input type='radio' name='".$c_nombre."' value='".$valor."' ";
							if($valor==$c_valor)
								$resto.=" checked ";
							$resto.=">".$texto."<br>";	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto;
						break;
			case "select":
						$campito="<select name='".$c_nombre."' >";
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.="<option value='".$valor."'";
							if($valor==$c_valor)
								$resto.=" selected ";
							$resto.=">".$texto."</option>";	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto."</select>";
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}


function build_show($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden,$id)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito=$c_valor;
						break;
			case "area":
						$campito=$c_valor;
						break;						
			case "password":
						$campito=$c_valor;
						break;
			case "select":						
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset
						  $band=0;
						  while ((!$rs->EOF) && (!$band))
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							if($valor==$c_valor)
							{
								$campito.=$texto;
								$band=1;
							}	
							$rs->MoveNext();	
						  }
						}  						
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}

//fin pv

/*
	* funcion para mostrar en una tabla datos para anadir datos
*/
function build_filter($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;						
			case "area":
						$campito='<textarea name="'.$c_nombre.'" >'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "select":
						$campito="<select name='".$c_nombre."' ><option value=''></option>";
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.='<option value="'.$texto.'">'.$texto.'</option>';	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto.'</select>';
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}

function rs2html(&$rs,$ztabhtml=false,$zheaderarray=false,$htmlspecialchars=true)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();
	$ncols = $rs->FieldCount();
	$hdr = "<TABLE CLASS='tableinside' COLS=$ncols $ztabhtml >\n\n";
	for ($i=0; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);	
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<TH>$fname</TH>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);

	while (!$rs->EOF) {
		
		$s .= "<TR valign=top>\n";
		
		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
			$i < $ncols; 
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {
			
			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD>".$rs->UserTimeStamp($v,"D d, M Y, h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD>".$rs->UserDate($v,"D d, M Y") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				$s .= "	<TD align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				$s .= "	<TD>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
	
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";

	if ($docnt) print "<H2>".$rows." Rows</H2>";
	
	return $rows;
 }
 
// pass in 2 dimensional array
function arr2html(&$arr,$ztabhtml='',$zheaderarray='')
{
	if (!$ztabhtml) $ztabhtml = 'BORDER=1';
	
	$s = "<TABLE $ztabhtml>";//';print_r($arr);

	if ($zheaderarray) {
		$s .= '<TR>';
		for ($i=0; $i<sizeof($zheaderarray); $i++) {
			$s .= "	<TH>{$zheaderarray[$i]}</TH>\n";
		}
		$s .= "\n</TR>";
	}
	
	for ($i=0; $isizeof($arr); $i++) {
		$s .= '<TR>';
		$a = &$arr[$i];
		if (is_array($a)) 
			for ($j=0; $jsizeof($a); $j++) {
				$val = $a[$j];
				if (empty($val)) $val = '&nbsp;';
				$s .= "	<TD>$val</TD>\n";
			}
		else if ($a) {
			$s .=  '	<TD>'.$a."/TD>\n";
		} else $s .= "	<TD>&nbsp;</TD>\n";
		$s .= "\n</TR>\n";
	}
	$s .= '</TABLE>';
	print $s;
}

function buildmenu($username){

	
	global $conn;
	global $print;
	if (!isset($print)){
		$query='select username,aplicaciones.id_aplicacion ,nombre_aplicacion,file_aplicacion,imagen_aplicacion ';
		$query.=' from aplicaciones,usuario_aplicaciones ';
		$query.=' where aplicaciones.id_aplicacion=usuario_aplicaciones.id_aplicacion and ';
		$query.='  aplicaciones.id_aplicacion=usuario_aplicaciones.id_aplicacion and usuario_aplicaciones.username='."'".$username."'";

	    $rs = &$conn->Execute($query);
	    if (!$rs||$rs->EOF) die(texterror('No usuario.'));
	
	    $recordSet1 = &$conn->Execute("select nombre from usuario where username='".$username."'");
	    if (!$recordSet1||$recordSet1->EOF) die(texterror('No usuario.'));

		echo '<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=1 BGCOLOR="#075685">
			  	<TR>
    				<TD ROWSPAN=2 BGCOLOR="#FFFFFF" WIDTH="10%" ALIGN=center VALIGN=middle><img src="images/logo.gif" border=0></TD>
    				<TD BGCOLOR="#075685"><TABLE BORDER=0 WIDTH="100%" CELLPADDING=1 CELLSPACING=0>
		        <TR>
        			<TD><SPAN onclick="location.href=\'s360.exe?\'" CLASS="ButtonTop" onmouseover="overborder(this.style,\'#90A8C8\')" onmouseout="moutborder(this.style,\'#075685\')">Home</SPAN></TD>
			        <TD><SPAN CLASS="LoginName">';
		echo $recordSet1->fields[0]; 
		echo '&nbsp;&nbsp;&nbsp;&nbsp;'.date("jS F Y H:i").'</SPAN></TD>
	          		<TD><select name="Start" onChange="i=this.selectedIndex;v=this.options[i].value;if(v)location.href=v;" CLASS="topSelector">
			              <option value="">Go to ... 
        			      <OPTION VALUE="">----</OPTION>';

	  	while (!$rs->EOF) {
		   		  echo '<option value="'.trim($rs->fields[3]).'?id_aplicacion='.trim($rs->fields[1]).'">'.trim($rs->fields[2]).'</OPTION>';
				  echo "\n";
			  	  $rs->MoveNext();
		}
		echo '</select></td>';
		echo '	
	        	 	<form name="logoutform" method=post action="logout.php">
	            		<TD VALIGN=middle>
        	      		<SPAN CLASS="ButtonTop" onclick="document.forms[\'logoutform\'].submit()" onmouseover="overborder(this.style,\'#90A8C8\')" onmouseout="moutborder(this.style,\'#075685\')">Log 
	              		out</SPAN> &nbsp; &nbsp; <SPAN CLASS="ButtonTop" onclick="helpWindow()" onmouseover="overborder(this.style, \'#90A8C8\')" onmouseout="moutborder(this.style, \'#075685\')">Help</SPAN>
						</TD>
	          		</form>
        			</TR>
      			</TABLE></TD>
			  </TR>
			  <TR>
	    		<TD BGCOLOR="#90A8C8" HEIGHT="30" VALIGN=top>';
	 	$rs->MoveFirst();
		while (!$rs->EOF) {	  
			  echo '<nobr>
			  			<SPAN class=menu onClick="location.href='."'".trim($rs->fields[3])."?id_aplicacion=".trim($rs->fields[1])."'".'" onMouseOver="over(this.style);showstatus('."'".trim($rs->fields[2])."'".');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/'.trim($rs->fields[4]).'" border=0 align=absmiddle >&nbsp;'.$rs->fields[2].'
						</SPAN>
					</nobr>';
		  	  $rs->MoveNext();
		}
		echo '
				</TD>
			  </TR>
			</TABLE>
	 	   </TD>
		  </TR>
		</TABLE>';
	}
	else
	{
		echo '<a href="javascript:print()"><img src="images/360/print.gif" border=0 alt="Print Now"></a>';
	}
}


function buildsubmenu($id_aplicacion,$default_sub_aplicacion=0){

	global $conn;
	global $print;
	if (!isset($print)){
		$query='select * from subaplicacion ';
		$query.=' where id_aplicacion='.$id_aplicacion;
	    $rs = &$conn->Execute($query);
	    if (!$rs||$rs->EOF) die(texterror('No records.'));
	

		$flag=1;$flag1=1;$contador=0;
  		while (!$rs->EOF) {
			if ($contador%10==0){
				echo '<TABLE CELLPADDING=0 CELLSPACING=0 >
					  	<TR>';
			}
			
			echo '
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="';
			if ($rs->fields[0]==$default_sub_aplicacion){
					echo 'tabselected';
			}
			else{
				if($default_sub_aplicacion==0&&$flag==1){
					echo 'tabselected';
					$flag=0;
				}
				else{
					echo 'tab';
				}
			}		
			echo	'" CELLPADDING=3 CELLSPACING=0>
        				<TR>
	          				<TD nowrap><A HREF="'.trim($rs->fields[3]).'?id_aplicacion='.trim($rs->fields[1]).'&id_subaplicacion='.trim($rs->fields[0]).'" CLASS="';
			if (trim($rs->fields[0])==$default_sub_aplicacion){
					echo 'tabtxtselected';
			}
			else{
				if($default_sub_aplicacion==0&&$flag1==1){
					echo 'tabtxtselected';
					$flag1=0;
				}
				else{
					echo 'tabtxt';
				}
			}			
			echo '" onmouseover="status='."'".trim($rs->fields[2])."'".';return true;" onmouseout="status='."''".';">
					<IMG SRC="'.trim($rs->fields[4]).'" BORDER=0 HSPACE=2 ALIGN="absmiddle">';
			echo trim($rs->fields[2]);
		    echo '     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD>';


			if ($contador%9==0&&$contador!=0){
				echo '
			    <TD VALIGN="bottom" WIDTH="100%">
					
				</td>';

				echo '</tr>
					  	</Table>';
			}
			$contador=$contador+1;
  		    $rs->MoveNext();

		}
		$tamanio=100-$contador;
		echo '<TD VALIGN="bottom" WIDTH="'.$tamanio.'%">
				<TABLE CLASS="empty" CELLPADDING=3 CELLSPACING=0 WIDTH="100%">
					<TR>
						<TD nowrap><IMG SRC="images/360/spacer.gif" WIDTH="12" HEIGHT="20"><SPAN CLASS="tabtxt">&nbsp;</SPAN>
					</TD></tr></table><td>';
		echo '  </TR>
			  </TABLE>';
		//$self_page=$_SERVER["REQUEST_URI"];
		$self_page="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		//echo $self_page;
		//global $HTTP_REFERER;
		//$self_page=$HTTP_REFERER;
		$self_page=str_replace("?","~",$self_page);
		$self_page=str_replace("&","|",$self_page);
		$self_page=str_replace("%20","Ü",$self_page);
		$script_print="printpage.php?page="."$self_page";
		echo '<SCRIPT LANGUAGE="JavaScript">function printWindow() { window.open("'.$script_print.'","Print","width=580,height=400,resizable=1,scrollbars=1,toolbar=0,menubar=0,location=0");}</SCRIPT>';
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="3" CLASS="workarea">
				  <TR>
				    <TD>
			<!--                        
                        <a href="#" onclick="printWindow()">Print Preview</a><a href="printpage.php">otro Print Preview</a>
                        -->
			';
		donde_estoy();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		//echo '<a href="'.$script_print.'" onclick="printWindow()">Print Preview</a>
		echo '<a href="#" onclick="printWindow()">Print Preview</a>
						<HR NOSHADE SIZE=1 COLOR=#000000 CLASS=HRule>		
				       <table width=100%>
    				    <tr> 
				          <td >';
	/*<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="3" CLASS="workarea">
	  <TR>
	    <TD> <SCRIPT LANGUAGE="JavaScript">function helpWindow() { window.open('s360.exe?page=CalendarHelp','Help','width=680,height=500,resizable=1,scrollbars=1,toolbar=0,menubar=0,location=0');}</SCRIPT> 
	      <a href="s360.exe?page=AGIndex">Home</a> &gt; Day View 
	      <HR NOSHADE SIZE=1 COLOR=#000000 CLASS=HRule> <SCRIPT LANGUAGE="JavaScript">
	function showFullContents(dobj)
	{
	var dwidth = parseInt(dobj.style.width) * 1.5;
	var dheight = parseInt(dobj.style.height) * 1.5;
	dobj.style.zIndex = 90;
	if (dheight < 200) dobj.style.height = dheight;
	if (dwidth < 200) dobj.style.width = dwidth;
	dobj.style.clip = "rect(auto,"+dwidth+"px,"+dheight+"px,auto)";
	}
	function hideFullContents(dobj,dheight,dwidth,dzindex)
	{
	dobj.style.zIndex = dzindex;
	dobj.style.height = dheight;
	dobj.style.width = dwidth;
	dobj.style.clip = "rect(auto,"+dwidth+"px,"+dheight+"px,auto)";
	}

	</SCRIPT>
	      <script language="JavaScript">
	<!--
	function ov(e) { e.style.background="#90A8C8";e.style.cursor="hand"   ; }
	function ot(e) { e.style.background="#ffffff";e.style.cursor="default"; }
	function co()  { location.href="s360.exe?page=CalendarEntry&gid=&id=16&date=dt.2003.4.20.9.15.48&bdate=dt.2003.4.20.9.15.48&bp=d"; }
	function ct(t,et) { location.href="s360.exe?page=CalendarEntry&gid=&id=16&date=dt.2003.4.20.9.15.48&bdate=dt.2003.4.20.9.15.48&bp=d&dh=tm."+t+".0.0&dhend=tm."+et+".0.0"; }
	//-->
	</script> 
	      <table width=100%>
	        <tr> 
	          <td >CONTENIDO</td>
	        </tr>
	      </table>
      
	    </TD>
	  </TR>
	</TABLE>	*/
	
	}
}

function buildsubmenufooter(){

		echo'	</td>
        				</tr>
				      </table>
      
			    	</TD>
				  </TR>
			  </TABLE>';
			  
}
