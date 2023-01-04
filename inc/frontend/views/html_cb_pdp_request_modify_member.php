<?php
global $PGCwp; // database handle for accessing wordpress db
global $PGCi;  // database handle for PDP external db
global $wpdb;
?>
<?php
error_reporting(E_ALL);
//ini_set('display_errors', 'On');
if (!isset($_SESSION)) {
  session_start();
}
//require_once('pgc_check_login.php'); 
?>
<?php
// $MM_restrictGoTo = "../Index.html";
// if ( !(isset($_SESSION['MM_Username']) ))  {   
//     header("Location: ". $MM_restrictGoTo); 
//   exit;
//  }
// $session_pilotname = $_SESSION['MM_PilotName'];
// $session_email = $_SESSION['MM_Username']; 
// if ( trim($session_pilotname = '') )  {   
//     header("Location: ". $MM_restrictGoTo); 
//   exit;
//  }

$current_pilot = wp_get_current_user(); 
$pilotname = $current_pilot->user_lastname . ", " .  $current_pilot->user_firstname;
$pilot_email =  $current_pilot->user_email;  

//mysql_select_db($database_PGC, $PGC);
$query_System = "SELECT * FROM pgc_system";
$System2 = mysqli_query($PGCi, $query_System )  or die(mysqli_error($PGCi));
$row_System2 =mysqli_fetch_assoc($System2);
$totalRows_System2 = mysqli_num_rows($System2);
$webmaster = $row_System2['request_emails'];
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
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$date_limit = date('Y-m-d', strtotime("+7 days"));
$todays_date = date('Y-m-d', strtotime("0 days"));

/* if (($_POST['date1'] <= $date_limit) && ($_POST['date1'] >= $todays_date)) { */


		if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
		
			/*** Check to See If CFIG1 is Off Duty ***/
			
			 		
 $insertSQL = sprintf("SELECT * FROM pgc_cfig_dates WHERE cfig_name = %s AND duty_date = %s AND cfig_vacation = 'Y'" ,  
						  GetSQLValueString($_POST['request_cfig'], "text"),
					      GetSQLValueString($_SESSION['curr_request_date'], "date"));
	
	  //mysql_select_db($database_PGC, $PGC);
	  $Result1 = mysqli_query($PGCi, $insertSQL )  or die(mysqli_error($PGCi));
	  $row_Result1 =mysqli_fetch_assoc($Result1);
	  $totalRows_Result1 = mysqli_num_rows($Result1);
	  $OffDutyCFIG1 = 'Y';
	  If ($totalRows_Result1  ==  0) {
	      $OffDutyCFIG1 = 'N';
	  }
	  
	  	/*** Check to See If CFIG2 is Off Duty ***/
		
    $insertSQL = sprintf("SELECT * FROM pgc_cfig_dates WHERE cfig_name = %s AND duty_date = %s AND cfig_vacation = 'Y'" ,  
						  GetSQLValueString($_POST['request_cfig2'], "text"),
					      GetSQLValueString($_SESSION['curr_request_date'], "date"));
	
	  //mysql_select_db($database_PGC, $PGC);
	  $Result1 = mysqli_query($PGCi, $insertSQL )  or die(mysqli_error($PGCi));
	  $row_Result1 =mysqli_fetch_assoc($Result1);
	  $totalRows_Result1 = mysqli_num_rows($Result1);
	  $OffDutyCFIG2 = 'Y';
	  If ($totalRows_Result1  ==  0) {
	      $OffDutyCFIG2 = 'N';
	  }
	  
	  
