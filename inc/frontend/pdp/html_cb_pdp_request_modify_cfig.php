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
$MM_restrictGoTo = "../Index.html";
if ( !(isset($_SESSION['MM_Username']) ))  {   
    header("Location: ". $MM_restrictGoTo); 
  exit;
 }
//  if (substr($_SESSION['MM_PilotRole'],0,5)<>'ADMIN' ){ 
//     header("Location: ". $MM_restrictGoTo); 
//   exit;
//  }
$session_pilotname = $_SESSION['MM_PilotName'];
$session_email = $_SESSION['MM_Username']; 
//mysql_select_db($database_PGC, $PGC);
$query_System = "SELECT * FROM pgc_system";
$System = mysqli_query($PGCi, $query_System )  or die(mysqli_error($PGCi));
$row_System =mysqli_fetch_assoc($System);
$totalRows_System = mysqli_num_rows($System);
$webmaster = $row_System['request_emails'];
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

/*  Save Original Values */
			  $updateSQL = sprintf("UPDATE pgc_request SET orig_request_date = request_date, orig_request_cfig =    request_cfig, orig_request_cfig2 = request_cfig2, orig_cfig1_email = cfig1_email, orig_cfig2_email = cfig2_email, orig_assign_cfig_email = assign_cfig_email, orig_accept_cfig = accept_cfig WHERE request_key=%s",
              GetSQLValueString($_POST['request_key'], "int"));
			  //mysql_select_db($database_PGC, $PGC);
              $Result2 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));

  
  /*  Save New Values */

  $updateSQL = sprintf("UPDATE pgc_request SET request_date=%s, request_type=%s, member_weight=%s, request_cfig=%s, request_cfig2=%s,  request_notes=%s,
  accept_cfig=%s, accept_notes=%s, record_deleted=%s WHERE request_key=%s",
                       
					   GetSQLValueString($_POST['date1'], "date"),
					   
					   GetSQLValueString($_POST['request_type'], "text"),
					   GetSQLValueString($_POST['request_weight'], "text"),
					   
                       GetSQLValueString($_POST['request_cfig'], "text"),
					   GetSQLValueString($_POST['request_cfig2'], "text"),
                      
                       GetSQLValueString($_POST['request_notes'], "text"),
					   
					   GetSQLValueString($_POST['accept_cfig'], "text"),
                       GetSQLValueString($_POST['accept_notes'], "text"),
                       GetSQLValueString($_POST['record_deleted'], "text"),
                       GetSQLValueString($_POST['request_key'], "int"));

  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));

	
	  
	      /*   Update E-Mail IDs - Version II                */

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

	  /* Refresh all Emails */
	  
	  	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.member_id = B.USER_ID
      WHERE A.member_name = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($_POST['request_key'],  "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  

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
	  
	  	  	  	  /*** Blank Email for ASSIGN CFIG ***/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_cfig_dates B SET A.assign_cfig_email = ''
      WHERE A.accept_cfig = B.cfig_name AND A.request_date = B.duty_date AND B.cfig_vacation = 'Y' AND A.request_key=%s",        
                       GetSQLValueString($colname_Requests, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  /**********/
	  
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

	  
  
     /*  Send Email */
	 $colname_Requests = "-1";
if (isset($_POST['request_id'])) {
  $colname_Requests = $_POST['request_id'];
} 

 //mysql_select_db($database_PGC, $PGC);
$query_Requests9 = sprintf("SELECT * FROM pgc_request WHERE request_key = %s", $colname_Requests);
$Requests9= mysqli_query($PGCi, $query_Requests9 )  or die(mysqli_error($PGCi));
$row_Requests9 =mysqli_fetch_assoc($Requests9);
$totalRows_Requests9 = mysqli_num_rows($Requests9);
  
          $message = " " . $row_Requests9['member_name']. "\n\n" . " The CFIG Team modified your instruction request as indicated below." . "\n\n";
		  		  
		    
           $made_change = 'no'; 
		   
		   $B = 'orig_request_date';
           $A = 'request_date'; 
           If ($row_Requests9[$A] != $row_Requests9[$B] ) {
		   $new = $row_Requests9[$A];
   		   $old = $row_Requests9[$B];
		   	   
           $message = $message . " Request Date was changed TO ... " . trim($new) . " ... FROM ... " . trim($old) . "\n\n";
           $made_change = 'yes';
           } 
      
	  
           $B = 'orig_request_cfig';
           $A = 'request_cfig'; 
	       If ($row_Requests9[$A] != $row_Requests9[$B] ) {
		   $new = $row_Requests9[$A];
   		   $old = $row_Requests9[$B];
		   
		   
           $message = $message . " Request CFIG 1 was changed TO ... " . trim($new) . " ... FROM ... " . trim($old) . "\n\n";
           $made_change = 'yes';
           } 
    
		   $B = 'orig_request_cfig2';
           $A = 'request_cfig2'; 
		   If ($row_Requests9[$A] != $row_Requests9[$B] ) {
           $new = $row_Requests9[$A];
   		   $old = $row_Requests9[$B];
		   
		   
           $message = $message . " Request CFIG 2 was changed TO ... " . trim($new) . " ... FROM ... " . trim($old) . "\n\n";
           $made_change = 'yes';
           } 
		   
		   $B = 'orig_accept_cfig';
           $A = 'accept_cfig'; 
		   If ($row_Requests9[$A] != $row_Requests9[$B] ) {
           $new = $row_Requests9[$A];
   		   $old = $row_Requests9[$B];
		   

           $message = $message . " Assigned CFIG was changed TO ... " . trim($new) . " ... FROM ... " . trim($old) . "\n\n";
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
		

		$message = $message . " This record was updated by ... " . $session_pilotname . "\n\n\n";
		
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
	    $ToList = $row_Requests9['member_id'] ;
		
		if (trim($row_Requests9['cfig1_email']) != '') {
		$ToList = $ToList . "," . $row_Requests9['cfig1_email'];
				}
		if (trim($row_Requests9['cfig2_email']) != '') {
		$ToList = $ToList . "," . $row_Requests9['cfig2_email'];
				}
				

		$ToList = $ToList . "," . $webmaster;	
		$message = $message ."Email List:  " . $ToList . "\n\n";
		
		
		/* End - Create Email List */
	   
		
		$to = $ToList;
		if ($row_System['sys_status'] == 'test') {
				$to = "ventusdriver@gmail.com, support@pgcsoaring.org";
		}
		    
		$subject = "PGC Instruction Request - Modified by CFIG Team";
				
//	    $email = $_REQUEST['email'];
				
		$headers = "From: PGC Pilot Data Portal";
//		$headers = "From: ventusdriver@gmail.com";
		$headers = "From: PGC-Instruction@noreply.com";
		
	   If ($made_change == 'yes') {
		  $sent = mail($to, $subject, $message, $headers) ; }

		  /*  END EMAIL */
	   
  
    $insertGoTo = $_SESSION['last_cfig_r_query'];

  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
//  header(sprintf("Location: %s", $insertGoTo));

}
 
?>
<?php 
//$colname_Requests = "-1";
if (isset($_GET['request_id'])) {
  $colname_Requests = $_GET['request_id'];
}
//mysql_select_db($database_PGC, $PGC);
$query_Requests = sprintf("SELECT request_key, entry_date, member_id, member_name, member_weight, request_date, request_time, request_type, request_cfig, request_cfig2, request_notes, accept_cfig, accept_date, accept_notes, record_deleted, Date_format(request_date,'%%W, %%M %%e') as mydate FROM pgc_request WHERE request_key = %s", $colname_Requests);
$Requests = mysqli_query($PGCi, $query_Requests )  or die(mysqli_error($PGCi));
$row_Requests =mysqli_fetch_assoc($Requests);
$totalRows_Requests = mysqli_num_rows($Requests);

//mysql_select_db($database_PGC, $PGC);
$query_Instructors = "SELECT Name FROM pgc_instructors WHERE cfig = 'Y' ORDER BY Name ASC";
$Instructors = mysqli_query($PGCi, $query_Instructors )  or die(mysqli_error($PGCi));
$row_Instructors =mysqli_fetch_assoc($Instructors);
$totalRows_Instructors = mysqli_num_rows($Instructors);

//mysql_select_db($database_PGC, $PGC);
$query_DutyDates = "SELECT date, Date_format(date,'%W, %M %e') as mydate FROM pgc_field_duty WHERE `date` >=CURDATE() ORDER BY `date` ASC LIMIT 4";

//$query_DutyDates = "SELECT date, Date_format(date,'%W, %M %e') as mydate FROM pgc_field_duty WHERE fd_type <> 'midweek' AND `date` >=CURDATE() ORDER BY `date` ASC LIMIT 4";
$DutyDates = mysqli_query($PGCi, $query_DutyDates )  or die(mysqli_error($PGCi));
$row_DutyDates =mysqli_fetch_assoc($DutyDates);
$totalRows_DutyDates = mysqli_num_rows($DutyDates);

//mysql_select_db($database_PGC, $PGC);
//$query_System = "SELECT * FROM pgc_system";
//$System = mysqli_query($PGCi, $query_System )  or die(mysqli_error($PGCi));
//$row_System =mysqli_fetch_assoc($System);
//$totalRows_System = mysqli_num_rows($System);

//mysql_select_db($database_PGC, $PGC);
$query_InstructionTypes = "SELECT * FROM pgc_instruction_types ORDER BY Instruction_type ASC";
$InstructionTypes = mysqli_query($PGCi, $query_InstructionTypes )  or die(mysqli_error($PGCi));
$row_InstructionTypes =mysqli_fetch_assoc($InstructionTypes);
$totalRows_InstructionTypes = mysqli_num_rows($InstructionTypes);
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
.style31 {color: #000000}
.style33 {
	font-size: 16px;
	color: #F0F0F0;
}
.style35 {color: #000000; font-weight: bold; }
.style42 {color: #000000; font-weight: bold; font-size: 14; }
.style43 {color: #FFFFFF}
.style44 {color: #FFFFFF; font-weight: bold; font-size: 14; }
-->
</style>
</head>
<body>
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#595E80">
  <tr>
    <td width="935" align="center" bgcolor="#666666"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="398"><table width="92%" height="542" align="center" cellpadding="4" cellspacing="3" >
        <tr>
          <td width="748" height="27" bgcolor="#1E3180"><div align="center" class="style2">
              <table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                      <td><div align="center" class="style33">CFIG SUPER  EDIT - CHANGE ANY FIELD </div></td>
                </tr>
                </table>
            </div></td>
        </tr>
        <tr>
          <td height="470" align="center" valign="top" bgcolor="#424A66">&nbsp;
   
                     <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
       	    	     <input type="hidden" name="action" value="cb_pdp_training_request">
       	    	     <input type="hidden" name="page" value="modify_cfig">
       	    	     <input type="hidden" name="request_id" value="<?php echo $row_Requests['request_key']; ?>">     
<!-- 
       	    	     <input type="submit" value="<?php echo $row_Requests['request_key']; ?>">	 
    		         </form>    
 -->

<!-- 
            <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
 -->
              <table width="500" align="center" cellpadding="2" cellspacing="2" bgcolor="#999999">
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style31"><strong>Key:</strong></div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style16"><span class="style42"><?php echo $row_Requests['request_key']; ?></span></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">Entry Date:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style42"><?php echo $row_Requests['entry_date']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">Member E-mail:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style42"><?php echo $row_Requests['member_id']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">Member Name:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style44"><?php echo $row_Requests['member_name']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">Date Requested:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style42 style43"><select name="date1" id="date1">
                    <?php
do {  
?>
                    <option value="<?php echo $row_DutyDates['date']?>"<?php if (!(strcmp($row_DutyDates['date'], $row_Requests['request_date']))) {echo "selected=\"selected\"";} ?>><?php echo $row_DutyDates['mydate']?></option>
                      <?php
} while ($row_DutyDates =mysqli_fetch_assoc($DutyDates));
  $rows = mysqli_num_rows($DutyDates);
  if($rows > 0) {
      mysqli_data_seek($DutyDates, 0);
	  $row_DutyDates =mysqli_fetch_assoc($DutyDates);
  }
?>
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">Request Type:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style44"><select name="request_type" id="request_type">
                    <?php
do {  
?>
                    <option value="<?php echo $row_InstructionTypes['Instruction_type']?>"<?php if (!(strcmp($row_InstructionTypes['Instruction_type'], $row_Requests['request_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_InstructionTypes['Instruction_type']?></option>
                    <?php
} while ($row_InstructionTypes =mysqli_fetch_assoc($InstructionTypes));
  $rows = mysqli_num_rows($InstructionTypes);
  if($rows > 0) {
      mysqli_data_seek($InstructionTypes, 0);
	  $row_InstructionTypes =mysqli_fetch_assoc($InstructionTypes);
  }
?>
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left"><strong><span class="style31">Member Weight:</span></strong></div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style44"><input name="request_weight" type="text" id="request_weight" value="<?php echo $row_Requests['member_weight']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left"><strong><span class="style31">Request Notes:</span></strong></div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style44"><input type="text" name="request_notes" value="<?php echo $row_Requests['request_notes']; ?>" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap="nowrap" bgcolor="#6666FF" class="style16"><div align="left" class="style35">CFIG 1 Requested:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style44"><select name="request_cfig">
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
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap="nowrap" bgcolor="#6666FF" class="style16"><div align="left" class="style35">CFIG 2 Requested:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style44"><select name="request_cfig2" id="request_cfig2">
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
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">CFIG Assigned:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style16"><select name="accept_cfig" id="accept_cfig">
                      <?php
do {  
?>
                      <option value="<?php echo $row_Instructors['Name']?>"<?php if (!(strcmp($row_Instructors['Name'], $row_Requests['accept_cfig']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Instructors['Name']?></option>
                      <?php
} while ($row_Instructors =mysqli_fetch_assoc($Instructors));
  $rows = mysqli_num_rows($Instructors);
  if($rows > 0) {
      mysqli_data_seek($Instructors, 0);
	  $row_Instructors =mysqli_fetch_assoc($Instructors);
  }
?>
                    </select>                  </td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">CFIG Note:</div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style16"><input type="text" name="accept_notes" value="<?php echo $row_Requests['accept_notes']; ?>" size="32"></td>
                </tr>
                <tr valign="baseline">
                  <td align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="left" class="style35">Delete Record ?: </div></td>
                  <td height="25" valign="middle" bgcolor="#6666FF" class="style16"><select name="record_deleted" id="record_deleted">
                      <option value="N" <?php if (!(strcmp("N", $row_Requests['record_deleted']))) {echo "selected=\"selected\"";} ?>>N</option>
                      <option value="Y" <?php if (!(strcmp("Y", $row_Requests['record_deleted']))) {echo "selected=\"selected\"";} ?>>Y</option>
                    </select>                  </td>
                </tr>
                <tr valign="baseline">
                  <td height="25" colspan="2" align="right" valign="middle" nowrap bgcolor="#6666FF" class="style16"><div align="center">
                      <input type="submit" value="Update record">
                    </div></td>
                </tr>
              </table>
              <input type="hidden" name="MM_update" value="form1">
              <input type="hidden" name="request_key" value="<?php echo $row_Requests['request_key']; ?>">
            </form>
            <p>&nbsp;</p></td>
        </tr>
        <tr>
          <td height="30" bgcolor="#4F5359" class="style16"><div align="center">
                      <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
       	    	     <input type="hidden" name="action" value="cb_pdp_training_request">
       	    	     <input type="hidden" name="page" value="list_cfig">       	    	   
       	    	     <input type="submit" value="Instructors Portal">	 
    		         </form>               
          
 <!-- 
         <a href=<?PHP echo $_SESSION['PDP_HOME']; ?>>BACK TO MEMBERS PAGE </a>
 -->
          
          </div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Instructors);

mysqli_free_result($DutyDates);

mysqli_free_result($System);

mysqli_free_result($InstructionTypes);

mysqli_free_result($Requests);
?>
