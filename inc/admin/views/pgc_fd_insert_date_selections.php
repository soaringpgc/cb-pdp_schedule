<?php
require_once('pgc_connect.php');
?>
<?php
error_reporting(E_ALL);
if (!isset($_SESSION)) {
  session_start();
}
require_once('pgc_check_login.php'); 
?>
<?php function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{


  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//Insert Holidays
//mysql_select_db($database_PGC, $PGC);
$query_session_holidays = "INSERT IGNORE INTO pgc_field_duty_selections(date)SELECT holiday_date FROM pgc_field_duty_holidays";
$session_holidays = mysqli_query($PGCi, $query_session_holidays )  or die(mysqli_error($PGCi));

//Getting the From Date
//mysql_select_db($database_PGC, $PGC);
$query_session_control = "SELECT fd_session, session_start_date, session_end_date, session_active FROM pgc_field_duty_control WHERE fd_session = '1'";
$session_control = mysqli_query($PGCi, $query_session_control )  or die(mysqli_error($PGCi));
$row_session_control =mysqli_fetch_assoc($session_control);
$from = $row_session_control['session_start_date'];


//Getting the To Date
//mysql_select_db($database_PGC, $PGC);
$query_session_control = "SELECT fd_session, session_start_date, session_end_date, session_active FROM pgc_field_duty_control WHERE fd_session = '3'";
$session_control = mysqli_query($PGCi, $query_session_control )  or die(mysqli_error($PGCi));
$row_session_control =mysqli_fetch_assoc($session_control);
$to = $row_session_control['session_end_date'];

 
$startTime = strtotime($from); 
$endTime = strtotime($to); 

//mysql_select_db($database_PGC, $PGC);

for($time = $startTime; $time <= $endTime; $time = strtotime('+1 day', $time)) 
{ 
   $thisDate = date('Y-m-d', $time); 
   $thisDateDay = date('l', $time);
   If  ($thisDateDay == "Saturday" || $thisDateDay == "Sunday" ) {
      //echo $thisDateDay;
      $query = sprintf("INSERT IGNORE INTO pgc_field_duty_selections(date) VALUES (%s)", 
      GetSQLValueString($thisDate, "date"));
      mysqli_query($PGCi, $query) or die('Error, query failed : ' . mysqli_error($PGCi) ) ; 
	

   }
} 

//Insert Sessions
//mysql_select_db($database_PGC, $PGC);
$query_sessions = "UPDATE pgc_field_duty_selections SET pgc_field_duty.session = (SELECT pgc_field_duty_control.fd_session FROM pgc_field_duty_control WHERE (pgc_field_duty_selections.date >= pgc_field_duty_control.session_start_date and pgc_field_duty_selections.date <= pgc_field_duty_control.session_end_date))";
$sessions = mysqli_query($PGCi, $query_sessions )  or die(mysqli_error($PGCi));

$updateGoTo = "pgc_fd_menu.php";
header(sprintf("Location: %s", $updateGoTo));
?> 


