<?php 
  session_start();
  
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  require_once('includes/header.php');
  $username=$sUsername;
  
  buildmenu($username);
  buildsubmenu($id_aplicacion,$id_subaplicacion);
  
  
    include_once("class/c_kiloequivalenciaxcli.php");
    $oKilEqxCli=new c_kiloequivalenciaxcli($conn,$sUsername);
    $costoKilo=$oKilEqxCli->recuperarCosto($tDocOrigen,$seDocDestino,$seCliente);
    include_once("class/c_parametro.php");
    $oParametro=new c_parametro($conn);
    $oParametro->info();
    
    
?>
<br>
<SCRIPT LANGUAGE="JavaScript">
function redondear(cantidad, decimales) 
{
  var cantidad = parseFloat(cantidad);
  var decimales = parseFloat(decimales);
  decimales = (!decimales ? 2 : decimales);
  return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}


function valida() {
  	  define('tNro', 'string', 'Nro. de Documento',1,20,document);
  	  define('tNropiezas', 'num', 'Nro. de Piezas',1,3,document);
  	  define('tPeso', 'num', 'Nro. de Piezas (Kg)',1,5,document);
  	  define('tVolumen', 'num', 'Volumen (m3)',1,3,document);  	   	  
  	  define('tValorDeclarado', 'num', 'Valor Declarado ($)',1,3,document);
  	  define('seTipoCarga', 'string', 'Tipo de Carga',1,20,document);
  	  define('seDescripcion', 'string', 'Descripción',1,5000,document);
  	  define('seSobreDoc', 'string', 'Sobre con documentos',1,1,document);
  	  //define('tObservacion', 'string', 'Observación',1,5000,document);
  	  define('seCliente', 'string', 'Cliente',1,20,document);
  	  define('seDocDestino', 'string', 'Destino',1,3,document);
  	  define('tDestNombre', 'string', 'Destinatario',1,200,document);
  	  define('tDestCiruc', 'string', 'CI / RUC de Destinatario',1,13,document);
  	  define('tDestTelf', 'string', 'Teléfono Destinatario',1,100,document);
  	  define('tCosto', 'num', 'Costo',1,10,document);
  	  define('tSeguro', 'num', 'Seguro',1,8,document);
  	  //define('tVolumen', 'num', 'Nro. de Piezas',1,3,document);
  	  //define('tVolumen', 'num', 'Nro. de Piezas',1,3,document);
  	  define('tTotal', 'num', 'Total',1,12,document);
  	  
  	  //define('seCliente', 'string', 'Cliente',1,20,document);
  	  //define('tPrecio', 'num', 'Costo Kilo',1,15,document);
}

  	function vValidar()
  	{
  	  var res;
  	  res=validate();
  	  return(res);
  	}
  	
  	function vValidarB(forma,urldestino)
  	{
  	  var res;
  	  res=validate();
  	  if(res) 
  	  {
  	    cambiar_action(forma,urldestino);
  	    //forma.submit();
  	    res=true;
  	  }
  	  else
  	    res=false;
  	  return(res);    	  
  	}  	

</script>

