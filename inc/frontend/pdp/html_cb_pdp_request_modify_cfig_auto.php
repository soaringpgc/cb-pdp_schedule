<?php

global $PGCwp; // database handle for accessing wordpress db
global $PGCi;  // database handle for PDP external db
global $wpdb;
//require_once('pgc_connect.php');
?>
<?php
error_reporting(E_ALL);
if (!isset($_SESSION)) {
  session_start();
}
//require_once('pgc_check_login.php'); 
?>
<?php
//mysql_select_db($database_PGC, $PGC);
$query_System = "SELECT * FROM pgc_system";
$System = mysqli_query($PGCi, $query_System )  or die(mysqli_error($PGCi));
$row_System =mysqli_fetch_assoc($System);
$totalRows_System = mysqli_num_rows($System);
$session_pilotname = $_SESSION['MM_PilotName'];
$session_email = $_SESSION['MM_Username']; 
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
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
 

?>
<?php 
$colname_Requests = "-1";
if (isset($_GET['request_id'])) {
  $colname_Requests = $_GET['request_id'];
}

 //mysql_select_db($database_PGC, $PGC);
$query_Requests7 = sprintf("SELECT * FROM pgc_request WHERE request_key = %s", $colname_Requests);
$Requests7= mysqli_query($PGCi, $query_Requests7 )  or die(mysqli_error($PGCi));
$row_Requests7 =mysqli_fetch_assoc($Requests7);
$totalRows_Requests7 = mysqli_num_rows($Requests7);
$cfig_vacation = $row_Requests7[cfig_vacation];
$accept_cfig = $row_Requests7[accept_cfig];
$request_cfig = $row_Requests7[request_cfig];

If ($cfig_vacation == "N" AND ($accept_cfig != $request_cfig) ) { 

//mysql_select_db($database_PGC, $PGC);
$query_Requests = sprintf("UPDATE pgc_request SET accept_cfig=request_cfig WHERE request_key=%s", $colname_Requests);
$Requests = mysqli_query($PGCi, $query_Requests )  or die(mysqli_error($PGCi));

	   	  /*   Update E-Mail IDs                 */
	  $id = mysqli_insert_id($PGCi); 
 $colname_Requests = "-1";
if (isset($_GET['request_id'])) {
  $colname_Requests = $_GET['request_id'];
}
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.assign_cfig_email = B.USER_ID
      WHERE A.request_cfig = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  	  	  	  /*** Blank Email for Off Duty CFIG1 ***/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_cfig_dates B SET A.assign_cfig_email = ''
      WHERE A.accept_cfig = B.cfig_name AND A.request_date = B.duty_date AND B.cfig_vacation = 'Y' AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  /**********/
	  
	  
	  
	  $id = mysqli_insert_id($PGCi); 
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.cfig1_email = B.USER_ID
      WHERE A.request_cfig = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  	  	  /*** Blank Email for Off Duty CFIG1 ***/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_cfig_dates B SET A.cfig1_email = ''
      WHERE A.request_cfig = B.cfig_name AND A.request_date = B.duty_date AND B.cfig_vacation = 'Y' AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  /**********/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.cfig2_email = B.USER_ID
      WHERE A.request_cfig2 = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  	  	  	  /*** Blank Email for Off Duty CFIG2 ***/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_cfig_dates B SET A.cfig2_email = ''
      WHERE A.request_cfig2 = B.cfig_name AND A.request_date = B.duty_date AND B.cfig_vacation = 'Y' AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  /**********/

/*  Send Email */
	 $colname_Requests = "-1";
if (isset($_GET['request_id'])) {
  $colname_Requests = $_GET['request_id'];
}
	 
 //mysql_select_db($database_PGC, $PGC);
$query_Requests9 = sprintf("SELECT * FROM pgc_request WHERE request_key = %s", $colname_Requests);
$Requests9= mysqli_query($PGCi, $query_Requests9 )  or die(mysqli_error($PGCi));
$row_Requests9 =mysqli_fetch_assoc($Requests9);
$totalRows_Requests9 = mysqli_num_rows($Requests9);
  
          $message = " " . $row_Requests9[member_name] . "\n\n" . " The CFIG Team assigned an instructor for your request as indicated below." . "\n\n";
		  
		  
		    
        $made_change = 'yes';   
		   
   
		$message = $message . "Instruction Request" . "\n";
		$message = $message . "================" . "\n";
				$message = $message ."CFIG ASSIGNED:    " . $row_Requests9[accept_cfig] . "   " . $row_Requests9[assign_cfig_email] . "\n\n";
		$message = $message ."Request Number:   " . $row_Requests9[request_key] . "\n";
		$message = $message ."Member Name:      " . $row_Requests9[member_name] . "\n";
		$message = $message ."Date Requested:   " . $row_Requests9[request_date] . "\n";
		$message = $message ."Request Type:     " . $row_Requests9[request_type] . "\n";
		$message = $message ."Request Notes:    " . $row_Requests9[request_notes] . "\n";
		$message = $message ."Member Weight:    " . $row_Requests9[member_weight] . "\n";
		$message = $message ."CFIG 1 Requested: " . $row_Requests9[request_cfig] . "\n";
		$message = $message ."CFIG 2 Requested: " . $row_Requests9[request_cfig2] . "\n";
		
		$message = $message ."CFIG Notes:       " . $row_Requests9[accept_notes] . "\n";
		$message = $message ."Record Deleted?:  " . $row_Requests9[record_deleted] . "\n\n";
		

		$message = $message . "CFIG assigned by ... " . $session_pilotname . "\n\n\n";
		
			$entry_ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
       $entry_ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
       $entry_ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
       $entry_ip=$_SERVER['REMOTE_ADDR'];
    }
	$message = $message . $entry_ip . "\n\n";

		
	    /* Create Email List */
	    $ToList = $row_Requests9[member_id] ;
		
		if (trim($row_Requests9[assign_cfig_email]) != '') {
		$ToList = $ToList . "," . $row_Requests9[assign_cfig_email];
				}
		if (trim($row_Requests9[cfig2_email]) != '') {
		$ToList = $ToList . "," . $row_Requests9[cfig2_email];
				}
	
		$webmaster = "support@pgcsoaring.org";
		$webmaster = $row_System[request_emails];
		$ToList = $ToList . "," . $webmaster;
		$message = $message ."Email List:  " . $ToList . "\n\n";
		/* End - Create Email List */
			  		
			
		$to = $ToList;
		if ($row_System[sys_status] == 'test') {
				$to = "ventusdriver@gmail.com, support@pgcsoaring.org";
		}
		    
		$subject = "PGC Instruction Request - CFIG Assigned by CFIG Team";
				
	    $email = $_REQUEST['email'];
				
		$headers = "From: PGC Pilot Data Portal";
		$headers = "From: ventusdriver@gmail.com";
		$headers = "From: PGC-Request-CFIG-Auto@noreply.com";
		
	   If ($made_change == 'yes') {
		  $sent = mail($to, $subject, $message, $headers) ; }

		  /*  END EMAIL */

  }
  $insertGoTo = $_SESSION['last_cfig_r_query'];
  header(sprintf("Location: %s", $insertGoTo));


mysqli_free_result($System);
?>