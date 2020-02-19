<? include('includes/main.php'); ?>
<? include('adodb/tohtml.inc.php'); ?>
<? include('includes/header.php'); ?>
<?php
        $recordSet = &$conn->Execute('select * from aplicaciones');
        if (!$recordSet||$recordSet->EOF) die(texterror('No Records found.'));
        echo '<a href="test.php"'.tooltip('TEXTO PARA EL TOLLTIP').'>ToolTIPS</a><br>'."\n";

/*        for ($i=1; $recordSet->fields[0]-(30*($i-1))>=0; $i++) {
           echo '<a href="labels.php?customer=1&companyid='.$active_company.'" target="_new"'.tooltip('This link generates a page of mailing labels for your Main Location customer addresses.  This file may be displayed as a PDF document in a new window.  It requires Adobe Acrobat Reader or another PDF viewer to display itself correctly.').'>Mailing Labels (Customer Locations: Page '.$i.')</a><br>'."\n";
           echo '<a href="labels.php?customershipto=1&companyid='.$active_company.'" target="_new"'.tooltip('This link generates a page of mailing labels for your Shipping Location customer addresses.  This file may be displayed as a PDF document in a new window.  It requires Adobe Acrobat Reader or another PDF viewer to display itself correctly.').'>Mailing Labels (Ship To Locations: Page '.$i.')</a><br>'."\n";
        };*/
		rs2html($recordSet,"border=3 bgcolor='#effee'");
?>
		<form name="form1" action="" method="post">
		<input type="text" name="customerid" size="30" onchange="validateint(this)"  onFocus="highlightField(this,1)" onBlur="normalField(this)">
		</form>
<? include('includes/footer.php'); ?>