<form action="documento.php" method="post" name="form1">
<input type="hidden" name="principal" value="<?=$principal?>">
<input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
<input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">

  <TABLE WIDTH="80%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	<TR>
	  <TD>
		<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
		  <tr>							
            <td nowrap>
			  <SPAN class="title" STYLE="cursor:default;"> 
                <img src="images/360/taskwrite.gif" border=0 align=absmiddle HSPACE=2>
				<font color="#FFFFFF">
				<?php
				  include_once("class/c_stock_tipo.php");
				  $oStoTip=new c_stock_tipo($conn);
				  $sql=$oStoTip->sqlSelect("1");
				  $rs=&$conn->Execute($sql);				  
				?>
                  <select name="seStockTipo" onChange="submit();">
				    <option value="">Escoja documento</option>
					<?php
					while(!$rs->EOF)
					{
					  $valor=$rs->fields[0];
					  $texto=$rs->fields[1];
					?>
					<option value="<?=$valor?>" <?php
					  if(isset($seStockTipo) && ($valor==$seStockTipo))
					    echo " selected";
					?>><?=$texto?></option>
					<?php
					  $rs->MoveNext();
					}
					?>
				  </select>
				  Nro:
				  <?php
				    include_once("class/c_terminal.php");
				    $oTer=new c_terminal($conn);
				    $oTer->info($sTerminal);
				    //$conn->debug=true;
				    include_once("class/c_stockxoficina.php");
				    //echo"<hr>sUsername: $sUsername<hr>";
				    $oStoxOfi=new c_stockxoficina($conn,$sUsername);
				    $nroDoc=$oStoxOfi->recuperarNroDoc($oTer->ofi_id,$seStockTipo);
				    $stoId=$oStoxOfi->recuperarStoId($oTer->ofi_id,$seStockTipo);
				    //$conn->debug=false;
				  ?>
				  <input type="hidden" name="tOfiId" value="<?=$oTer->ofi_id?>">
				  <input name="tNro" type="text" value="<?=$nroDoc?>" size="8" maxlength="4" <?php
				    if(!isset($seStockTipo)||(strlen($seStockTipo)==0))
					  echo "disabled";
				  ?>>
				  <input type="hidden" name="tStoId" value="<?=$stoId?>">
				<?php
				
				?>
				  <input type="submit" name="subAct" value="Actualizar Datos">  
				</font>
			  </SPAN>
			</td>
		  </tr>
		</table>
		<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
		  <TR>
		    <TD> <table width='100%' border=1 cellpadding=2 cellspacing=1 bgcolor='#CCCCCC'>
                <TR BGCOLOR="#CCCCCC"> 
                  <td width="45%" nowrap  bgcolor='#ffffff' valign="top"><div align="right"> 
                      <table width="100%" border="1">
                        <tr> 
                          <td width="26%" class="table_hd">Fecha de Recepci&oacute;n</td>
                          <td width="15%" class="table_hd">Piezas</td>
                          <td width="19%" class="table_hd">Peso (Kg)</td>
                          <td width="17%" class="table_hd">Volumen</td>
                          <td width="23%"class="table_hd">Valor Declarado</td>
                        </tr>
                        <tr> 
                          <td> 
                            <?php
							if (isset($fecha))
							    $vvfecha=$fecha;
							  else
							    $vvfecha=date("Y-m-d");
							if (isset($hora))
							    $vvhora=$hora;
							  else
							    $vvhora=date("H:i");	
							?>                            
                            <input name="fecha" type="text" size="10" maxlength="10" value="<?=$vvfecha?>">
                            <input name="hora" type="text" size="5" maxlength="5" value="<?=$vvhora?>"> </td>
                          <td> 
                            <input type="text" name="tNropiezas" size="2" maxlength="2" value="<?=$tNropiezas?>">
						  </td>
                          <td>
						    <input type="text" name="tPeso" size="4" maxlength="10" value="<?=$tPeso?>">
                          </td>
                          <td> 
                            <input type="text" name="tVolumen" size="4" maxlength="10" value="<?=$tVolumen?>">  
                          </td>
                          <td> 
                            <input type="text" name="tValorDeclarado" size="4" maxlength="10" value="<?=$tValorDeclarado?>">
                          </td>
                        </tr>
                        <tr> 
                          <td colspan="5" class="table_hd">Contenido Declarado</td>
                        </tr>
                        <tr> 
                          <td>Tipo de Carga</td>
                          <td colspan="4"> <select name="seTipoCarga">
                             <?php
							   include_once("class/c_tipo_carga.php");
							   $oTipCar=new c_tipo_carga($conn);
                               $sql=$oTipCar->sqlSelect();
							   $rs=&$conn->Execute($sql);
							   while (!$rs->EOF)
							   {
							     $valor=$rs->fields[0];
								 $texto=$rs->fields[1];								 
							 ?> 
							  <option value="<?=$valor?>" <?php
							    if($valor==$seTipoCarga)
							      echo " selected";
							  ?>><?=$texto?></option>
							 <?php
							     $rs->MoveNext();
							   }
							 ?>
                            </select>
                          </td>
                        </tr>
                        <tr> 
                          <td>Descripci&oacute;n (Contenido)</td>
                          <td colspan="4"><textarea name="seDescripcion" ><?=$seDescripcion?></textarea></td>
                        </tr>
                        <tr> 
                          <td>Sobre con Documentos</td>
                          <td colspan="4">
                            <select name="seSobreDoc">
                            <?php
                              include_once("class/c_verdadfalso.php");
                              $oVF=new c_verdadfalso($conn);
                              $sql=$oVF->sqlSelect();
                              $rs=&$conn->Execute($sql);
                              while(!$rs->EOF)
                              {
                              	$valor=$rs->fields[0];
                              	$texto=$rs->fields[1];
                            ?>
                              <option value="<?=$valor?>" <?php
                                if($valor==$seSobreDoc)
                                  echo " selected";
                              ?>><?=$texto?></option>
                            <?php
                                $rs->MoveNext();
                              }
                            ?>
                            </select>
                          </td>
                        </tr>
						<!--
                        <tr> 
                          <td>Sobrepeso</td>
                          <td colspan="4"><input name="sobrepeso" type="checkbox" value="1"></td>
                        </tr>
                        -->
                        <tr> 
                          <td class="table_hd">Observaciones</td>
                          <td colspan="4"><textarea name="tObservacion"><?=$tObservacion?></textarea></td>
                        </tr>
                        <tr> 
                          <td colspan="5"><p>Sin dinero ni joyas 
                              <!--<input type="checkbox" name="sindinjo" value="1" checked>-->
                              &nbsp;&nbsp; Sujeto a espacio 
                              <!--<input type="checkbox" name="suespacio" value="1" checked>-->
                            </p>
                            <p>Viaja a cuenta y riesgo del Remitente 
                              <!--<input type="checkbox" name="cuentarem" value="1" checked>-->
                            </p></td>
                        </tr>
                        <!--
						<tr> 
                          <td>&nbsp;</td>
                          <td colspan="4">&nbsp;</td>
                        </tr>
						-->
                      </table>
                    </div></td>
                  <td width="10%"></td>
                  <td width="45%" nowrap  bgcolor='#ffffff'> <table width="100%" border="1">
                      <tr> 
                        <td width="26%"  class="table_hd">Procedencia</td>
						<?php
						  $sql=<<<va
						  select e.est_codigo,e.est_nombre 
						  from estacion e, oficina o
						  where 
						  o.ofi_id='$oTer->ofi_id'
						  and e.est_codigo=o.est_codigo
