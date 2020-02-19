<html><head>
<link rel="stylesheet" type="text/css" href="includes/style/bluish.css">
<script type="text/javascript" language="JavaScript"><!--
function toggle(object) {
  var Event = window.event || arguments.callee.caller.arguments[0];

  if (document.getElementById) {
    if (document.getElementById(object).style.visibility == 'visible')
      document.getElementById(object).style.visibility = 'hidden';
    else {
      document.getElementById(object).style.left = Event.x+15;
      document.getElementById(object).style.top  = Event.y-20;
      document.getElementById(object).style.visibility = 'visible';
      }
  }

  else if (document.layers && document.layers[object] != null) {
    if (document.layers[object].visibility == 'visible' ||
     document.layers[object].visibility == 'show' )
      document.layers[object].visibility = 'hidden';
    else {
      document.layers[object].left = Event.x+15;
      document.layers[object].top  = Event.y-20;
      document.layers[object].visibility = 'visible';
      }
  }

  else if (document.all) {
    if (document.all[object].style.visibility == 'visible')
      document.all[object].style.visibility = 'hidden';
    else {
      document.all[object].style.pixelLeft = document.body.scrollLeft + Event.x + 1;
      document.all[object].style.pixelTop = document.body.scrollTop + Event.y + 1;
      document.all[object].style.visibility = 'visible';
      }
  }

  return false;
}
//--></script>
        <script language="JavaScript" src="js/overlib.js"></script>
        <script language="JavaScript" src="js/donothing.js"></script>
        <script language="JavaScript" src="js/confirm.js"></script>
        
		<script language="JavaScript" src="js/date-picker.js"></script>
        <script language="JavaScript">
        <!--
                // handleEnter allows forms to 'tab' to the next field when the enter key is pushed, instead of submitting
	function handleEnter (field, event) {
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if (keyCode == 13) {
			var i;
			for (i = 0; i < field.form.elements.length; i++)
				if (field == field.form.elements[i])
					break;
			i = (i + 1) % field.form.elements.length;
			field.form.elements[i].focus();
			return false;
		} 
		else
		return true;
	}                //-->
        </script>

        <script language="JavaScript">
        <!--
                                function highlightField (field,select) {
                var i;
                for (i = 0; i < field.form.elements.length; i++)
                        if (field == field.form.elements[i])
                                break;
//                if (NS4) {
//                        field.form.elements[i].bgColor = '#E7EEF5';
//                } else {
                        field.form.elements[i].style.backgroundColor = '#E7EEF5';
//                }
                if (select&&field.form.elements[i].type=="text") field.form.elements[i].select();
        }


        function normalField (field) {
                var i;
                for (i = 0; i < field.form.elements.length; i++)
                        if (field == field.form.elements[i])
                                break;
//                if (NS4) {
//                        field.form.elements[i].bgColor = '#FFFFFF';
//                } else {
                        field.form.elements[i].style.backgroundColor = '#FFFFFF';
//                }

        }

        function highlightFieldFirst () {
                var i;
				var oele;
                i = 0;
				if (document.forms.length == 0) return;

					// get a shortcut to the elements array
					oele = document.forms[0].elements;

					// find the first non hidden field
					for (i=0; i < oele.length; i++) {
                        if (oele[i].type!="hidden")  break;
					}
					// if all the fields were hidden return
	  				if (i == oele.length) return;

                    if (oele[i].type=="text") oele[i].select();
                    oele[i].focus();
                    if (oele[i].type=="text"||oele[i].type=="select"||oele[i].type=="textarea") highlightField(oele[i], '1');
        }                //-->
        </script>
        <script language="Javascript1.1">
            function imgchange(imgName,imgSrc) {
                if (document.images) {
                    document.images[imgName].src = imgSrc;
                }
            }
            function imgchange2(imgName, imgSrc) {
                if (document.images) {
                    document[imgName].src = eval(imgSrc + ".src");
                }
            }
        </script>
		
