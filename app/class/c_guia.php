<?php
//clase para guia
class c_guia
{
//campos comunes para guías y facturas
var $gui_id;//autoincrement de la guía
var $gui_nro;
var $tipgui_id;
var $tipro_id;
var $gui_fecha;
var $gui_piezas;
var $gui_peso;
var $gui_volumen;
var $gui_vdeclarado;
var $gui_descripcion;	
var $gui_condocumento;
var $gui_observacion;
var $gui_obs_sindinenijoya;
var $gui_obs_sujetoespacio;
var $gui_obs_cuentariesgorem;
var $ent_id;//entregado en
var $cli_codigo;
var $usu_codigo;
var $est_origen;
var $gui_remitente;
var $gui_ced_remitente;
var $gui_dir_remitente;
var $gui_telf_remitente;
var $gui_destinatario;
var $gui_ced_destinatario;
var $gui_dir_destinatario;
var $gui_telf_destinatario;
var $gui_fletecarga;
var $gui_entregadomicilio;
var $gui_transadicional;
var $gui_servadicional;
var $gui_correspondencia;
var $gui_seguros;
var $gui_embalaje;
var $gui_subtotal;
var $gui_descuento;
var $gui_iva;
var $gui_total;
var $ciu_codigo;
//en caso de guía de remisión
var $gui_sobrepeso;
//campos encontrados en la tabla
var $est_destino;//no usada ... probablemente borrar de la base porque las estaciones son como las agencias en una ciudad y el destino es una ciudad
var $gui_uaudit;
var $gui_faudit;
var $repven_id;
var $manemb_id;
var $gui_entregada;
var $gui_entregadapor;
var $guifechaentrega;

var $separador_base;
var $separador_js;
var $separador_php;
  
  //constructor
  function c_guia()
  {
	$this->gui_id=0;
	$this->gui_nro="-";
	$this->tipgui_id="-";
	$this->tipro_id="-";
	$this->gui_fecha="-";
	$this->gui_piezas="-";
	$this->gui_peso="0";
	$this->gui_volumen="0";
	$this->gui_vdeclarado="0";
	$this->gui_descripcion="-";;	
	$this->gui_condocumento="0";;
  	$this->gui_observacion="-";;
	$this->gui_obs_sindinenijoya="0";
	$this->this->gui_obs_sujetoespacio="0";
	$this->gui_obs_cuentariesgorem="0";
	$this->ent_id="-";
	$this->cli_codigo="-";
	$this->usu_codigo="-";
	$this->est_origen="-";
	$this->gui_remitente="-";
	$this->gui_ced_remitente="-";
	$this->gui_dir_remitente="-";
	$this->gui_telf_remitente="-";
	$this->gui_destinatario="-";
	$this->gui_ced_destinatario="-";
	$this->gui_dir_destinatario="-";
	$this->gui_telf_destinatario="-";
	$this->gui_fletecarga="0";
	$this->gui_entregadomicilio="0";
	$this->gui_transadicional="0";
	$this->gui_servadicional="0";
	$this->gui_correspondencia="0";
	$this->gui_seguros="0";
	$this->gui_embalaje="0";
	$this->gui_subtotal="0";
	$this->gui_descuento="0";
	$this->gui_iva="0";
	$this->gui_total="0";
	$this->ciu_codigo="-";
	$this->gui_sobrepeso="0";
	
	$this->separador_base=",";
	$this->separador_js=".";
    $arrayLocale=localeconv();
    $this->separador_php=$arrayLocale["decimal_point"];	
	
  }
  
  function base2php(&$variable)
  {
  	$variable=str_replace($this->separador_base,$this->separador_php,$variable);
  }
  
  function base2js(&$variable)
  {
  	$variable=str_replace($this->separador_base,$this->separador_js,$variable);
  }
  
  function js2base(&$variable)
  { 
	$variable=str_replace($this->separador_js,$this->separador_base,$variable);
  }
  
  function js2php(&$variable)
  {
  	$variable=str_replace($this->separador_js,$this->separador_php,$variable);
  }        
  