va;
						  $rs=&$conn->Execute($sql);
						  $estCodigo=$rs->fields[0];
						  $estNombre=$rs->fields[1];
						  
						?>
                        <td>
                          <?=$estNombre?> 
                          <input type="hidden" name="tDocOrigen" value="<?=$estCodigo?>"> 
                        </td>
                      </tr>
                      <tr> 
                        <td>Cliente </td>
                        <td width="15%">
						  <select name="seCliente" onchange="submit();">
						  <?php
						    include_once("class/c_cliente.php");
						    $oCliente=new c_cliente($conn);
						    
						    //$docConvenio='0';
						    
						    if($seStockTipo!="")
						    {
						      $oStoTip->info($seStockTipo);
						      $docConvenio=$oStoTip->stotip_convenio;
						    }
						      
						    if($docConvenio=="0")
						      $sql=$oCliente->sqlSelect();
						    else 
						      $sql=$oCliente->sqlSelectConvenio();
						      
						    $rs=&$conn->Execute($sql);
						    while(!$rs->EOF)
						    {
						      $valor=$rs->fields[0];
						      $texto=$rs->fields[1];
						  ?>
						    <option value="<?=$valor?>" <?php
						      if($valor==$seCliente)
						        echo " selected ";
						    ?>>
						    <?=$texto?>
						    </option>
						  <?php
						      $rs->MoveNext();
						    }
						  ?>  
						  </select>
						  <input type="text" name="filCliente" value="" onkeyup="busca_combo(document.form1.seCliente,this.value);" >
						  <input type="button" name="add" value="Añadir Nuevo" onclick="fOpenWindow('vcli_add.php','AddCliente','400','400')">   
                        </td>
                      </tr>
                      <?php
                      if(isset($seCliente))  
                        $oCliente->info($seCliente);
                      ?>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%">
                          <?=$oCliente->cli_ciruc?>
                        </td>  
                      </tr>
					  <tr> 
                        <td>Remite </td>
                        <td width="15%">
                          <?=$oCliente->cli_nombre?>
                        </td>
                      </tr>
                      <tr> 
                        <td>Dirección</td>
                        <td width="15%">
                          <?=$oCliente->cli_direccion?>
                        </td>
                      </tr>
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%">
                          <?=$oCliente->cli_telefono?>
                        </td>
                      </tr>
                      <tr> 
                        <td class="table_hd">Destino</td>
                        <td>
                          <select name="seDocDestino" onchange="submit();">
                            <?php
                            include_once("class/c_estacion.php");
                            $oEst=new c_estacion($conn);
						    $sql=$oEst->sqlSelectDocumento($estCodigo);
							$rs=&$conn->Execute($sql);
							while (!$rs->EOF)
							{
							  $valor=$rs->fields[0];
							  $texto=$rs->fields[1];
						 	?>
                            <option value="<?=$valor?>" <?php
                            if($valor==$seDocDestino)
                              echo " selected";
                            ?>><?=$texto?></option>
                            <?php
						      $rs->MoveNext();	
							}
						 ?>
                          </select>
                        </td>
                      </tr>					  
                      <tr> 
                        <td  class="table_hd">Destinatario</td>
                        <td width="15%"><input name="tDestNombre" type="text" size="30" value="<?=$tDestNombre?>"></td>
                      </tr>
                      <tr> 
                        <td>RUC / CI</td>
                        <td width="15%">
						  <input name="tDestCiruc" type="text" onBlur="valida_ci(form1.tDestCiruc);" value="<?=$tDestCiruc?>" maxlength="13">
						</td>
                      </tr>
                      <!--
                      <tr> 
                        <td>Dirección</td>
                        <td width="15%"><input name="tDestDireccion" type="text" size="30" value="<?=$destinatario_direccion?>"></td>
                      </tr>
                      -->
                      <tr> 
                        <td>Teléfono</td>
                        <td width="15%"><input name="tDestTelf" type="text" size="30" value="<?=$tDestTelf?>"></td>
                      </tr>
                      <tr> 
                        <td colspan="2"><table width="100%" border="1">
                            <tr> 
                              <td class="table_hd">DETALLE</td>
                              <td class="table_hd">
							    <p>&nbsp;							    </p>
                              </td>
                            </tr>
