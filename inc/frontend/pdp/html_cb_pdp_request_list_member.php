<?php

global $PGCwp; // database handle for accessing wordpress db
global $PGCi;  // database handle for PDP external db
global $wpdb;
//require_once('pgc_connect.php');
?>
<?php
error_reporting(E_ALL);
//ini_set('display_errors', 'On');
if (!isset($_SESSION)) {
  session_start();
}
//exit(var_dump($_SESSION));
//require_once('pgc_check_login.php'); 
?>
<?php
$MM_restrictGoTo = "../Index.html";
if ( !(isset($_SESSION['MM_Username']) ))  {   
    header("Location: ". $MM_restrictGoTo); 
  exit;
 }
$_SESSION['last_rm_query'] = "http://" .  $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']; 
$session_pilotname = $_SESSION['MM_PilotName'];
$session_email = $_SESSION['MM_Username']; 
$BaseColor = "#35415B";
$RedColor = "#990000";
$LtBlueColor = "#35415B";
$LtBlueColor = "#1A6866";
$LtGreenColor = "#35415B";
$LtGreenColor = "#575575";
/***************/
/* ========= Set CFIG Vacation Dates ===============================*/
//mysql_select_db($database_PGC, $PGC);
$runSQL = "Truncate pgc_cfig_dates"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

//mysql_select_db($database_PGC, $PGC);
$runSQL = "INSERT INTO pgc_cfig_dates(cfig_name, duty_date) SELECT A.Name, B.date FROM pgc_instructors A, pgc_field_duty B Where A.CFIG = 'Y'  AND DATEDIFF(B.date,CURDATE()) < 15"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* ---  A. CFIG Vacation Dates - Update Short Duration Vacations first ----*/

//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_cfig_dates SET cfig_vacation = 'N', rec_processed = 'N', source_key = null"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_cfig_dates SET duty_day = Date_format(duty_date,'%W')"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));


$runSQL = "UPDATE pgc_cfig_dates A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.cfig_name = B.cfig_name and (A.duty_date >= B.vac_start AND A.duty_date <= B.vac_end) AND (LOCATE(Date_format(A.duty_date,'%W'),B.alldays) > 0) AND (B.vdays <= 7) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_dates A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.cfig_name = B.cfig_name and (A.duty_date >= B.vac_start AND A.duty_date <= B.vac_end) AND (B.vdays <= 7) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));


$runSQL = "UPDATE pgc_cfig_dates A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.cfig_name = B.cfig_name and (A.duty_date >= B.vac_start AND A.duty_date <= B.vac_end) AND (LOCATE(Date_format(A.duty_date,'%W'),B.alldays) > 0) AND (B.vdays <= 31) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_dates A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.cfig_name = B.cfig_name and (A.duty_date >= B.vac_start AND A.duty_date <= B.vac_end) AND (B.vdays <= 31) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_dates A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.cfig_name = B.cfig_name and (A.duty_date >= B.vac_start AND A.duty_date <= B.vac_end) AND (LOCATE(Date_format(A.duty_date,'%W'),B.alldays) > 0) AND (B.vdays > 31) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_dates A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.cfig_name = B.cfig_name and (A.duty_date >= B.vac_start AND A.duty_date <= B.vac_end) AND (B.vdays > 31) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* =========  Set Request Vacation Flages ===============================*/
//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_request SET cfig2_vacation = 'N', cfig_vacation = 'N', cfig_vacation2 = 'N', source_key = null, rec_processed = 'N', rec_processed2 = 'N', rec_processed3 = 'N'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_request SET request_day = Date_format(request_date,'%W')"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* ---  A. Update Short Duration Vacations first ----*/
$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 7) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 7) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 31) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 31) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* --- B. Then Longer Duration Vacations  ----*/

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays > 31 ) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND ( B.vdays > 31) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* ========== */

if (1 == 2) {

/* ---  A. Update Short Duration Vacations first ----*/
$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 7) AND A.rec_processed2  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed2 = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 7) AND A.rec_processed2 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 31) AND A.rec_processed2  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 31) AND A.rec_processed2 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* --- B. Then Longer Duration Vacations  ----*/

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays > 31) AND A.rec_processed2  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed2 = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays > 31) AND A.rec_processed2 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* ======= */