  function php2base(&$variable)
  {
  	$variable=str_replace($this->separador_php,$this->separador_base,$variable);
  }  

  function cargar_dato($dato)			
  {
    $ncampos=14;
	if($ncampos==count($dato))
	{
      $this->ait_id=$dato[0];
      $this->air_id_name=$dato[1];
      $this->air_sn=$dato[2];
      $this->air_variable_number=$dato[3];
      $this->air_line_number=$dato[4];
      $this->air_model=$dato[5];
      $this->air_owner=$dato[6];
      $this->air_current_hours=$dato[7];
      $this->air_current_landings=$dato[8];
      $this->air_last_update=$dato[9];
      $this->air_last_log_page=$dato[10];
      $this->air_landings_per_month=$dato[11];
	  $this->air_hours_per_landing=$dato[12];
      $this->air_remark=$dato[13];
	} 
  }
  
  function validar()
  {
	//$this->gui_nro="-";
	//$this->tipgui_id="-";
	//$this->tipro_id="-";
	if(strlen($this->gui_fecha)<=0)
	  $this->gui_fecha=date("Y-m-d H:i");
	//$this->gui_piezas="-";
	if(strlen($this->gui_peso)<=0)
	  $this->gui_peso="0";
	//$this->gui_volumen="0";
	//$this->gui_vdeclarado="0";
	if(strlen($this->gui_descripcion)<=0)
	  $this->gui_descripcion="-";	
	if(strlen($this->gui_condocumento)<=0)
	  $this->gui_condocumento="0";
    if(strlen($this->gui_observacion)<=0)	
	  $this->gui_observacion="-";
	if(strlen($this->gui_obs_sindinenijoya)<=0)
	  $this->gui_obs_sindinenijoya="0";
	if(strlen($this->gui_obs_sujetoespacio)<=0)
	  $this->gui_obs_sujetoespacio="0";
	if(strlen($this->gui_obs_cuentariesgorem)<=0)
	  $this->gui_obs_cuentariesgorem="0";
	//$this->ent_id="-";
	//$this->cli_codigo="-";
	//$this->usu_codigo="-";
	//$this->est_origen="-";
	if(strlen($this->gui_remitente)<=0)
	  $this->gui_remitente="-";
	if(strlen($this->gui_ced_remitente)<=0)  
	  $this->gui_ced_remitente="-";
	if(strlen($this->gui_dir_remitente)<=0)
	  $this->gui_dir_remitente="-";
	if(strlen($this->gui_telf_remitente)<=0)
	  $this->gui_telf_remitente="-";
	if(strlen($this->gui_destinatario)<=0)
	  $this->gui_destinatario="-";
	if(strlen($this->gui_ced_destinatario)<=0)
	  $this->gui_ced_destinatario="-";
	if(strlen($this->gui_dir_destinatario)<=0)
	  $this->gui_dir_destinatario="-";
	if(strlen($this->gui_telf_destinatario)<=0)
	  $this->gui_telf_destinatario="-";
	if(strlen($this->gui_fletecarga)<=0)
	  $this->gui_fletecarga=0;
	if(strlen($this->gui_entregadomicilio)<=0)
	  $this->gui_entregadomicilio=0;
	if(strlen($this->gui_transadicional)<=0)
	  $this->gui_transadicional=0;
	if(strlen($this->gui_servadicional)<=0)
	  $this->gui_servadicional=0;
	if(strlen($this->gui_correspondencia)<=0)
	  $this->gui_correspondencia=0;
	if(strlen($this->gui_seguros)<=0)
	  $this->gui_seguros=0;
	if(strlen($this->gui_embalaje)<=0)
	  $this->gui_embalaje=0;
	if(strlen($this->gui_subtotal)<=0)
	{
	  $vsubtotal=$this->gui_fletecarga+$this->gui_entregadomicilio+$this->gui_transadicional+$this->gui_servadicional+$this->gui_correspondencia+$this->gui_seguros+$this->gui_embalaje;
	  $this->gui_subtotal=$vsubtotal;
	}
	if(strlen($this->gui_descuento)<=0)
	  $this->gui_descuento="0";
	if(strlen($this->gui_iva)<=0)
	{
	  $this->gui_iva=($this->gui_subtotal)*(0.12);
	}
	if(strlen($this->gui_total)<=0)
	{
	  $this->gui_total=$this->gui_subtotal+$this->gui_iva-$this->gui_descuento;
	}
	//$this->ciu_codigo="-";
	if(strlen($this->gui_sobrepeso)<=0)
	  $this->gui_sobrepeso="0";
	$this->cambiarbase();  
  }  
  
