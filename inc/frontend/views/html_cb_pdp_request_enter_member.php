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
// require_once('pgc_check_login.php'); 
?>
<?php
//$MM_restrictGoTo = "../Index.html";
// if ( !(isset($_SESSION['MM_Username']) ))  {   
//     header("Location: ". $MM_restrictGoTo); 
//   exit;
//  }
// $session_pilotname = $_SESSION['MM_PilotName'];
// $session_email = $_SESSION['MM_Username']; 
//if ( trim($session_pilotname = '') )  {   
//    header("Location: ". $MM_restrictGoTo); 
//  exit;
// }
 
$current_pilot = wp_get_current_user(); 
$pilotname = $current_pilot->user_lastname . ", " .  $current_pilot->user_firstname;
$pilot_email =  $current_pilot->user_email;  
 
// select instructors from Wordpress user database where role = 'cfi_g'
$row_Cfigpilots = array();
$args = array('role'=> 'cfi_g', 'role__not_in'=>'inactive', 'orderby'=>'user_nice_name', 'order'=> 'ASC');
$cfi_pilots = get_users($args );

foreach($cfi_pilots as $pilot ){
	$pilot_user = get_userdata( $pilot->id );
	array_push ($row_Cfigpilots, ($pilot_user->last_name .', '. $pilot_user->first_name ));	
}
sort($row_Cfigpilots ); 
 
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
    case "onechar":
      $theValue = ($theValue != "NO") ? "'Y'": "'N'" ;
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


	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	
	/*** Check to See If CFIG1 is Off Duty ***/
    $insertSQL = sprintf("SELECT * FROM pgc_cfig_dates WHERE cfig_name = %s AND duty_date = %s AND cfig_vacation = 'Y'" ,  

						  GetSQLValueString($_POST['request_cfig'], "text"),
					      GetSQLValueString($_POST['date1'], "date"));
	
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
					      GetSQLValueString($_POST['date1'], "date"));
	
	  //mysql_select_db($database_PGC, $PGC);
	  $Result1 = mysqli_query($PGCi, $insertSQL )  or die(mysqli_error($PGCi));
	  $row_Result1 =mysqli_fetch_assoc($Result1);
	  $totalRows_Result1 = mysqli_num_rows($Result1);
	  $OffDutyCFIG2 = 'Y';
	  If ($totalRows_Result1  ==  0) {
	      $OffDutyCFIG2 = 'N';
	  }
	  
/* Regular requets - CFIGs have to be on duty ... Confirmed can be off duty */