<script language="JavaScript">

var costoKilo=<?=$costoKilo?>;
var iva=<?=$oParametro->iva?>;
var seguro=<?=$oParametro->seguro?>;
var sepPHP="<?=$oParametro->sepDec?>";

function calc(form) 
{
  var cad,sep,nosep;   
  
  sep=jsSeparador();
  if(sep==".")
    nosep=",";
  else
    nosep="."; 
    
  var aPeso,aVolumen;
  var aCosto,aSeguro,aSubtotal,aIva,aTotal;
  
  aPeso=jsSustituir(form.tPeso.value,nosep,sep);
  form.tPeso.value=jsSustituir(aPeso,sep,sepPHP);
  aVolumen=jsSustituir(form.tVolumen.value,nosep,sep);
  
  aAux=new String(aVolumen);

  if(aAux.len==0)
  {
    aVolumen="0";
    form.tVolumen.value=aVolumen;
  }
  aCosto=(eval(aPeso)+eval(aVolumen))*costoKilo/2;
  aSeguro=aCosto*seguro/100;
  aSubtotal=aCosto+aSeguro;
  aIva=aSubtotal*iva/100;
  aTotal=aSubtotal+aIva;
  //alert(aTotal);
  
  //cambiar a separador php
  var ndecimal=2;
  
  form.tCosto.value=redondear(jsSustituir(aCosto,sep,sepPHP), ndecimal);
  form.tSeguro.value=redondear(jsSustituir(aSeguro,sep,sepPHP), ndecimal);
  form.tSubtotal.value=redondear(jsSustituir(aSubtotal,sep,sepPHP), ndecimal);
  form.tIva.value=redondear(jsSustituir(aIva,sep,sepPHP), ndecimal);
  form.tTotal.value=redondear(jsSustituir(aTotal,sep,sepPHP), ndecimal);
  
  return(1);
}