If ($OffDutyCFIG1 == 'N' AND $OffDutyCFIG2 == 'N' ) {		
		
		
		/*  Save Original Values */
			  $updateSQL = sprintf("UPDATE pgc_request SET orig_request_date = request_date, orig_request_cfig =    request_cfig, orig_request_cfig2 = request_cfig2, orig_cfig1_email = cfig1_email, orig_cfig2_email = cfig2_email, orig_assign_cfig_email = assign_cfig_email, orig_accept_cfig = accept_cfig  WHERE request_key=%s",
              GetSQLValueString($_POST['request_key'], "int"));
			  //mysql_select_db($database_PGC, $PGC);
              $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
  
  /*  Save New Values */
		
	          $updateSQL = sprintf("UPDATE pgc_request SET  request_type=%s, member_weight=%s, request_cfig=%s, request_cfig2=%s,  request_notes=%s,  record_deleted=%s WHERE request_key=%s",
                                          
                                            
                       GetSQLValueString($_POST['request_type'], "text"),
					   GetSQLValueString($_POST['request_weight'], "text"),
					   
                       GetSQLValueString($_POST['request_cfig'], "text"),
					   GetSQLValueString($_POST['request_cfig2'], "text"),
                      
                       GetSQLValueString($_POST['request_notes'], "text"),
                    
                       GetSQLValueString($_POST['record_deleted'], "text"),
                       GetSQLValueString($_POST['request_key'], "int"));

  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
  
    /*   Update E-Mail IDs                 */
  $id = mysqli_insert_id($PGCi); 

  $colname_Requests = "-1";
  $colname_Requests = GetSQLValueString($_POST['request_key'], "int");

	  $updateSQL = sprintf( "UPDATE pgc_request SET cfig1_email = ''
      WHERE request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  

	  $updateSQL = sprintf( "UPDATE pgc_request SET cfig2_email = ''
      WHERE request_key=%s",    
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  $updateSQL = sprintf( "UPDATE pgc_request SET assign_cfig_email = ''
      WHERE request_key=%s",      
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	 	  
	  	  $updateSQL = sprintf( "UPDATE pgc_request SET orig_cfig1_email = ''
      WHERE request_key=%s",      
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
 
	  	  $updateSQL = sprintf( "UPDATE pgc_request SET orig_cfig2_email = ''
      WHERE request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
   
	  
	  	  $updateSQL = sprintf( "UPDATE pgc_request SET orig_assign_cfig_email = ''
      WHERE request_key=%s",  
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));

	  /* Refresh Emails */
	  
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
	  
	  	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.assign_cfig_email = B.USER_ID
      WHERE A.accept_cfig = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
/*======================*/	  
	  
      $id = mysqli_insert_id($PGCi); 
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.orig_cfig1_email = B.USER_ID
      WHERE A.orig_request_cfig = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.orig_cfig2_email = B.USER_ID
      WHERE A.orig_request_cfig2 = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  	  	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.orig_assign_cfig_email = B.USER_ID
      WHERE A.orig_accept_cfig = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi)); 

/**************************/
/**************************/

      
     /*  Send Email */
	 $colname_Requests = "-1";
if (isset($_POST['request_key'])) {
  $colname_Requests = $_POST['request_key'];
}
	 
 //mysql_select_db($database_PGC, $PGC);
$query_Requests9 = sprintf("SELECT * FROM pgc_request WHERE request_key = %s", $colname_Requests);
$Requests9= mysqli_query($PGCi, $query_Requests9 )  or die(mysqli_error($PGCi));
$row_Requests9 =mysqli_fetch_assoc($Requests9);
$totalRows_Requests9 = mysqli_num_rows($Requests9);
  
          $message = " " . $row_Requests9['member_name'] . "\n\n" . "You modified your instruction request as indicated below." . "\n\n";
		  
		  		    
           $made_change = 'no'; 
	       
  
       	   $C = 'cfig_vacation';
           $B = 'orig_request_cfig';
           $A = 'request_cfig'; 
	       If ($row_Requests9[$A] != $row_Requests9[$B]) {
		   $new = $row_Requests9[$A];
   		   $old = $row_Requests9[$B];
		   
		   
           $message = $message . " Request CFIG 1 was changed TO ... " . trim($new) . " ... FROM ... " . trim($old) . "\n\n";
           $made_change = 'yes';
           } 
           $C = 'cfig_vacation2';
		   $B = 'orig_request_cfig2';
           $A = 'request_cfig2'; 
		   If ($row_Requests9[$A] != $row_Requests9[$B]) {
           $new = $row_Requests9[$A];
   		   $old = $row_Requests9[$B];
		   
		   
           $message = $message . " Request CFIG 2 was changed TO ... " . trim($new) . " ... FROM ... " . trim($old) . "\n\n";
           $made_change = 'yes';
           } 
		   
		   If ($row_Requests9['record_deleted'] == 'Y') {
            $message = $message . " THIS RECORD WAS DELETED !!!!" . "\n\n";
           $made_change = 'yes';
           } 
   
		$message = $message . "Current Instruction Request" . "\n";
		$message = $message . "====================" . "\n";
		$message = $message ."Request Number:   " . $row_Requests9['request_key'] . "\n";
		$message = $message ."Member Name:      " . $row_Requests9['member_name'] . "\n";
		$message = $message ."Date Requested:   " . $row_Requests9['request_date'] . "\n";
		$message = $message ."Request Type:     " . $row_Requests9['request_type'] . "\n";
		$message = $message ."Request Notes:    " . $row_Requests9['request_notes'] . "\n";
		$message = $message ."Member Weight:    " . $row_Requests9['member_weight'] . "\n";
		$message = $message ."CFIG 1 Requested: " . $row_Requests9['request_cfig'] . "\n";
		$message = $message ."CFIG 2 Requested: " . $row_Requests9['request_cfig2'] . "\n";
		$message = $message ."CFIG Assigned:    " . $row_Requests9['accept_cfig'] . "\n";
		$message = $message ."CFIG Notes:       " . $row_Requests9['accept_notes'] . "\n";
		$message = $message ."Record Deleted?:  " . $row_Requests9['record_deleted'] . "\n\n";
		

 		$message = $message . "This record was entered by ... " . $current_pilot . "\n\n\n";
		
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
	$message = $message . $entry_ip . "\n";
		
		/* Create Email List */
	    $ToList = $row_Requests9['member_id'] ;
		
		if (trim($row_Requests9['cfig1_email']) != '') {
		$ToList = $ToList . "," . $row_Requests9['cfig1_email'];
				}
		if (trim($row_Requests9['cfig2_email']) != '') {
		$ToList = $ToList . "," . $row_Requests9['cfig2_email'];
				}
	
		/* $webmaster = "support@pgcsoaring.org";
		$webmaster = $row_System[request_emails]; */
		
		
		$ToList = $ToList . "," . $webmaster;
		$message = $message ."Email List:  " . $ToList . "\n\n";
		/* End - Create Email List */
	    
		$to = $ToList;
// 		if ($row_System['sys_status'] == 'test') {
// 				$to = "ventusdriver@gmail.com, support@pgcsoaring.org";
// 		}
		    
		$subject = "PGC Instruction Request - Modified by Member";
				
	//    $email = $_REQUEST['email'];
				
		$headers = "From: PGC Pilot Data Portal";
		$headers = "From: ventusdriver@gmail.com";
		$headers = "From: PGC-<em>nstructionm>Member-Request-Modifyw@noreply.com";
		$headers = "From: PGC-Instruction@noreply.com";
		
	   If ($made_change == 'yes') {
		  $sent = mail($to, $subject, $message, $headers) ; }

		  /*  END EMAIL */

  
  $updateGoTo = $_SESSION['last_rm_query'];
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
   header(sprintf("Location: %s", $updateGoTo));
   $_SESSION['MM_S_Message'] =  "Modify record or save with no changes";
  
} else  {
	 If ($OffDutyCFIG1 == 'Y') {
	 $_SESSION['MM_S_Message'] =  "The CFIG1 you attempted to select is off duty for this date - select again or save";
	 }
 	 If ($OffDutyCFIG2 == 'Y') {
	 $_SESSION['MM_S_Message'] =  "The CFIG2 you attempted to select is off duty for this date - select again or save";
	 }
	 If ($OffDutyCFIG1 == 'Y' AND $OffDutyCFIG2 == 'Y' ) {
	 $_SESSION['MM_S_Message'] =  "Both CFIGs you attempted to select are off duty for this date - select again or save";
	 }
 
 }
 
 /* End of CFIG ON Duty */
   	
} else {
	if (isset($_POST['date1'])) {
	$_SESSION['MM_S_Message'] = "Enter Training Request";
	} else {
	$_SESSION['MM_S_Message'] = "Enter Training Request";
	}
} 
   

$colname_Requests = "-1";
if (isset($_GET['request_key'])) {
  $colname_Requests = $_GET['request_key'];
}
//mysql_select_db($database_PGC, $PGC);
$query_Requests = sprintf("SELECT request_key, entry_date, member_id, member_name, member_weight, request_date, request_time, request_type, request_cfig, cfig_vacation, request_cfig2, cfig_vacation2, request_notes, accept_cfig, cfig2_vacation, cfig_weight, accept_date, accept_notes, cfig1_email, cfig2_email, assign_cfig_email, record_deleted, Date_format(request_date,'%%W, %%M %%e') as mydate, cfig_confirmed, sched_assist FROM pgc_request WHERE request_key = %s ORDER BY request_date ASC", $colname_Requests);
$Requests = mysqli_query($PGCi, $query_Requests )  or die(mysqli_error($PGCi));
$row_Requests =mysqli_fetch_assoc($Requests);
$totalRows_Requests = mysqli_num_rows($Requests);
$_SESSION['curr_request_date'] = $row_Requests['request_date'];

//mysql_select_db($database_PGC, $PGC);
$query_Instructors = "SELECT * FROM pgc_instructors WHERE cfig = 'Y' AND rec_active = 'Y' ORDER BY Name ASC";
$Instructors = mysqli_query($PGCi, $query_Instructors )  or die(mysqli_error($PGCi));
$row_Instructors =mysqli_fetch_assoc($Instructors);
$totalRows_Instructors = mysqli_num_rows($Instructors);

//mysql_select_db($database_PGC, $PGC);
$query_DutyDates = "SELECT date, Date_format(date,'%W, %M %e') as mydate FROM pgc_field_duty WHERE `date` >=CURDATE() ORDER BY `date` ASC LIMIT 3";
$DutyDates = mysqli_query($PGCi, $query_DutyDates )  or die(mysqli_error($PGCi));
$row_DutyDates =mysqli_fetch_assoc($DutyDates);
$totalRows_DutyDates = mysqli_num_rows($DutyDates);

//mysql_select_db($database_PGC, $PGC);
$query_DutyDates = "SELECT date, Date_format(date,'%W, %M %e') as mydate FROM pgc_field_duty WHERE `date` >=CURDATE() ORDER BY `date` ASC LIMIT 3";
$DutyDates = mysqli_query($PGCi, $query_DutyDates )  or die(mysqli_error($PGCi));
$row_DutyDates =mysqli_fetch_assoc($DutyDates);
$totalRows_DutyDates = mysqli_num_rows($DutyDates);

//mysql_select_db($database_PGC, $PGC);
$query_System = "SELECT * FROM pgc_system";
$System = mysqli_query($PGCi, $query_System )  or die(mysqli_error($PGCi));
$row_System =mysqli_fetch_assoc($System);
$totalRows_System = mysqli_num_rows($System);

//mysql_select_db($database_PGC, $PGC);
$query_instruction_types = "SELECT * FROM pgc_instruction_types ORDER BY Instruction_type ASC";
$instruction_types = mysqli_query($PGCi, $query_instruction_types )  or die(mysqli_error($PGCi));
$row_instruction_types =mysqli_fetch_assoc($instruction_types);
$totalRows_instruction_types = mysqli_num_rows($instruction_types);
?>
<?php
/* EXIT */
 if (($current_pilot <> $row_Requests['member_name']) OR (TRIM($row_Requests['accept_cfig'])) <> '' ) {
 
   $updateGoTo = $_SESSION[last_rm_query];
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  $_SESSION['MM_S_Message'] = "Modify record or save with no changes";
  

 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script src="../java/javascripts.js" type="text/javascript"></script>
<script src="../java/CalendarPopup.js" type="text/javascript"></script>
<script src="../java/zxml.js" type="text/javascript"></script>
<script src="../java/workingjs.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript" ID="js1">
		var cal = new CalendarPopup();
	 </SCRIPT>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PGC Data Portal - Instruction Request</title>
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
	color: #FF9900;
	font-size: 16px;
}
.style31 {color: #000000}
.style32 {font-weight: bold; color: #000000; }
.style33 {font-size: 14px}
.style38 {font-weight: bold; color: #6666FF; font-size: 14px; }
.style39 {color: #BAB3FF}
.style43 {font-size: 16px; }
.style41 {font-size: 18px}
.style44 {color: #000000; font-size: 14px; font-weight: bold; font-style: italic; }
.style45 {color: #FFFFFF}
-->
</style>
</head>
<body>  
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#595E80">
      <tr>
            <td align="center" bgcolor="#666666"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
      </tr>
      <tr>
            <td height="398" valign="top"><table width="92%" height="345" align="center" cellpadding="4" cellspacing="3"  >
                        <tr>
                              <td width="884" height="24" valign="top" bgcolor="#4F5359"><div align="center" class="style2">
                                          <table width="60%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                      <td><div align="center" class="style33">PGC STUDENT INSTRUCTION REQUEST - MODIFY </div></td>
                                                </tr>
                                          </table>
                                    </div></td>
                        </tr>
                        <tr>
                              <td height="277" align="center" valign="top" bgcolor="#424A66"><form action="<?php echo $editFormAction; ?>" method="post" name="new_flight" id="new_flight">
                                          <p><span class="style25"><?php echo "Requests only accepted for scheduled ops days - one week into the future."; ?></span></p>
                                          <table width="500" align="center" cellpadding="5" cellspacing="2" bgcolor="#666666">
                                                <tr valign="baseline">
                                                      <td height="35" colspan="2" align="right" nowrap bgcolor="#6666FF"><table width="380" border="0" cellpadding="2" cellspacing="2" bgcolor="#6666FF">
                                                                  <tr>
                                                                        <td width="214" height="30" valign="middle" bgcolor="#6666FF" class="style32"><div align="center" class="style43"><?php echo $current_pilot; ?></div></td>
                                                                        <td width="214" valign="middle" bgcolor="#6666FF" class="style32"><div align="center" class="style43"><?php echo $pilot_email ; ?></div></td>
                                                                  </tr>
                                                            </table></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td width="152" height="1" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="left"><span class="style32">REQUEST KEY</span></div></td>
                                                      <td width="232" height="1" valign="middle" bgcolor="#6666FF" class="style44"><div align="left"><?php echo $row_Requests['request_key']; ?></div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap bgcolor="#6666FF" class="style31"><div align="left" class="style32"> INSTRUCTION DATE</div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF" class="style17 style45"><div align="left"><?php echo $row_Requests['mydate']; ?></div></td>
                                                </tr>
                                                
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="left"><span class="style32">INSTRUCTION TYPE</span></div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <select name="request_type" id="request_type">
                                                                    <?php
do {  
?>
                                                                    <option value="<?php echo $row_instruction_types['Instruction_type']?>"<?php if (!(strcmp($row_instruction_types['Instruction_type'], $row_Requests['request_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_instruction_types['Instruction_type']?></option>
                                                                    <?php
} while ($row_instruction_types =mysqli_fetch_assoc($instruction_types));
  $rows = mysqli_num_rows($instruction_types);
  if($rows > 0) {
      mysqli_data_seek($instruction_types, 0);
	  $row_instruction_types =mysqli_fetch_assoc($instruction_types);
  }
?>
                                                                  </select>
                                                            </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="left"><span class="style32">MEMBER WEIGHT</span></div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <input name="request_weight" type="text" id="request_weight" value="<?php echo $row_Requests['member_weight']; ?>" size="3" maxlength="3" />
                                                            </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="left"><span class="style32">REQUESTED CFIG 1</span> </div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <select name="request_cfig">
                                                                        <?php
do {  
?>
                                                                        <option value="<?php echo $row_Instructors['Name']?>"<?php if (!(strcmp($row_Instructors['Name'], $row_Requests['request_cfig']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Instructors['Name']?></option>
                                                                        <?php
} while ($row_Instructors =mysqli_fetch_assoc($Instructors));
  $rows = mysqli_num_rows($Instructors);
  if($rows > 0) {
      mysqli_data_seek($Instructors, 0);
	  $row_Instructors =mysqli_fetch_assoc($Instructors);
  }
?>
                                                        </select>
                                                            </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap="nowrap" bgcolor="#6666FF"><div align="left"><span class="style32">REQUESTED CFG 2</span></div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <select name="request_cfig2" id="request_cfig2">
                                                                        <?php
do {  
?>
                                                                        <option value="<?php echo $row_Instructors['Name']?>"<?php if (!(strcmp($row_Instructors['Name'], $row_Requests['request_cfig2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Instructors['Name']?></option>
                                                                        <?php
} while ($row_Instructors =mysqli_fetch_assoc($Instructors));
  $rows = mysqli_num_rows($Instructors);
  if($rows > 0) {
      mysqli_data_seek($Instructors, 0);
	  $row_Instructors =mysqli_fetch_assoc($Instructors);
  }
?>
                                                        </select>
                                                            </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="left"><span class="style32">REQUEST NOTES</span></div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <input type="text" name="request_notes" value="<?php echo $row_Requests['request_notes']; ?>" size="32">
                                                            </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="left"><span class="style32"> DELETE REQUEST ? </span></div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <select name="record_deleted" id="record_deleted">
                                                                        <option value="N" <?php if (!(strcmp("N", $row_Requests['record_deleted']))) {echo "selected=\"selected\"";} ?>>N</option>
                                                                        <option value="Y" <?php if (!(strcmp("Y", $row_Requests['record_deleted']))) {echo "selected=\"selected\"";} ?>>Y</option>
                                                        </select>
                                                            </div></td>
                                                </tr>
                                                
                                                <tr valign="baseline">
                                                      <td height="34" colspan="2" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="center">
                                                                  <input type="submit" value="Save">
                                                            </div></td>
                                                </tr>
                                          </table>
                                          <p>
                                            <input type="hidden" name="MM_update" value="form1">
                                            <input type="hidden" name="request_key" value="<?php echo $row_Requests['request_key']; ?>"></p>
                                          </form>
                                    <p><span class="style38 style39"><span class="style41"><?php echo $_SESSION['MM_S_Message']; ?></span>&nbsp;</span></p></td>
                        </tr>
                        <tr>
                              <td height="30" valign="top" bgcolor="#4F5359" class="style16"><div align="center"><a href=<?PHP echo $_SESSION['PDP_HOME']; ?> class="style17">BACK TO MEMBERS PAGE </a></div></td>
                        </tr>
                  </table></td>
      </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Requests);

mysqli_free_result($Instructors);

mysqli_free_result($DutyDates);

mysqli_free_result($System);

mysqli_free_result($instruction_types);
?>