<SCRIPT LANGUAGE="Javascript">
function cambiar_action(forma,url_destino)
{	
    forma.action=url_destino;	
}       
</script>		
		
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="js/validation.js"></script>
<SCRIPT LANGUAGE="JavaScript">var formissent = 0;function onlyOneSubmit() {if (!formissent) {formissent = 1; return true; } else { return false; }}</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
function hideRow(rowid) { document.getElementById(rowid).style.display = "none"; }
var iFrameIndex = new Number(0);
//var iFrameId = new Array("notifyZero","notifyOne","notifyTwo","notifyThree","notifyFour","notifyFive","notifySix");
//var shimHTML = new String('<IMG SRC="/s360v2/spacer.gif" WIDTH=10 HEIGHT=10>');
function setNotification()
{
var iFrameId = new String("notify");
var args = setNotification.arguments;
if (args.length < 2) return false;
iFrameId += iFrameIndex;
iFrameIndex < 7 ? iFrameIndex++ : iFrameIndex=0;
iFrameURL = new String("s360.exe?");
for (i=0; i<args.length; i+=2)
{
	iFrameURL += args[i] + "=" + args[i+1] + "&";
}
//alert(iFrameURL);
//alert(iFrameId);
eval("var cf = window.frames."+iFrameId);
cf.location.replace(iFrameURL);
}
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">function helpWindow() { window.open('s360.exe?page=PersonalTopPageHelp','Help','width=680,height=500,resizable=1,scrollbars=1,toolbar=0,menubar=0,location=0');}</SCRIPT>
<!--
pv
-->
<SCRIPT LANGUAGE="JavaScript">
function fOpenWindow(vurl,vtitle,vwidth,vheight) 
{
 var cad;
 cad='width=' + vwidth + ',height=' + vheight + ',resizable=1,scrollbars=1,toolbar=0,menubar=0,location=0';
 //alert (cad);
 window.open(vurl,vtitle,cad);
}
</SCRIPT>
<!--
pv
-->
<SCRIPT LANGUAGE="Javascript">
function over( style ) { style.borderLeftColor="#FFFFFF"; style.borderTopColor="#FFFFFF"; style.borderRightColor="#333333"; style.borderBottomColor="#333333"; }
function mout( style ) { style.borderColor="#90A8C8" }
function tout( style ) { style.borderColor="#94ACC8" }
function overborder( style, clr ) {style.borderColor=clr }
function moutborder( style, clr ) {style.borderColor=clr }
function showstatus( lbl ) { status = lbl; return true; }
function hidestatus() {status = ""; }
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
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
<SCRIPT LANGUAGE="JavaScript">
var chk = 1
function checkAll(fmnm,cbnm)
{
var cb = document.forms[fmnm].elements[cbnm];
	if (cb && cb.value) 
	{
		cb.checked = chk ? 1 : 0;
		highlightRow(cb.value,cb.checked);
	}
	else if (cb)
	{
		for (i=0;i<cb.length;i++)
		{
			cb[i].checked = chk ? 1 : 0;
			highlightRow(cb[i].value,cb[i].checked);
		}
	}
	chk = (chk) ? 0 : 1;
}
function highlightRow(rid,chk)
{
var tr = document.getElementById(eval("'tr"+rid+"'"));
	if (chk && tr)
	{
		tr.style.backgroundColor = "#90A8C8";
	}
	else if (tr)
	{
		tr.style.backgroundColor = "";
	
	}
}

</SCRIPT>
<TITLE>SIIMAI</TITLE>
</head>
<body onLoad="valida();highlightFieldFirst()" class="" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table border=0 width="100%" cellspacing="0" cellpadding="0">
<tr>
<td valign=top> 
<!-- startprint --><TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=1 BGCOLOR="#075685">
			  	<TR>
    				<TD ROWSPAN=2 BGCOLOR="#FFFFFF" WIDTH="10%" ALIGN=center VALIGN=middle><img src="images/logo.gif" border=0></TD>
    				<TD BGCOLOR="#075685"><TABLE BORDER=0 WIDTH="100%" CELLPADDING=1 CELLSPACING=0>
		        <TR>
        			<TD></TD>
			        <TD><SPAN CLASS="LoginName">Víctor Hugo Rengifo Peñafiel&nbsp;&nbsp;&nbsp;&nbsp;last refresh at:&nbsp;2003-12-16 12:05:50</SPAN></TD>
	          		<TD><select name="Start" onChange="i=this.selectedIndex;v=this.options[i].value;if(v)location.href=v;" CLASS="topSelector">
			              <option value="">Go to ... 
        			      <OPTION VALUE="">----</OPTION><option value="ingenieria.php?id_aplicacion=3">Engineering</OPTION>