  function cambiarbase()
  {
    $this->js2base($this->gui_fletecarga);
	$this->js2base($this->gui_entregadomicilio);
	$this->js2base($this->gui_transadicional);
	$this->js2base($this->gui_servadicional);
	$this->js2base($this->gui_correspondencia);
	$this->js2base($this->gui_seguros);
	$this->js2base($this->gui_embalaje);
	$this->js2base($this->gui_subtotal);
	$this->js2base($this->gui_descuento);
	$this->js2base($this->gui_iva);
	$this->js2base($this->gui_total);
  }
  
  function mostrar_dato()
  {
    echo($this->gui_fletecarga."<br>");
	echo($this->gui_entregadomicilio."<br>");
	echo($this->gui_transadicional."<br>");
	echo($this->gui_servadicional."<br>");
	echo($this->gui_correspondencia."<br>");
	echo($this->gui_seguros."<br>");
	echo($this->gui_embalaje."<br>");
	echo($this->gui_subtotal."<br>");
	echo($this->gui_descuento."<br>");
	echo($this->gui_iva."<br>");
	echo($this->gui_total."<br>");
  }  
  
  //funciones con base de datos
  function add_guia($con)
  {
  	$this->validar();
	//$this->mostrar_dato();
	//campos seteados con 0
	$this->repven_id=0;//reporte de ventas
	$this->manemb_id=0;//manifiesto de embarque
	$this->gui_entregada="0";//indica si la guía fue entregada
		
	$sql="insert into guia "
		."(gui_nro,tipgui_id,tipro_id,gui_fecha,gui_piezas,gui_peso,gui_volumen,gui_vdeclarado,gui_descripcion,gui_condocumento,"
  	    ."gui_observacion,gui_obs_sindinenijoya,gui_obs_sujetoespacio,gui_obs_cuentariesgorem,ent_id,cli_codigo,usu_codigo,"
	    ."est_origen,gui_remitente,gui_ced_remitente,gui_dir_remitente,gui_telf_remitente,gui_destinatario,gui_ced_destinatario,"
	    ."gui_dir_destinatario,gui_telf_destinatario,gui_fletecarga,gui_entregadomicilio,gui_transadicional,gui_servadicional,"
	    ."gui_correspondencia,gui_seguros,gui_embalaje,gui_subtotal,gui_descuento,gui_iva,gui_total,ciu_codigo,gui_sobrepeso,"
		."repven_id,manemb_id,gui_entregada) "
		."values "		
		."('$this->gui_nro','$this->tipgui_id','$this->tipro_id',to_date('".$this->gui_fecha."','YYYY-MM-DD HH24:MI'),$this->gui_piezas,'$this->gui_peso',$this->gui_volumen,'$this->gui_vdeclarado','$this->gui_descripcion','$this->gui_condocumento',"
  	    ."'$this->gui_observacion','$this->gui_obs_sindinenijoya','$this->gui_obs_sujetoespacio','$this->gui_obs_cuentariesgorem','$this->ent_id','$this->cli_codigo','$this->usu_codigo',"
	    ."$this->est_origen,'$this->gui_remitente','$this->gui_ced_remitente','$this->gui_dir_remitente','$this->gui_telf_remitente','$this->gui_destinatario','$this->gui_ced_destinatario',"
	    ."'$this->gui_dir_destinatario','$this->gui_telf_destinatario','$this->gui_fletecarga','$this->gui_entregadomicilio','$this->gui_transadicional','$this->gui_servadicional',"
	    ."'$this->gui_correspondencia','$this->gui_seguros','$this->gui_embalaje','$this->gui_subtotal','$this->gui_descuento','$this->gui_iva','$this->gui_total','$this->ciu_codigo','$this->gui_sobrepeso',"
		."$this->repven_id,$this->manemb_id,'$this->gui_entregada')";
		
	$rs=&$con->Execute($sql);
	//echo "$sql <br>";	
	//recuperar el gui_id
	$sql_rec="select gui_id from guia where "
			."gui_nro='$this->gui_nro' and to_char(gui_fecha,'YYYY-MM-DD HH24:MI')='$this->gui_fecha' "
			." and usu_codigo='$this->usu_codigo' ";
	$rs=&$con->Execute($sql_rec);		
	//echo "$sql_rec <br>";
	$idp=$rs->fields[0];
	//echo "<br>$sql <br>";
	//$this->mostrar_dato();
    return ($idp);
  }
  