/* ---  A. Update Short Duration Vacations first ----*/
$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 7) AND A.rec_processed3  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed3 = 'Y', A.source_key = B.vac_key
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 7) AND A.rec_processed3 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 31) AND A.rec_processed3  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed3 = 'Y', A.source_key = B.vac_key
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 31) AND A.rec_processed3 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* --- B. Then Longer Duration Vacations  ----*/

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays > 31) AND A.rec_processed3  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed3 = 'Y', A.source_key = B.vac_key
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays > 31) AND A.rec_processed3 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));
}

/* --- Fix Pilot Names   ----*/

$runSQL = "UPDATE pgc_request A, pgc_members B SET A.member_name = B.name 
WHERE A.member_id = B.user_id 
AND (LENGTH(TRIM(A.member_name)) = 0 OR A.member_name IS NULL)
AND request_date >= curdate() ";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));


/* =========  End Set Vacation Flages ===============================*/


/*****Delete the following*********/

if (1 == 2) {

//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_request SET cfig2_vacation = 'N', cfig_vacation = 'N', cfig_vacation2 = 'N' "; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));
/*
$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end)"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end)"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end)"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi)); */

/* Order by Length of Vacation  */
$runSQL = "TRUNCATE TABLE pgc_cfig_vacation_copy"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "INSERT into pgc_cfig_vacation_copy SELECT * FROM pgc_cfig_vacation ORDER by vdays asc"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));
/*******************/

//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_request SET cfig_vacation = 'N'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request SET cfig2_vacation = 'N'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = null"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = concat(alldays,' Saturday') Where saturday = 'OFF'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = concat(alldays,' Sunday') Where sunday = 'OFF'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = concat(alldays,' Monday') Where monday = 'OFF'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = concat(alldays,' Tuesday') Where tuesday = 'OFF'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = concat(alldays,' Wednesday') Where wednesday = 'OFF'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = concat(alldays,' Thursday') Where thursday = 'OFF'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = concat(alldays,' Friday') Where friday = 'OFF'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));



$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));
/* End of 1 == 2 Delete */
}

?>

<?php
$maxRows_Requests = 20;
$pageNum_Requests = 0;
if (isset($_GET['pageNum_Requests'])) {
  $pageNum_Requests = $_GET['pageNum_Requests'];
}
$startRow_Requests = $pageNum_Requests * $maxRows_Requests;

//mysql_select_db($database_PGC, $PGC);
$query_Requests = "SELECT request_key, entry_date, member_id, member_name,member_weight, Date_format(request_date,'%W, %M %e') as mydate,  request_time, request_type, request_cfig, request_cfig2, cfig_vacation, cfig_vacation2, cfig_weight, request_notes, accept_cfig, cfig2_vacation, accept_date, accept_notes, record_deleted, sched_assist FROM pgc_request WHERE request_date >= curdate()  AND record_deleted <> 'Y' ORDER BY request_date ASC, request_key ASC  ";
$query_limit_Requests = sprintf("%s LIMIT %d, %d", $query_Requests, $startRow_Requests, $maxRows_Requests);
$Requests = mysqli_query($PGCi, $query_limit_Requests )  or die(mysqli_error($PGCi));
//$row_Requests =mysqli_fetch_assoc($Requests);
//var_dump($row_Requests);
//die();
// var_dump($query_limit_Requests);
if (isset($_GET['totalRows_Requests'])) {
  $totalRows_Requests = $_GET['totalRows_Requests'];
} else {
  $all_Requests = mysqli_query($PGCi, $query_Requests ) ;
  $totalRows_Requests = mysqli_num_rows($all_Requests);
}
$totalPages_Requests = ceil($totalRows_Requests/$maxRows_Requests)-1;

