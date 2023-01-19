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

$date1=date("Y-m-d");
$date2=date("Y-m-d");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$date_limit = date('Y-m-d', strtotime("+10 days"));
$todays_date = date('Y-m-d', strtotime("0 days"));

/*if (($_POST['date1'] <= $date_limit) && ($_POST['date1'] >= $todays_date)) { */

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO pgc_cfig_vacation (cfig_name, saturday, sunday, monday, tuesday, wednesday, thursday, friday, vac_start, vac_end) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cfig_name'], "text"),
					   GetSQLValueString($_POST['v_saturday'], "text"),
					   GetSQLValueString($_POST['v_sunday'], "text"),
					   GetSQLValueString($_POST['v_monday'], "text"),
					    GetSQLValueString($_POST['v_tuesday'], "text"),
						 GetSQLValueString($_POST['v_wednesday'], "text"),
						  GetSQLValueString($_POST['v_thursday'], "text"),
						   GetSQLValueString($_POST['v_friday'], "text"),
                       GetSQLValueString($_POST['date1'], "date"),
                       GetSQLValueString($_POST['date2'], "date"));

  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $insertSQL )  or die(mysqli_error($PGCi));
  $_SESSION['MM_S_Message'] = "Record Saved - Enter Additional Or Exit";
  
   /* Order by Length of Vacation - Start New  */

$runSQL = "UPDATE pgc_cfig_vacation SET alldays = ''"; 
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

/********/
 
$runSQL = "UPDATE pgc_cfig_vacation SET vdays = DATEDIFF(vac_end,vac_start)+1"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));
 

/****   End New   ****/
  
/*} */
} else {
	if (isset($_POST['date1'])) {
	$_SESSION['MM_S_Message'] = "Last Date Entry Out of Date Range - Correct and Reenter";
	} else {
	$_SESSION['MM_S_Message'] = "Enter Off-Duty Dates";
	}

} 

//mysql_select_db($database_PGC, $PGC);
$query_Vacation = "SELECT * FROM pgc_cfig_vacation";
$Vacation = mysqli_query($PGCi, $query_Vacation )  or die(mysqli_error($PGCi));
$row_Vacation =mysqli_fetch_assoc($Vacation);
$totalRows_Vacation = mysqli_num_rows($Vacation);

//mysql_select_db($database_PGC, $PGC);
$query_Instructors = "SELECT * FROM pgc_instructors ORDER BY Name ASC";
$Instructors = mysqli_query($PGCi, $query_Instructors )  or die(mysqli_error($PGCi));
$row_Instructors =mysqli_fetch_assoc($Instructors);
$totalRows_Instructors = mysqli_num_rows($Instructors);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<script src="<?php echo plugins_url('/cb-pdp_schedule/inc/frontend/js/javascripts.js') ?>";></script>
<script src="<?php echo plugins_url('/cb-pdp_schedule/inc/frontend/js/CalendarPopup.js') ?>";></script>
<script src="<?php echo plugins_url('/cb-pdp_schedule/inc/frontend/js/zxml.js') ?>";></script>
<script src="<?php echo plugins_url('/cb-pdp_schedule/inc/frontend/js/workingjs.js') ?>";></script>

<!-- 
<script src="../js/javascripts.js" type="text/javascript"></script>
<script src="../js/CalendarPopup.js" type="text/javascript"></script>
<script src="../js/zxml.js" type="text/javascript"></script>

