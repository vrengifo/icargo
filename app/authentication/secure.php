<?
$cfgIndexpage = '/index.php';
$bgImage = 'login.png';                 // Choose the background image
$bgRotate = true;                         // Rotate the background image from list
                                          // (This overrides the $bgImage setting)

/****** Lists ******/
// List of backgrounds to rotate through
$backgrounds[] = 'login.png';
$backgrounds[] = 'login.png';
$backgrounds[] = 'login.png';


/****** Database ******/
$useDatabase = true;                     // choose between using a database or data as input
require_once('includes/defines.php');


  //require_once('includes/adodb/adodb.inc.php');
  require_once('includes/functions.php');
  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  $conn->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

// https support
if (getenv("HTTPS") == 'on') {
	$cfgUrl = 'https://';
} else {
	$cfgUrl = 'http://';
}

// getting other login variables
if ($message) $messageOld = $message;
$message = false;


// include functions and variables
function admEmail() {
	// create administrators email link
	global $admEmail;
	return("<A HREF='mailto:$admEmail'>$admEmail</A>");
}

// logout first if requested
if ($logout || $HTTP_GET_VARS["logout"] || $HTTP_POST_VARS["logout"]) { // logout
	include('authentication/logout.php');
}
// loading login check
include('authentication/checklogin.php');
?>
