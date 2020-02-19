CJ Printer Friendly V2.0  -  James Crooke © 2002 - webmaster@cj-design.com
CJ Web Design http://www.cj-design.com ===============================
===============================================================

*********************************************************************************
* Please, Please, Please help keep Cj-Design.com alive by visiting our Donate page (if you havent already)	*
* Donating to CJ allows the copyright to be removed!										*
*																		*
* http://www.cj-design.com/?id=donate  or email webmaster@cj-design.com						*
*																		*
*********************************************************************************


Important Notice
~~~~~~~~~~

Version 2.0 requires the $HTTP_REFERER variable to make sure that other websites cannot use the script
to view their pages in Printer Friendly mode.  If you find that your webserver does not support this then
download Version 1.0 from here:  http://www.cj-design.com/dl/dl.php?url=4



Files Just Downloaded
~~~~~~~~~~~~~

The contents of "CJ PrinterFriendly.zip" ........

1.	printpage.php
2.	css.php
3.	config.php
4.	noaccess.php
5.	Readme.txt
6.	Copying.txt (GPL)
7.	and just incase you forgot where it came from.... an Internet Shortcut  :D

Files Required
~~~~~~~~

1.	printpage.php  (comes within zip file)
2.	css.php (comes within zip file)
3.	config.php (comes within zip file)
3.	noaccess.php (comes within zip file)
4.	a printable page linking to "printpage.php"
	
	» note:  the page must link to printpage.php wherever it is located on your server


Installation Help
~~~~~~~~~~

1.  	Variables to edit in "printpage.php":
	
	$title = "Printer Friendly Version";			« enter the title to appear on the page title
	$domain = "cj-design.com";				« change to your domain name, be specific but no "www"
	$startingpoint = "<!--start-->";			« the hidden comment tag to start printing from
	$endingpoint = "<!--end-->";				« the hidden comment tag to end printing at
	$nonreferer = "Link page to the script";		« error message when script is accessed
	$nosent = "No Referer Sent";				« error link when script is accessed
	$website = "YourSite Name";				« enter your websites name

	$font="verdana";						« enter page font, eg.. verdana, arial, tahoma
	$fontcolor="#000000";					« enter colour of page font as hex
	$fontsize="10pt";						« enter font size on page
	$hrcolor="#000000";					« enter horizontal line colour on page

2.  	Within your HTML page enter the comment tags <!--start--> where you want the printer friendly page to start printing from
	and <!--end--> where you want it to end, these must be included!!!

3.	Link your HTML page to "printpage.php" by using a hyperlink for example:
	<a href="print/printpage.php"><b>Print This Page</b></a>

4.	Feel free to edit "noaccess.php" to say anything you want (i.e  This website is a LEECH!) - its up to you!

5.  	Upload the files: "printpage.php", "css.php", "config.php", "noaccess.php" and a page with the comment tags and a link to "printpage.php" within it.

6.	Go to the page and test it works, you should see the text from the previous page layed out in a printer friendly fashion (i.e no images)


Thats All!
~~~~~~

Thank you for downloading this script, I hope you like it - feedback would be appreciated
(nothing to harsh please) at the CJ Forums! - http://www.cj-design.com/?id=forum

If you need any help with this script, try going to the FAQ's page on the website:
http://www.cj-design.com/index.php?id=downloads&faq=4

James - CJ Website Designer

==========================================================================