  function add_factura($con)
  {
  	$this->validar();
	
	//campos seteados con 0
	$this->repven_id=0;//reporte de ventas
	$this->manemb_id=0;//manifiesto de embarque
	$this->gui_entregada="0";//indica si la guía fue entregada
		
	$sql="insert into guia "
		."(gui_nro,tipgui_id,tipro_id,gui_fecha,gui_piezas,gui_peso,gui_volumen,gui_vdeclarado,gui_descripcion,gui_condocumento,"
  	    ."gui_observacion,gui_obs_sindinenijoya,gui_obs_sujetoespacio,gui_obs_cuentariesgorem,ent_id,cli_codigo,usu_codigo,"
	    ."est_origen,gui_remitente,gui_ced_remitente,gui_dir_remitente,gui_telf_remitente,gui_destinatario,gui_ced_destinatario,"
	    ."gui_dir_destinatario,gui_telf_destinatario,gui_fletecarga,gui_entregadomicilio,gui_transadicional,gui_servadicional,"
	    ."gui_correspondencia,gui_seguros,gui_embalaje,gui_subtotal,gui_descuento,gui_iva,gui_total,ciu_codigo,"
		."repven_id,manemb_id,gui_entregada) "
		."values "		
		."('$this->gui_nro','$this->tipgui_id','$this->tipro_id',to_date('".$this->gui_fecha."','YYYY-MM-DD HH24:MI'),$this->gui_piezas,'$this->gui_peso',$this->gui_volumen,'$this->gui_vdeclarado','$this->gui_descripcion','$this->gui_condocumento',"
  	    ."'$this->gui_observacion','$this->gui_obs_sindinenijoya','$this->gui_obs_sujetoespacio','$this->gui_obs_cuentariesgorem','$this->ent_id','$this->cli_codigo','$this->usu_codigo',"
	    ."$this->est_origen,'$this->gui_remitente','$this->gui_ced_remitente','$this->gui_dir_remitente','$this->gui_telf_remitente','$this->gui_destinatario','$this->gui_ced_destinatario',"
	    ."'$this->gui_dir_destinatario','$this->gui_telf_destinatario','$this->gui_fletecarga','$this->gui_entregadomicilio','$this->gui_transadicional','$this->gui_servadicional',"
	    ."'$this->gui_correspondencia','$this->gui_seguros','$this->gui_embalaje','$this->gui_subtotal','$this->gui_descuento','$this->gui_iva','$this->gui_total','$this->ciu_codigo',"
		."$this->repven_id,$this->manemb_id,'$this->gui_entregada')";		
	$rs=&$con->Execute($sql);
	//echo "$sql <br>";	
	//recuperar el gui_id
	$sql_rec="select gui_id from guia where "
			."gui_nro='$this->gui_nro' and to_char(gui_fecha,'YYYY-MM-DD HH24:MI')='$this->gui_fecha' "
			." and usu_codigo='$this->usu_codigo' ";
	$rs=&$con->Execute($sql_rec);		
	//echo "$sql_rec <br>";
	$idp=$rs->fields[0];
	//echo "<br>$sql <br>";
	//$this->mostrar_dato();
       return ($idp);
  }  
  