<script src="../js/workingjs.js" type="text/javascript"></script>
 -->

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
	background-color: #333333;
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
	color: #000000;
	font-size: 16px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style32 {font-weight: bold; color: #000000; }
.style33 {font-size: 14px}
.style38 {font-weight: bold; color: #6666FF; font-size: 14px; }
.style39 {color: #BAB3FF}
.style41 {font-size: 18px}
.style43 {font-weight: bold; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; }
.style44 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style51 {font-weight: bold; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; }
.style52 {font-size: 14px; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
</head>
<body>
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#000033">
  <tr>
    <td align="center" bgcolor="#666666"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="398" bgcolor="#666666"><table width="900" height="465" align="center" cellpadding="4" cellspacing="3" bordercolor="#005B5B" bgcolor="#005B5B">
        <tr>
          <td width="1562" height="40" bgcolor="#4F5359"><div align="center" class="style2">
              <table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><div align="center" class="style52">PGC CFIG OFF-DUTY &amp; VACATION ENTRY </div></td>
                </tr>
              </table>
            </div></td>
        </tr>
        <tr>
          <td height="377" align="center" valign="top" bgcolor="#4F5359"><p class="style38 style39"><span class="style41"><?php echo $_SESSION['MM_S_Message']; ?></span>&nbsp;</p>
            
           <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" name="new_flight" id="new_flight">
           <input type="hidden" name="action" value="cb_pdp_training_request">
           <input type="hidden" name="page" value="enter_vacay">
           
           
<!-- 
            <form action="<?php echo $editFormAction; ?>" method="post" name="new_flight" id="new_flight">
 -->
              <table width="588" align="center" cellpadding="5" cellspacing="2" bgcolor="#6666CC">
                
                <tr valign="baseline">
                  <td height="25" align="right" valign="top" nowrap bgcolor="#6666FF"><table width="100%" height="268" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="100%" height="266" valign="top"><div align="center">
                        <table width="96%" align="center" cellpadding="0" cellspacing="0">
                              <tr valign="baseline">
                                <td width="42%" height="15" valign="middle" bgcolor="#6666FF"><div align="left"><span class="style43">INSTRUCTOR</span></div></td>
                                <td width="29%" valign="middle" bgcolor="#6666FF"><div align="left" class="style44"><span class="style32">START DATE</span></div></td>
                                <td width="29%" valign="middle" bgcolor="#6666FF"><div align="left"><span class="style43">END DATE</span></div></td>
                              </tr>
                              <tr valign="baseline">
                                <td height="30" valign="middle" bgcolor="#6666FF"><div align="left">
                                    <select name="cfig_name" id="cfig_name">
                                      <?php
										do {  
										?>
                                      <option value="<?php echo $row_Instructors['Name']?>"<?php if (!(strcmp($row_Instructors['Name'], ''))) {echo "selected=\"selected\"";} ?>><?php echo $row_Instructors['Name']?></option>
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
                                <td valign="middle" bgcolor="#6666FF"><div align="left">
                                    <input type="text" name="date1" size="10" value="<?php echo "$date1" ?>" />
                                <a href="#"
					   name="anchor2" class="style32" id="anchor2" onclick="cal.select(document.forms['new_flight'].date1,'anchor2','yyyy-MM-dd'); return false;"> Calendar</a></div></td>
                                <td valign="middle" bgcolor="#6666FF"><div align="left">
                                    <input type="text" name="date2" size="10" value="<?php echo "$date2" ?>" />
                                <a href="#"
					   name="anchor2" class="style32" id="anchor2" onclick="cal.select(document.forms['new_flight'].date2,'anchor2','yyyy-MM-dd'); return false;"> Calendar</a></div></td>
                              </tr>
                            </table>
                      </div>
                        <p align="center"><span class="style25 style33">For the period identified above ... are you off-duty on these days?</span></p>
                        <table width="68%" align="center" cellpadding="7" cellspacing="2">
                          
                          <tr>
                            <td width="26%" height="1" bgcolor="#5F5FC9" class="style25 style33"><div align="left">Saturday</div></td>
                            <td width="25%" height="1" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_saturday" id="v_saturday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Vacation['saturday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
								<option value="ON" <?php if (!(strcmp("ON", $row_Vacation['saturday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td width="25%" height="1" bgcolor="#5F5FC9"><div align="left"><span class="style51">Wednesday</span></div></td>
                            <td width="24%" height="1" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_wednesday" id="v_wednesday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Vacation['wednesday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
								<option value="ON" <?php if (!(strcmp("ON", $row_Vacation['wednesday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                          </tr>
                          <tr>
                            <td height="1" bgcolor="#5F5FC9"><div align="center" class="style33">
                              <div align="left"><span class="style43">Sunday</span></div>
                            </div></td>
                            <td height="1" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_sunday" id="v_sunday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Vacation['sunday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
							<option value="ON" <?php if (!(strcmp("ON", $row_Vacation['sunday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td height="1" bgcolor="#5F5FC9"><div align="left"><span class="style51">Thursday</span></div></td>
                            <td height="1" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_thursday" id="v_thursday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Vacation['thursday']))) {echo "selected=\"selected\"";} ?>>OFF</option><option value="ON" <?php if (!(strcmp("ON", $row_Vacation['thursday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                          </tr>
                          <tr>
                            <td height="1" bgcolor="#5F5FC9"><div align="center" class="style33">
                              <div align="left"><span class="style43">Monday</span></div>
                            </div></td>
                            <td height="1" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_monday" id="v_monday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Vacation['monday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
							<option value="ON" <?php if (!(strcmp("ON", $row_Vacation['monday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td height="1" bgcolor="#5F5FC9"><div align="left"><span class="style51">Friday</span></div></td>
                            <td height="1" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_friday" id="v_friday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Vacation['friday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
                                  <option value="ON" <?php if (!(strcmp("ON", $row_Vacation['friday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                          </tr>
                          <tr>
                            <td height="1" bgcolor="#5F5FC9"><div align="center" class="style33">
                              <div align="left"><span class="style43">Tuesday</span></div>
                            </div></td>
                            <td height="1" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_tuesday" id="v_tuesday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Vacation['tuesday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
								<option value="ON" <?php if (!(strcmp("ON", $row_Vacation['tuesday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td height="1" bgcolor="#5F5FC9">&nbsp;</td>
                            <td height="1" bgcolor="#5F5FC9">&nbsp;</td>
                          </tr>
                        </table>                        
                        <p>&nbsp;</p></td>
                    </tr>

                  </table></td>
                </tr>
                <tr valign="baseline">
                  <td width="548" height="0" align="right" valign="top" nowrap bgcolor="#6666FF"><div align="center">
                    <input name="submit" type="submit" value="Submit" />
                  </div></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="form1">
            </form>
            <p>&nbsp;</p>
          </p></td>
        </tr>
        <tr>
          <td height="30" bgcolor="#4F5359" class="style16"><div align="center">
                    <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
       	    	     <input type="hidden" name="action" value="cb_pdp_training_request">
       	    	     <input type="hidden" name="page" value="vacation_view_cfig_by_cfig">       	    	   
       	    	     <input type="submit" value="Display CFIG Schedule">	 
    		         </form>               
<!-- 
          <a href="pgc_request_vacation_view_cfig_by_cfig.php" class="style17">BACK TO CFIG OFF-DUTY  PAGE </a>
 -->          
          </div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Vacation);

mysqli_free_result($Instructors);
?>