<option value="admin.php?id_aplicacion=81">Importacion</OPTION>
<option value="admin.php?id_aplicacion=5">Inventary</OPTION>
<option value="admin.php?id_aplicacion=101">Manuales y Documentos</OPTION>
<option value="admin.php?id_aplicacion=42">Modules</OPTION>
<option value="production_control.php?id_aplicacion=4">Production Control</OPTION>
<option value="admin.php?id_aplicacion=6">Purchase</OPTION>
<option value="admin.php?id_aplicacion=2">Quality Control</OPTION>
<option value="admin.php?id_aplicacion=41">Users</OPTION>
<option value="alerts.php?id_aplicacion=7">Alerts</OPTION>
<option value="ingreso_aprobar.php?id_aplicacion=61">Aprobar (JM)</OPTION>
</select></td>	
	        	 	<form name="logoutform" method=post action="logout.php">
	            		<TD VALIGN=middle>
        	      		<SPAN CLASS="ButtonTop" onclick="document.forms['logoutform'].submit()" onmouseover="overborder(this.style,'#90A8C8')" onmouseout="moutborder(this.style,'#075685')">Log 
	              		out</SPAN> 
						&nbsp; &nbsp; <SPAN CLASS="ButtonTop" onclick="fOpenWindow('change_password.php','Change_Password','370','160')" onmouseover="overborder(this.style, '#90A8C8')" onmouseout="moutborder(this.style, '#075685')">Change Password</SPAN>
						&nbsp; &nbsp; <SPAN CLASS="ButtonTop" onclick="helpWindow()" onmouseover="overborder(this.style, '#90A8C8')" onmouseout="moutborder(this.style, '#075685')"></SPAN>
						</TD>
	          		</form>
        			</TR>
      			</TABLE></TD>
			  </TR>
			  <TR>
	    		<TD BGCOLOR="#90A8C8" HEIGHT="30" VALIGN=top><nobr>
			  			<SPAN class=menu onClick="location.href='ingenieria.php?id_aplicacion=3'" onMouseOver="over(this.style);showstatus('Engineering');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/group.gif" border=0 align=absmiddle >&nbsp;Engineering
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='admin.php?id_aplicacion=81'" onMouseOver="over(this.style);showstatus('Importacion');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/department.gif" border=0 align=absmiddle >&nbsp;Importacion
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='admin.php?id_aplicacion=5'" onMouseOver="over(this.style);showstatus('Inventary');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/reuse.gif" border=0 align=absmiddle >&nbsp;Inventary
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='admin.php?id_aplicacion=101'" onMouseOver="over(this.style);showstatus('Manuales y Documentos');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/report.gif" border=0 align=absmiddle >&nbsp;Manuales y Documentos
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='admin.php?id_aplicacion=42'" onMouseOver="over(this.style);showstatus('Modules');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/s360.gif" border=0 align=absmiddle >&nbsp;Modules
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='production_control.php?id_aplicacion=4'" onMouseOver="over(this.style);showstatus('Production Control');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/refresh.gif" border=0 align=absmiddle >&nbsp;Production Control
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='admin.php?id_aplicacion=6'" onMouseOver="over(this.style);showstatus('Purchase');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/project_s.gif" border=0 align=absmiddle >&nbsp;Purchase
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='admin.php?id_aplicacion=2'" onMouseOver="over(this.style);showstatus('Quality Control');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/workflow.gif" border=0 align=absmiddle >&nbsp;Quality Control
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='admin.php?id_aplicacion=41'" onMouseOver="over(this.style);showstatus('Users');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/s360.gif" border=0 align=absmiddle >&nbsp;Users
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='alerts.php?id_aplicacion=7'" onMouseOver="over(this.style);showstatus('Alerts');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/calendar_icon.gif" border=0 align=absmiddle >&nbsp;Alerts
						</SPAN>
					</nobr><nobr>
			  			<SPAN class=menu onClick="location.href='ingreso_aprobar.php?id_aplicacion=61'" onMouseOver="over(this.style);showstatus('Aprobar (JM)');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/360/taskcomplete.gif" border=0 align=absmiddle >&nbsp;Aprobar (JM)
						</SPAN>
					</nobr>
				</TD>
			  </TR>
			</TABLE>
	 	   </TD>
		  </TR>
		</TABLE><TABLE CELLPADDING=0 CELLSPACING=0 >
					  	<TR>
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="tab" CELLPADDING=3 CELLSPACING=0>
        				<TR>
	          				<TD nowrap><A HREF="aircraft_hc.php?id_aplicacion=4&id_subaplicacion=341" CLASS="tabtxt" onmouseover="status='Update (Hr & Cyc)';return true;" onmouseout="status='';">
					<IMG SRC="images/360/change.gif" BORDER=0 HSPACE=2 ALIGN="absmiddle">Update (Hr & Cyc)     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD>
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="tab" CELLPADDING=3 CELLSPACING=0>
        				<TR>
	          				<TD nowrap><A HREF="pc_assembly.php?id_aplicacion=4&id_subaplicacion=66" CLASS="tabtxt" onmouseover="status='Assembly (Move)';return true;" onmouseout="status='';">
					<IMG SRC="images/360/mailreply.gif" BORDER=0 HSPACE=2 ALIGN="absmiddle">Assembly (Move)     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD>
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="tab" CELLPADDING=3 CELLSPACING=0>
        				<TR>
	          				<TD nowrap><A HREF="pc_subassembly.php?id_aplicacion=4&id_subaplicacion=67" CLASS="tabtxt" onmouseover="status='Subassembly (Change)';return true;" onmouseout="status='';">
					<IMG SRC="images/360/mailforward.gif" BORDER=0 HSPACE=2 ALIGN="absmiddle">Subassembly (Change)     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD>
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="tabselected" CELLPADDING=3 CELLSPACING=0>
        				<TR>
	          				<TD nowrap><A HREF="pc_component.php?id_aplicacion=4&id_subaplicacion=68" CLASS="tabtxtselected" onmouseover="status='Components';return true;" onmouseout="status='';">
					<IMG SRC="images/360/home.gif" BORDER=0 HSPACE=2 ALIGN="absmiddle">Components     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD>
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="tab" CELLPADDING=3 CELLSPACING=0>
        				<TR>

	          				<TD nowrap><A HREF="pc_task.php?id_aplicacion=4&id_subaplicacion=101" CLASS="tabtxt" onmouseover="status='Tasks';return true;" onmouseout="status='';">
					<IMG SRC="images/360/link.gif" BORDER=0 HSPACE=2 ALIGN="absmiddle">Tasks     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD>
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="tab" CELLPADDING=3 CELLSPACING=0>
        				<TR>
	          				<TD nowrap><A HREF="material_request.php?id_aplicacion=4&id_subaplicacion=181" CLASS="tabtxt" onmouseover="status='Material Request';return true;" onmouseout="status='';">
					<IMG SRC="images/360/mailreplyall.gif" BORDER=0 HSPACE=2 ALIGN="absmiddle">Material Request     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD><TD VALIGN="bottom" WIDTH="94%">
				<TABLE CLASS="empty" CELLPADDING=3 CELLSPACING=0 WIDTH="100%">
					<TR>
						<TD nowrap><IMG SRC="images/360/spacer.gif" WIDTH="12" HEIGHT="20"><SPAN CLASS="tabtxt">&nbsp;</SPAN>
					</TD></tr></table><td>  </TR>
			  </TABLE><SCRIPT LANGUAGE="JavaScript">function printWindow() { window.open("printpage.php?page=http://localhost/simai/cons_hcyc.php","Print","width=580,height=400,resizable=1,scrollbars=1,toolbar=0,menubar=0,location=0");}</SCRIPT><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="3" CLASS="workarea">
				  <TR>
				    <TD>
			<!--                        
                        <a href="#" onclick="printWindow()">Print Preview</a><a href="printpage.php">otro Print Preview</a>
                        -->
			<a href=production_control.php?id_aplicacion=4>Production Control</a>&nbsp;&nbsp;--->&nbsp;&nbsp;<a href=pc_component.php?id_aplicacion=4&id_subaplicacion=68>Components</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="printWindow()">Print Preview</a>
						<HR NOSHADE SIZE=1 COLOR=#000000 CLASS=HRule>		
				       <table width=100%>
    				    <tr> 
				          <td >