  function del($con,$id)
  {
    //eliminacion en cascada!!!
    //borrar assemblies
    //borrar components

    //borrar forecast_assum
    
    //borrar hc
    $hc="delete from mai_ota_aircraft_hc where air_id=$id";
	$rs = &$con->Execute($hc);
 
    $sql="delete from mai_ota_aircraft "
			."where air_id=$id ";
	$rs = &$con->Execute($sql);
 
	if($rs)
	  return $id;
	else
	  return 0;  		
  }
  function update($con,$id)
  {
	$this->validar();
  	$sql="UPDATE mai_ota_aircraft"
			." set ait_id=$this->ait_id,air_id_name='$this->air_id_name',air_sn='$this->air_sn',air_variable_number='$this->air_variable_number',"
			."air_line_number='$this->air_line_number',air_model='$this->air_model',air_owner='$this->air_owner',air_current_hours='$this->air_current_hours',"
			."air_current_landings='$this->air_current_landings',air_last_update='$this->air_last_update',air_last_log_page=$this->air_last_log_page,air_landings_per_month=$this->air_landings_per_month,"
			."air_hours_per_landing='$this->air_hours_per_landing',air_remark='$this->air_remark'"
			." WHERE air_id=$id";
//	echo "<br>$sql <br>";
//	$this->mostrar_dato();		
	$rs = &$con->Execute($sql);

    //insertar en mai_ota_aircraft_hc
    $sqli="insert into mai_ota_aircraft_hc (air_id,ahc_hours,ahc_cycles,ahc_date,ahc_act) values "
         ."($id,'$this->air_current_hours','$this->air_current_landings','$this->air_last_update','0')";
    $rs2= &$con->Execute($sqli);

	if($rs)
	  return $id;
	else
	  return 0;	
  }    

//funciones para el estado
  function estado($con,$id)
  {
  	//buscar si fue entregada o no
  	$sql="select gui_entregada from guia where gui_id=$id";
  	$rs=&$con->Execute($sql);
  	$entregada=$rs->fields[0];
  	if($entregada=="1")//entregada
  	{
  	  $sql1="select gui_entregadapor,gui_fechaentrega from guia where gui_id=$id";
  	  $rs1=&$con->Execute($sql1);
  	  $ent_por=$rs1->fields[0];
  	  $ent_fecha=$rs1->fields[1];
  	  $msg="Guía entregada por ".$ent_por." con fecha ".$ent_fecha; 
  	  $vestado="1";
  	    	  
  	}
  	else //si no es entregada
  	{  	  
  	  //si está en manifiestos de desembarque
  	  $sql1="select count(mandesdet_id) from mandes_detalle "
  	  	   ."where gui_id=$id ";
  	  //echo "<hr>$sql1</hr>";
  	  $rs1=&$con->Execute($sql1);
  	  $variable=$rs1->fields[0];
  	  if($variable>0)//en lista de desembarque
  	  {
  	    $endesembarque=1;  	    
  	    $vestado="r";//ready
  	    $msg="Guía lista para ser entregada";
  	  }  
  	  else
  	  {
  	  	 $endesembarque=0;
  	  	 $vestado="f";//faltan piezas
  	  	 //si no está desembarcada
  	  	 $msg="Guía en espera de completar, faltan piezas";
  	  }	  
  	}
  	$resultado["estado"]=$vestado;
  	$resultado["mensaje"]=$msg;
  	return ($resultado);
  }
  
  function despachar($con,$gui_id,$entregadapor)
  {
  	$fecha_server=date("Y-m-d H:i:s");
  	$sql="update guia set "
  		."gui_entregadapor='$entregadapor',gui_fechaentrega=to_date('$fecha_server','YYYY-MM-DD HH24:MI:SS') "
  		."where gui_id=$gui_id ";
  	//echo "";	
  	$rs=&$con->Execute($sql);
  	return ($gui_id);
  }
  
}
?>