</script>
                            <tr> 
                              <td>Carga / Corresp.</td>
                              <td><input type="text" name="tCosto" value="<?php 
							    if(!isset($tCosto))
								  echo "0";
								else
								  echo($tCosto);
								?>" readonly>
								</td>
                            </tr>
                            <!--
							<tr> 
                              <td>Entrega Domicilio</td>
                              <td><input type="text" name="entdom" value="<?php 
							    if(!isset($entdom))
								  echo "0";
								  else
								  echo $entdom;
								?>" onChange=""></td>
                            </tr>
                            <tr> 
                              <td>Transp. Adicional</td>
                              <td><input type="text" name="tadic" value="<?php 
							    if(!isset($tadic))
								  echo "0";
								  else
								  echo $tadic;
								?>" onChange=""></td>
                            </tr>
                            <tr> 
                              <td>Servicio Adicional</td>
                              <td><input type="text" name="sadic" value="<?php 
							    if(!isset($sadic))
								  echo "0";
								  else
								  echo $sadic;
								?>" onChange=""></td>
                            </tr>
                            -->
							<tr> 
                              <td>Seguro</td>
                              <td><input type="text" name="tSeguro" value="<?php 
							    if(!isset($tSeguro))
								  echo "0";
								  else
								  echo($tSeguro);
								?>" readonly></td>
                            </tr>
                            <!--
							<tr> 
                              <td>Seguros</td>
                              <td><input type="text" name="seguro" value="<?php 
							    if(!isset($seguro))
								  echo "0";
								  else
								  echo $seguro;
								?>" onChange=""></td>
                            </tr>
                            <tr> 
                              <td>Embalaje</td>
                              <td><input type="text" name="embalaje" value="<?php 
							    if(!isset($embalaje))
								  echo "0";
								  else
								  echo $embalaje;
								?>" onChange=""></td>
                            </tr>
                            -->
							<tr> 
                              <td>Subtotal</td>
                              <td><input type="text" name="tSubtotal" value="<?php 
							    if(!isset($tSubtotal))
								  echo "0";
								  else
								  echo($tSubtotal);
								?>" readonly>
                              </td>
                            </tr>
                            <!--
                            <tr> 
                              <td>Desc.</td>
                              <td><input type="text" name="descuento" value="<?php 
							    if(!isset($descuento))
								  echo "0";
								  else
								  echo $descuento;
								?>" onChange=""></td>
                            </tr>
                            -->
                            <tr> 
                              <td>IVA</td>
                              <td><input type="text" name="tIva" value="<?php 
							    if(!isset($tIva))
								  echo "0";
								else
								  echo($tIva);
								?>" readonly></td>
                            </tr>
                            <tr> 
                              <td class="table_hd">Valor Total $</td>
                              <td><input type="text" name="tTotal" value="<?php 
							    if(!isset($tTotal))
								  echo "0";
								  else
								  echo($tTotal);
								?>" readonly>
                                <input type="button" name="Button" value="Calcular" onClick="calc(document.form1);"></td>
                            </tr>
                          </table></td>
                      </tr>					  
                    </table></td>
                </tr>
				<tr>
				  <td colspan="3" align="center">
				    <input type="submit" name="procesar" value="Procesar" onClick="calc(document.form1);return vValidarB(document.form1,'documento1.php');" <?php if((!isset($seStockTipo))||(strlen($seStockTipo)==0)) echo" disabled"; ?>>
				  </td>
				</tr>                
              </table></TABLE>
				  </TABLE>

  <br>
<TABLE WIDTH="80%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
                      <TR> 
                        <TD> <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
                            <TR> 
                              <TD> </table></table>  
  <br>
  <br>
	<!--
    <input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>" >
    <input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>" >
    <input type="hidden" name="principal" value="<?=$principal?>" >
    -->
    <input type="hidden" name="campo_base" value="clp2|clp4|clp3" >
    <input type="hidden" name="campo_extra" value="id_aplicacion|id_subaplicacion|idp|principal" >
    <input type="hidden" name="cextra" value="id_aplicacion|id_subaplicacion|principal|idp">
    <br>
  
</form>	
<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>