<SCRIPT LANGUAGE="JavaScript">
function valida() {

}
</script>

	
	<br>
	<form action="cons_hcyc.php" method="post" name="form1">
		<input type="hidden" name="principal" value="cons_hcyc.php">
	<input type="hidden" name="id_aplicacion" value="4">
	<input type="hidden" name="id_subaplicacion" value="68">		
	<table>
	<tr>
	  <td>	
	    <table>
	      <tr>	    
            <td>Aircraft: </td>
		    <td>			  
			  <select name="aircraft" onChange="submit();">
			    <option value="-" 
			   selected			    >All</option>
              				<option value="83" 
						> 
                        HC-BJI                        </option>
                        				<option value="81" 
						> 
                        HC-BYO                        </option>
                        				<option value="82" 
						> 
                        HC-CAO                        </option>
                        				<option value="85" 
						> 
                        HC-CDA                        </option>
                        				<option value="86" 
						> 
                        HC-CDG                        </option>
                        				<option value="84" 
						> 
                        HC-CDN                        </option>
                        				<option value="87" 
						> 
                        HC-CDT                        </option>
                        				<option value="88" 
						> 
                        HC-CDW                        </option>
                                              </select>
			</td>
	      </tr>
	      <tr>	    
            <td>Assembly: </td>
		    <td>			  
			  <select name="assembly">
			    <option value="-"  selected>All</option>
                                    </select>
			</td>
	      </tr>
	      <tr>	    
            <td>Date: </td>
		     <td>
			  <input type="text" name="fecha" value="2003-10-01" >
              <a href="javascript:show_calendar('form1.fecha');" 
					  onmouseover="window.status='Date Picker';return true;" 
					   onmouseout="window.status='';return true;"> <img src="images/360/big_calendar.gif" width=24 height=24 border=0> 
              </a>
			 </td>
	      </tr>	  	  	
	    </table>
	    </td>
	    <td align="center"><input type="submit" name="procesar" value="Procesar"></td>
	  </tr>
	  
	</table>

	<br><br>	