$queryString_Requests = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Requests") == false && 
        stristr($param, "totalRows_Requests") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Requests = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Requests = sprintf("&totalRows_Requests=%d%s", $totalRows_Requests, $queryString_Requests);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Member View - List Requests</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #CCCCCC;
}
body {
	background-color: #283664;
	background-image: url(../images/Buttons/PGC%20copy.png);
}
/* div.warning used on this page to make "8AM" warning red */
div.warning	{
	color: yellow;
}
.style1 {
	font-size: 18px;
	font-weight: bold;
}
.style2 {
	font-size: 14px;
	font-weight: bold;
}
.style16 {color: #CCCCCC; }
a:link {
	color: #CCCCCC;
}
a:visited {
	color: #CCCCCC;
}
.style17 {
	color: #CCCCCC;
	font-size: 14px;
	font-weight: bold;
	font-style: italic;
}
.style25 {
	font-weight: bold;
	color: #D0D8E6;
}
.style33 {
	color: #DD0000;
	font-size: 12px;
	font-weight: normal;
}
.style34 {
	color: #333333;
	font-weight: bold;
}
.style35 {
	color: #E8E8E8;
	font-size: 16px;
	font-weight: bold;
}
.style36 {
	color: #FF9933;
	font-size: 14px;
}
.style37 {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-align: center;
}
.style38 {color: #E5E5E5; }
.style11 {	font-size: 15px;
	font-weight: bold;
	color: #EFEFEF;
}
-->
</style>
</head>
<body>
<table width="1100" align="center" cellpadding="1" cellspacing="1" bordercolor="#000033" bgcolor="#595E80">
  <tr>
    <td width="1129" align="center" bgcolor="#4F5359"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="289"><table width="99%" height="396" align="center" cellpadding="2" cellspacing="2"  >
        <tr>
              <td width="1126" height="145" align="center" valign="top" bgcolor="#003366"><table width="99%" cellspacing="1" cellpadding="4">
                    <tr>
<!-- 
                          <td width="14%" align="center" valign="top" bgcolor="#002B55"><a href="pgc_request_enter_member.php"><img src="../images/Buttons/TrainingRequest copy.jpg" width="180" height="36" alt="Request" /></a></td>
 -->
                           <td width="14%" align="center" valign="top" bgcolor="#002B55">                            	
                             	<form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
                         	    	<input type="hidden" name="action" value="cb_pdp_training_request">
                         	    	<input type="hidden" name="page" value="enter_request_member">
                         	    	<input type='hidden' name='source_page' value='<?php  the_permalink() ?>' >	 
                         	    	<input type="image" name="submit" src='<?php echo plugin_dir_url(dirname(dirname(dirname(__FILE__ )))) ?>assets/images/trainingrequest.jpg'>	 
                      		    </form>                          
                          <td width="72%" align="center" valign="top" bgcolor="#002B55"><span class="style35">MEMBER ENTER /    MODIFY INSTRUCTION  REQUESTS</span>
                                <table width="95%" cellspacing="2" cellpadding="4">
                                      <tr>
                                            <td align="center"><span class="style37">You can only: 1/ Edit requests you entered ... and 2/ Edit requests that do not have a CFIG assigned</span></td>
                                            </tr>
                                      <tr>
                                            <td align="center"><span class="style37">Contact the Instruction Coordinator or the assigned CFIG to modify a request with an Assigned CFIG</span></td>
                                            </tr>
                                      </table></td>
                                   <td width="14%" align="center" valign="top" bgcolor="#002B55">
                                <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
                         	    	<input type="hidden" name="action" value="cb_pdp_training_request">
                         	    	<input type="hidden" name="page" value="vacation_view">
                         	    	<input type="hidden" name="sort" value="date">
                         	    	<input type='hidden' name='source_page' value='<?php  the_permalink() ?>' >	 
                         	    	<input type="image" name="submit" src='<?php echo plugin_dir_url(dirname(dirname(dirname(__FILE__ )))) ?>assets/images/displaycfig.jpg'>	 
                      		    </form>                          
                       
<!-- 
                          <td width="14%" align="center" valign="top" bgcolor="#002B55"><a href="pgc_request_vacation_view.php"><img src="../images/Buttons/DisplayCFIG copy.jpg" width="180" height="36" alt="CFIG" /></a></td>
 -->
                          </tr>
                    </table>
                    <table width="100%" cellspacing="1" cellpadding="2">
                          <tr>
                                <td height="22" align="center" bgcolor="#003366"><span class="style36">SATURDAY &gt;&gt;&gt; Students are required to be at the field no later than 8:00 AM on the morning of instruction.</span></td>
                                </tr>
                          <tr>
                                <td height="22" align="center" bgcolor="#003366"><span class="style36">SUNDAY &amp; HOLIDAYS &gt;&gt;&gt; Students are required to be at the field no later than 8:00 AM on the morning of instruction.</span></td>
                                </tr>
                          </table></td>
        </tr>
        <tr>
          <td height="189" align="center" valign="top" bgcolor="#003366"><table width="99%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#36373A">
              <tr>
                <td bgcolor="#35415B" class="style25"><div align="center">EDIT</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">MEMBER</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">WT</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">DATE REQUESTED </div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">TYPE</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">SAR</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">REQUEST NOTES</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">REQUEST CFIG 1</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">REQUEST CFIG 2</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">CFIG ASSIGNED</div></td>
                <td bgcolor="#35415B" class="style25"><div align="center">CFIG COMMENTS </div></td>
              </tr>
              <?php while ($row_Requests =mysqli_fetch_assoc($Requests)) { ?>
                <tr>
							  <?php
							  
			  $color1 = $BaseColor;
			  if ($row_Requests != null ){
 			 	 if (substr($row_Requests['mydate'],0,3) == "Sun") {
					  $color1 = $LtGreenColor; 
			 	 }
 			 	 if (substr($row_Requests['mydate'],0,3) == "Sat") {
			 		 $color1 = $LtBlueColor;
			 	 }
			  }
			  ?>
                 <td bgcolor="<?php echo $color1; ?>"><div align="center">  
                     <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
       	    	     <input type="hidden" name="action" value="cb_pdp_training_request">
       	    	     <input type="hidden" name="page" value="modify_member">
       	    	     <input type="hidden" name="request_key" value="<?php echo $row_Requests['request_key']; ?>">     
       	    	     <input type="submit" value="<?php echo $row_Requests['request_key']; ?>">	 
    		         </form>        
<!--                
                 <a href="pgc_request_modify_member.php?request_key=<?php echo $row_Requests['request_key']; ?>"><?php echo ($row_Requests != null) ?  $row_Requests['request_key'] : '' ; ?></a>
 -->                 
                 </div></td>
                 <td bgcolor="<?php echo $color1; ?>"><div align="left"><?php  echo ($row_Requests != null) ?  $row_Requests['member_name'] : ''; ?></div></td>
                 <td bgcolor="<?php echo $color1; ?>"><div align="center"><?php  echo ($row_Requests != null) ?  $row_Requests['member_weight'] :''; ?></div></td>
                 <td bgcolor="<?php echo $color1; ?>"><div align="center"><?php  echo ($row_Requests != null) ?  $row_Requests['mydate'] : ''; ?></div></td>
                 <td bgcolor="<?php echo $color1; ?>"><div align="left"><?php  echo ($row_Requests != null) ?  $row_Requests['request_type'] : ''; ?></div></td>
                 <td bgcolor="<?php echo $color1; ?>"><div align="center"><?php  echo ($row_Requests != null) ?  $row_Requests['sched_assist'] : ''; ?></div></td>
                 <td bgcolor="<?php echo $color1; ?>"><div align="left"><?php  echo ($row_Requests != null) ?  $row_Requests['request_notes'] : ''; ?></div></td>


			  <?php
			  
			  $color = $BaseColor;
			  if ($row_Requests != null ){
				  if (substr($row_Requests['mydate'],0,3) == "Sun") {
				   $color = $LtGreenColor;
				  }
				  
				  if (substr($row_Requests['mydate'],0,3) == "Sat") {
				   $color = $LtBlueColor;
				  }
				  
				  if ($row_Requests['cfig_vacation'] == "Y") {
				  $color = $RedColor; 
				  }
			  }
			  ?>
				  
                  <td bgcolor="<?php echo $color; ?>"><div align="left"><?php  echo ($row_Requests != null) ?  $row_Requests['request_cfig'] : ''; ?></div></td>
                 
			  <?php		  
			  $color = $BaseColor;
			  if ($row_Requests != null ){	
 			  	if (substr($row_Requests['mydate'],0,3) == "Sun") {
			  	 $color = $LtGreenColor;
			  	}
			  	
 			  	if (substr($row_Requests['mydate'],0,3) == "Sat") {
			  	 $color = $LtBlueColor;
			  	}
			  	
			  	if ($row_Requests['cfig_vacation2'] == "Y") {
			  	$color = $RedColor; 
			  	}
			  }
			  ?>				 
				  <td bgcolor="<?php echo $color; ?>"><div align="left"><?php  echo ($row_Requests != null) ?  $row_Requests['request_cfig2'] : ''; ?></div></td>
                  <?php
							  
			  $color = $BaseColor;
			  if ($row_Requests != null ){	
 			 	if (substr($row_Requests['mydate'],0,3) == "Sun") {
			 	  $color = $LtGreenColor;
			 	
			 	}
 			 	if (substr($row_Requests['mydate'],0,3) == "Sat") {
			 	$color = $LtBlueColor;
			 	}
			 	
 			 	if ($row_Requests['cfig2_vacation'] == "Y")   {
			 	$color = $RedColor; 
			 	}
			  }
			  ?>
				  
                  <td bgcolor="<?php echo $color; ?>"><div align="left"><?php  echo ($row_Requests != null) ?  $row_Requests['accept_cfig'] : ''; ?></div></td>
                  <td bgcolor="<?php echo $color1; ?>"><?php  echo ($row_Requests != null) ?  $row_Requests['accept_notes'] : ''; ?></td>
                </tr>
                <?php } ; ?>
            </table>
            <table width="50%" border="0" align="center" bgcolor="#CCCCCC">
              <tr>
                <td width="23%" align="center"><?php if ($pageNum_Requests > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, 0, $queryString_Requests); ?>"><img src="First.gif" border="0" /></a>
                  <?php } // Show if not first page ?>                </td>
                  <td width="31%" align="center"><?php if ($pageNum_Requests > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, max(0, $pageNum_Requests - 1), $queryString_Requests); ?>"><img src="Previous.gif" border="0" /></a>
                    <?php } // Show if not first page ?>                </td>
                  <td width="23%" align="center"><?php if ($pageNum_Requests < $totalPages_Requests) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, min($totalPages_Requests, $pageNum_Requests + 1), $queryString_Requests); ?>"><img src="Next.gif" border="0" /></a>
                    <?php } // Show if not last page ?>                </td>
                  <td width="23%" align="center"><?php if ($pageNum_Requests < $totalPages_Requests) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, $totalPages_Requests, $queryString_Requests); ?>"><img src="Last.gif" border="0" /></a>
                    <?php } // Show if not last page ?>                </td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td height="29" align="center" bgcolor="#4F5359" class="style16"><div align="center">
                <table width="99%" cellspacing="1" cellpadding="0">
                      <tr>
                            <td width="24%" align="center" bgcolor="#4A4A4A">SAR = Scheduling Assistance Requested</td>
                        	<td width="53%" align="center" bgcolor="#4A4A4A"><a href=<?PHP echo $_SESSION['PDP_HOME']; ?>><img src="<?php echo plugin_dir_url(dirname(dirname(dirname(__FILE__ )))) ?>assets/images/gomembers.jpg" width="133" height="24" alt="Members" /></a></strong></a></td>
<!-- 
                            <td width="53%" align="center" bgcolor="#4A4A4A"><a href=<?PHP echo $_SESSION['PDP_HOME']; ?>><img src="../images/Buttons/GoMembers.jpg" width="133" height="24" alt="Members" /></a></strong></a></td>
 -->
                            <td width="23%" align="center" bgcolor="#4A4A4A" class="style35"><span class="style33">RED  CFIG NAME =  NOT AVAILABLE ON THAT DATE</span></td>
                      </tr>
          </table>
          </div></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Requests);

?>