If (($OffDutyCFIG1 == 'N' AND $OffDutyCFIG2 == 'N' ) OR ($_POST['cfig_confirmed'] == 'YES') ) {	  
  /* UPDATE~RECORD */
	$insertSQL = sprintf("INSERT INTO pgc_request (member_name, member_id, request_date, request_time, request_type, member_weight, request_cfig, request_cfig2, request_notes, cfig_confirmed, sched_assist) 
	VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
//						   GetSQLValueString($_SESSION['MM_PilotName'], "text"),
						   GetSQLValueString($pilotname, "text"),
						   GetSQLValueString($pilot_email, "text"),
						   GetSQLValueString($_POST['date1'], "date"),
						   'NULL',
						   GetSQLValueString($_POST['request_type'], "text"),
						   GetSQLValueString($_POST['request_weight'], "text"),						   
						   GetSQLValueString($_POST['request_cfig'], "text"),
						   GetSQLValueString($_POST['request_cfig2'], "text"),
						   GetSQLValueString($_POST['request_notes'], "text"),
						   GetSQLValueString($_POST['cfig_confirmed'], "text"),
						   GetSQLValueString($_POST['sar'], "onechar")
						   );	

	  $Result1 = mysqli_query($PGCi, $insertSQL )  or die(mysqli_error($PGCi));

 	  $id = mysqli_insert_id($PGCi); 
	
	  /*   Member Confirmed Entry  */  
	  If ($_POST['cfig_confirmed'] == 'YES' AND $_POST['request_cfig'] <> '') {	
	  		  
	  $updateSQL = sprintf( "UPDATE pgc_request A SET A.accept_cfig = A.request_cfig, A.request_cfig2 = '', A.accept_notes = 'Pre-Confirmed' WHERE A.request_key=%s",        
                       GetSQLValueString($id, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  }	  
	  
	  /*   Update E-Mail IDs                 */
	  /* Depends on CFIG_DATES Updates in List Member ***/
	   /*** Enter Email for CFIG1 ***/
	 
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.cfig1_email = B.USER_ID
      WHERE A.request_cfig = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($id, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	 
	  /*** Blank Email for Off Duty CFIG1 ***/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_cfig_dates B SET A.cfig1_email = ''
      WHERE A.request_cfig = B.cfig_name AND A.request_date = B.duty_date AND B.cfig_vacation = 'Y' AND A.request_key=%s",        
                       GetSQLValueString($id, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi)); 
	  
	  /*** Enter Email for CFIG2 ***/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_members B SET A.cfig2_email = B.USER_ID
      WHERE A.request_cfig2 = B.NAME AND A.request_key=%s",        
                       GetSQLValueString($id, "int"));
      //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  	  /*** Blank Email for Off Duty CFIG2 ***/
	  
	  $updateSQL = sprintf( "UPDATE pgc_request A, pgc_cfig_dates B SET A.cfig2_email = ''
      WHERE A.request_cfig2 = B.cfig_name AND A.request_date = B.duty_date AND B.cfig_vacation = 'Y' AND A.request_key=%s",        
                       GetSQLValueString($id, "int"));
       //mysql_select_db($database_PGC, $PGC);
      $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
	  
	  /*  Save Original Values */
			  $updateSQL = sprintf("UPDATE pgc_request SET orig_request_date = request_date, orig_request_cfig =    request_cfig, orig_request_cfig2 = request_cfig2, orig_cfig1_email = cfig1_email, orig_cfig2_email = cfig2_email, orig_assign_cfig_email = assign_cfig_email, orig_accept_cfig = accept_cfig WHERE request_key=%s",
              GetSQLValueString($id, "int"));
			  //mysql_select_db($database_PGC, $PGC);
              $Result2 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
              If ($Result2 != false ){
	//		  	$row_Result2 =mysqli_fetch_assoc($Result2);
	//		  	$totalRows_Result2 = mysqli_num_rows( $Result2);
			  }
         /*  Send Email */
	 $colname_Requests = "-1";
if (isset($_GET['request_id'])) {
  $colname_Requests = $_GET['request_id'];
}

 //mysql_select_db($database_PGC, $PGC);
$query_Requests9 = sprintf("SELECT * FROM pgc_request WHERE request_key = %s", $id);
$Requests9= mysqli_query($PGCi, $query_Requests9 )  or die(mysqli_error($PGCi));
$row_Requests9 =mysqli_fetch_assoc($Requests9);
$totalRows_Requests9 = mysqli_num_rows($Requests9);

        $message = " " . $row_Requests9['member_name'] . "\n\n" . " Your instruction request was entered as indicated below." . "\n\n";
		$made_change = 'yes';   
		
      /*   Member Confirmed Entry  */  
	  If ($_POST['cfig_confirmed'] == 'YES' AND $_POST['request_cfig'] <> '') {	
        $message = " " . $row_Requests9['member_name'] . "\n\n" . " Your CONFIRMED instruction request was entered as indicated below." . "\n\n";
	  }

   
		$message = $message . "Member - New Instruction Request" . "\n";
		
		
		$message = $message . "=========================" . "\n";
		$message = $message ."Request Number:     " . $row_Requests9['request_key'] . "\n";
		$message = $message ."Member Name:        " . $row_Requests9['member_name'] . "\n";
		$message = $message ."Date Requested:     " . $row_Requests9['request_date'] . "\n";
		$message = $message ."Request Type:       " . $row_Requests9['request_type'] . "\n";
		$message = $message ."Scheduling Assist:  " . $row_Requests9['sched_assist'] . "\n";
		$message = $message ."Request Notes:      " . $row_Requests9['request_notes'] . "\n";
		$message = $message ."Member Weight:      " . $row_Requests9['member_weight'] . "\n";
		$message = $message ."CFIG 1 Requested:   " . $row_Requests9['request_cfig'] . "\n";
		$message = $message ."CFIG 2 Requested:   " . $row_Requests9['request_cfig2'] . "\n";
		$message = $message ."CFIG Assigned:      " . $row_Requests9['accept_cfig'] . "\n";
		$message = $message ."CFIG Notes:         " . $row_Requests9['accept_notes'] . "\n";
		$message = $message ."Record Deleted?:    " . $row_Requests9['record_deleted'] . "\n\n";
		

		$message = $message . "This record was entered by ... " . $pilotname . "\n\n\n";
		
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
// 		if ($row_System['sys_status'] == 'test') {
// 				$to = "ventusdriver@gmail.com, support@pgcsoaring.org";
// 		}
		
		
		$subject = "PGC Instruction Request - New - Entered by Member";
				
// 	    $email = $_REQUEST['email'];
				
		$headers = "From: PGC Pilot Data Portal";
		$headers = "From: PGC-Instruction@noreply.com";
		
	   If ($made_change == 'yes') {
		  $sent = mail($to, $subject, $message, $headers) ; }

		  /*  END EMAIL */		
		
				   
					   
	  $_SESSION['MM_S_Message'] = "Record Saved - Enter Additional Or Exit";
//tag	  
  $updateGoTo = $_SESSION['last_rm_query'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
   $_SESSION['MM_S_Message'] =  "Modify record or save with no changes";
  
 } else  {
	 If ($OffDutyCFIG1 == 'Y') {
	 $_SESSION['MM_S_Message'] =  "CFIG1 is off duty for this date - select again";
	 }
 	 If ($OffDutyCFIG2 == 'Y') {
	 $_SESSION['MM_S_Message'] =  "CFIG2 is off duty for this date - select again";
	 }
	 If ($OffDutyCFIG1 == 'Y' AND $OffDutyCFIG2 == 'Y' ) {
	 $_SESSION['MM_S_Message'] =  "Both your CFIGs are off duty for this date - select again";
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

$maxRows_Requests = 10;
$pageNum_Requests = 0;
if (isset($_GET['pageNum_Requests'])) {
  $pageNum_Requests = $_GET['pageNum_Requests'];
}
$startRow_Requests = $pageNum_Requests * $maxRows_Requests;

//mysql_select_db($database_PGC, $PGC);
$query_Requests = "SELECT * FROM pgc_request ORDER BY request_date ASC";
$query_limit_Requests = sprintf("%s LIMIT %d, %d", $query_Requests, $startRow_Requests, $maxRows_Requests);
$Requests = mysqli_query($PGCi, $query_limit_Requests )  or die(mysqli_error($PGCi));
$row_Requests =mysqli_fetch_assoc($Requests);

if (isset($_GET['totalRows_Requests'])) {
  $totalRows_Requests = $_GET['totalRows_Requests'];
} else {
  $all_Requests = mysqli_query($PGCi, $query_Requests ) ;
  $totalRows_Requests = mysqli_num_rows($all_Requests);
}
$totalPages_Requests = ceil($totalRows_Requests/$maxRows_Requests)-1;

//mysql_select_db($database_PGC, $PGC);
$query_Instructors = "SELECT * FROM pgc_instructors WHERE cfig = 'Y' AND rec_active = 'Y' ORDER BY Name ASC";
$Instructors = mysqli_query($PGCi, $query_Instructors )  or die(mysqli_error($PGCi));
$row_Instructors =mysqli_fetch_assoc($Instructors);
$totalRows_Instructors = mysqli_num_rows($Instructors);

//mysql_select_db($database_PGC, $PGC);
// NTFS in april 2021 when setting up the field duty dates, 'fd_type <> 'midweek' caused the following statement
// to NOT match anything. Not using midweek anymore so just took it out. 
// I suspect an update to MySQL may have caused this problem. Not sure..
// $query_DutyDates = "SELECT date, Date_format(date,'%W, %M %e') as mydate FROM pgc_field_duty WHERE fd_type <> 'midweek' AND `date` >=CURDATE() AND `date` <= (CURDATE() + Interval 6 Day) ORDER BY `date` ASC LIMIT 4";
$query_DutyDates = "SELECT date, Date_format(date,'%W, %M %e') as mydate FROM pgc_field_duty WHERE `date` >=CURDATE() AND `date` <= (CURDATE() + Interval 6 Day) ORDER BY `date` ASC LIMIT 4";


$DutyDates = mysqli_query($PGCi, $query_DutyDates )  or die(mysqli_error($PGCi));
$row_DutyDates =mysqli_fetch_assoc($DutyDates);
$totalRows_DutyDates = mysqli_num_rows($DutyDates);

//mysql_select_db($database_PGC, $PGC);
$query_System = "SELECT * FROM pgc_system";
$System = mysqli_query($PGCi, $query_System )  or die(mysqli_error($PGCi));
$row_System = mysqli_fetch_assoc($System);
$totalRows_System = mysqli_num_rows($System);

$maxRows_ServerTime = 1;
$pageNum_ServerTime = 0;
if (isset($_GET['pageNum_ServerTime'])) {
  $pageNum_ServerTime = $_GET['pageNum_ServerTime'];
}
$startRow_ServerTime = $pageNum_ServerTime * $maxRows_ServerTime;

//mysql_select_db($database_PGC, $PGC);
$query_ServerTime = "SELECT DISTINCT NOW() FROM pgc_field_duty";
$query_limit_ServerTime = sprintf("%s LIMIT %d, %d", $query_ServerTime, $startRow_ServerTime, $maxRows_ServerTime);
$ServerTime = mysqli_query($PGCi, $query_limit_ServerTime )  or die(mysqli_error($PGCi));
$row_ServerTime =mysqli_fetch_assoc($ServerTime);

if (isset($_GET['totalRows_ServerTime'])) {
  $totalRows_ServerTime = $_GET['totalRows_ServerTime'];
} else {
  $all_ServerTime = mysqli_query($PGCi, $query_ServerTime ) ;
  $totalRows_ServerTime = mysqli_num_rows($all_ServerTime);
}
$totalPages_ServerTime = ceil($totalRows_ServerTime/$maxRows_ServerTime)-1;

//mysql_select_db($database_PGC, $PGC);
$query_Instruction_types = "SELECT * FROM pgc_instruction_types ORDER BY Instruction_type ASC";
$Instruction_types = mysqli_query($PGCi, $query_Instruction_types )  or die(mysqli_error($PGCi));
$row_Instruction_types =mysqli_fetch_assoc($Instruction_types);
$totalRows_Instruction_types = mysqli_num_rows($Instruction_types);
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
.style25 {font-weight: bold; color: #A7B5CE;}
.style31 {color: #000000}
.style32 {font-weight: bold; color: #000000; }
.style33 {
	font-size: 14px;
	color: #CCC;
}
.style36 {font-weight: bold; color: #A7B5CE; font-size: 14; }
.style37 {
	color: #F8BD6D;
	font-weight: bold;
	font-style: italic;
}
.style38 {font-weight: bold; color: #6666FF; font-size: 14px; }
.style39 {color: #BAB3FF}
.style41 {font-size: 18px}
.style43 {font-size: 16px; }
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
            <td height="398"><table width="92%" height="481" align="center" cellpadding="4" cellspacing="3" >
                        <tr>
                              <td width="837" height="40" bgcolor="#4F5359"><div align="center" class="style2">
                                          <table width="60%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                      <td><div align="center" class="style33">PGC STUDENT INSTRUCTION REQUEST</div></td>
                                                </tr>
                                          </table>
                                    </div></td>
                        </tr>
                        <tr>
                              <td height="277" align="center" valign="top" bgcolor="#424A66"><form action="<?php echo $editFormAction; ?>" method="post" name="new_flight" id="new_flight">
                                      <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                      <td height="54" valign="middle" class="style37"><div align="center">*** You can now enter a 'Confirmed Request' if you discuss with a CFIG before entering. Your Requested CFIG 1 selection will become the Assigned CFIG automatically ... your  CFIG will not have to do anything to 'accept' the request. *** </div></td>
                                              </tr>
                                      </table>
                                      <table width="500" align="center" cellpadding="5" cellspacing="2" bgcolor="#666666">
                                                <tr valign="baseline">
                                                      <td height="48" colspan="2" align="center" valign="middle" nowrap bgcolor="#6666FF" class="style25"><table width="380" border="0" cellpadding="2" cellspacing="2" bgcolor="#6666FF">
                                                                  <tr>
                                                                        <td width="214" height="30" valign="middle" bgcolor="#6666FF" class="style32"><div align="center" class="style43"><?php echo $pilotname; ?></div></td>
                                                                        <td width="214" valign="middle" bgcolor="#6666FF" class="style32"><div align="center" class="style43"><?php echo $pilot_email; ?></div></td>
                                                                  </tr>
                                                            </table></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td width="191" height="1" align="right" valign="middle" nowrap bgcolor="#6666FF" class="style25"><div align="left" class="style31">INSTRUCTION DATE: </div></td>
                                                      <td width="254" height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <select name="date1" id="date1">
                                                                        <?php
do {  
?>
                                                                        <option value="<?php echo $row_DutyDates['date']?>"><?php echo $row_DutyDates['mydate']?></option>
                                                                        <?php
} while ($row_DutyDates =mysqli_fetch_assoc($DutyDates));
  $rows = mysqli_num_rows($DutyDates);
  if($rows > 0) {
      mysqli_data_seek($DutyDates, 0);
	  $row_DutyDates =mysqli_fetch_assoc($DutyDates);
  }
?>
                                                                  </select>
                                                            </div></td>
                                                </tr>
                                                <tr valign="middle" bgcolor="#6666FF">
                                                      <td height="1" align="right" nowrap="nowrap"><div align="left" class="style32">INSTRUCTION TYPE: </div></td>
                                                      <td height="1"><div align="left">
                                                                  <select name="request_type" id="request_type">
                                                                    <?php
do {  
?>
                                                                    <option value="<?php echo $row_Instruction_types['Instruction_type']?>"><?php echo $row_Instruction_types['Instruction_type']?></option>
                                                                    <?php
} while ($row_Instruction_types =mysqli_fetch_assoc($Instruction_types));
  $rows = mysqli_num_rows($Instruction_types);
  if($rows > 0) {
      mysqli_data_seek($Instruction_types, 0);
	  $row_Instruction_types =mysqli_fetch_assoc($Instruction_types);
  }
?>
                                                        </select>
                                                            </div></td>
                                                </tr>
                                                <tr valign="middle" bgcolor="#6666FF">
                                                      <td height="1" align="right" nowrap="nowrap"><div align="left"><span class="style32">MEMBER WEIGHT: </span></div></td>
                                                      <td height="1"><div align="left">
                                                                  <input name="request_weight" type="text" id="request_weight" value="100" size="3" maxlength="3" />
                                                            </div></td>
                                                </tr>
                                                <tr valign="middle" bgcolor="#6666FF">
                                                      <td height="1" align="center" nowrap="nowrap"><div align="left" class="style32">REQUEST NOTES (30 Chars Max): </div></td>
                                                      <td height="1"><div align="left">
                                                                  <input name="request_notes" type="text" size="35" maxlength="30" />
                                                      </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap bgcolor="#6666FF" class="style25"><div align="left" class="style31">REQUESTED CFIG 1: </div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <select name="request_cfig" id="request_cfig">
                                                                       <option value="" selected>Select</option>' 
                                                                        <?php 
											                           foreach($row_Cfigpilots as $pilot ){
											                  			    if ( $pilot == $row_Flightlog['Pilot2'] ) { 
											                  			    	echo(' <option value="'.$pilot.'" selected>'.$pilot.'</option>');  
											                  			    } else {
											                  					echo(' <option value="'.$pilot.'" >'.$pilot.'</option>');    
											                  				}                   
											                  			}               
																	?>
                                                                  </select>
                                                            </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="1" align="right" valign="middle" nowrap="nowrap" bgcolor="#6666FF" class="style25"><div align="left" class="style31">REQUESTED CFIG 2: </div></td>
                                                      <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                                  <select name="request_cfig2" id="request_cfig2">
                                                                        <?php
do {  
?>
                                                                        <option value="<?php echo $row_Instructors['Name']?>"><?php echo $row_Instructors['Name']?></option>
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
                                                  <td height="1" align="right" valign="middle" nowrap="nowrap" bgcolor="#6666FF" class="style25"><div align="left"><span class="style45">CONFIRMED WITH  CFIG 1 ?</span></div></td>
                                                        <td height="1" valign="middle" bgcolor="#6666FF"><div align="left">
                                                          <select name="cfig_confirmed" id="cfig_confirmed">
                                                            <option value="NO" selected="selected">NO</option>
                                                            <option value="YES">YES</option>
                                                          </select>
                                                        </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                  <td height="32" align="right" valign="middle" nowrap="nowrap" bgcolor="#6666FF" class="style25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td><div align="left"><span class="style45">SCHEDULING ASSISTANCE</span></div></td>
                                                    </tr>
                                                    <tr>
                                                      <td><div align="left"><span class="style45">REQUESTED ?</span></div></td>
                                                    </tr>
                                                  </table></td>
                                                  <td height="32" valign="middle" bgcolor="#6666FF"><div align="left">
                                                      <select name="sar" id="sar">
                                                        <option value="NO" selected="selected">NO</option>
                                                        <option value="YES">YES</option>
                                                      </select>
                                                  </div></td>
                                                </tr>
                                                <tr valign="baseline">
                                                      <td height="32" colspan="2" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="center">
                                                                  <input type="submit" value="SAVE"> "Press only once!
                                                            </div></td>
                                                </tr>
                                                
                                                <tr valign="baseline">
                                                        <td height="28" colspan="2" align="right" valign="middle" nowrap bgcolor="#6666FF"><div align="center"><?php echo $row_ServerTime['NOW()']; ?></div>                                                                </td>
                                                </tr>
                                </table>
                                          <p class="style36">
                                            <input type="hidden" name="MM_insert" value="form1" />
                                </p>
                                          <p class="style36">&nbsp;</p>
                              </form>
                                    <p class="style38 style39"><span class="style41"><?php echo $_SESSION['MM_S_Message']; ?></span>&nbsp;</p>
                                    </p></td>
                        </tr>
                        <tr>
                              <td height="30" bgcolor="#4F5359" class="style16"><div align="center"><a  href=<?php echo  $_SERVER['HTTP_REFERER']  ?>  class="style17">BACK TO MEMBER REQUEST LIST </a></div></td>
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

mysqli_free_result($ServerTime);

mysqli_free_result($Instruction_types);

mysqli_free_result($Requests);
?>