<br>
			<TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="images/360/yearview.gif" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								Consulta Horas y Ciclos de Avión&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD><TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>

<TR BGCOLOR="#CCCCCC"><td nowrap class="table_hd">Item</td><td nowrap class='table_hd'>Air ID</td><td nowrap class='table_hd'>Aircraft</td><td nowrap class='table_hd'>Aircraft SN</td><td nowrap class='table_hd'>Hours</td><td nowrap class='table_hd'>Cycles</td><td nowrap class='table_hd'>Date</td>

<TR valign=top bgcolor='#ffffff'>
	<TD valign=top nowrap align=right>1&nbsp;</TD>
	<TD valign=top nowrap align=right>87&nbsp;</TD>
	<TD valign=top nowrap>HC-CDT&nbsp;</TD>
	<TD valign=top nowrap>11222&nbsp;</TD>
	<TD valign=top nowrap align=right>36407,97&nbsp;</TD>
	<TD valign=top nowrap align=right>45149&nbsp;</TD>
	<TD valign=top nowrap>2003-10-01&nbsp;</TD>
</TR>

<TR valign=top bgcolor='#ffffff'>
	<TD valign=top nowrap align=right>2&nbsp;</TD>
	<TD valign=top nowrap align=right>88&nbsp;</TD>
	<TD valign=top nowrap>HC-CDW&nbsp;</TD>
	<TD valign=top nowrap>11224&nbsp;</TD>
	<TD valign=top nowrap align=right>35379,16&nbsp;</TD>
	<TD valign=top nowrap align=right>43066&nbsp;</TD>
	<TD valign=top nowrap>2003-10-01&nbsp;</TD>
</TR>

</TABLE>

		<input type="hidden" name="cextra" value="id_aplicacion|id_subaplicacion|principal">
	</form>	
	</td>
        				</tr>
				      </table>
      
			    	</TD>
				  </TR>
			  </TABLE></body>
</html>
