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
/* Purge Deletions */
//mysql_select_db($database_PGC, $PGC);
$deleteSQL = "DELETE FROM pgc_cfig_vacation WHERE rec_deleted = 'YES'";
$Result1 = mysqli_query($PGCi, $deleteSQL )  or die(mysqli_error($PGCi));

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
  $updateSQL = sprintf("UPDATE pgc_cfig_vacation SET cfig_name=%s, vac_start=%s, vac_end=%s, saturday=%s , sunday=%s, monday=%s, tuesday=%s, wednesday=%s, thursday=%s, friday=%s, rec_deleted=%s WHERE vac_key=%s",
                       GetSQLValueString($_POST['cfig_name'], "text"),
                       GetSQLValueString($_POST['date1'], "date"),
                       GetSQLValueString($_POST['date2'], "date"),
					   GetSQLValueString($_POST['v_saturday'], "text"),
					    GetSQLValueString($_POST['v_sunday'], "text"),
						 GetSQLValueString($_POST['v_monday'], "text"),
						  GetSQLValueString($_POST['v_tuesday'], "text"),
						   GetSQLValueString($_POST['v_wednesday'], "text"),
						    GetSQLValueString($_POST['v_thursday'], "text"),
							 GetSQLValueString($_POST['v_friday'], "text"),
							 GetSQLValueString($_POST['r_deleted'], "text"),
                       GetSQLValueString($_POST['vac_key'], "int"));

  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));

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
 
  $updateGoTo = "pgc_request_vacation_view_cfig.php";
   $updateGoTo =  $_SESSION['last_vacay_view'];
   
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['vac_key'])) {
  $colname_Recordset1 = $_GET['vac_key'];
}
//mysql_select_db($database_PGC, $PGC);
$query_Recordset1 = sprintf("SELECT * FROM pgc_cfig_vacation WHERE vac_key = %s", $colname_Recordset1);
$Recordset1 = mysqli_query($PGCi, $query_Recordset1 )  or die(mysqli_error($PGCi));
$row_Recordset1 =mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

//mysql_select_db($database_PGC, $PGC);
$query_Instuctors = "SELECT * FROM pgc_instructors";
$Instuctors = mysqli_query($PGCi, $query_Instuctors )  or die(mysqli_error($PGCi));
$row_Instuctors =mysqli_fetch_assoc($Instuctors);
$totalRows_Instuctors = mysqli_num_rows($Instuctors);


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
<title>CFIG Vacation Modify</title>
<style type="text/css">
<!--
.style1 {	font-size: 18px;
	font-weight: bold;
}
.style16 {color: #CCCCCC; }
.style17 {	color: #CCCCCC;
	font-size: 14px;
	font-weight: bold;
	font-style: italic;
}
.style2 {	font-size: 14px;
	font-weight: bold;
}
.style33 {font-size: 14px}
.style38 {font-weight: bold; color: #6666FF; font-size: 14px; }
.style39 {color: #BAB3FF}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #CCCCCC;
}
body {
	background-color: #333333;
}
.style32 {font-weight: bold; color: #000000; }
.style25 {	font-weight: bold;
	color: #000000;
	font-size: 16px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style43 {font-weight: bold; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; }
.style44 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style51 {font-weight: bold; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; }
.style53 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000000;
	font-size: 14;
	font-weight: bold;
}
.style54 {color: #FFFF99}
-->
</style>
</head>

<body>
<p>&nbsp;</p>
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#000033">
  <tr>
    <td align="center" bgcolor="#666666"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="308" bgcolor="#666666"><table width="900" height="283" align="center" cellpadding="4" cellspacing="3" bordercolor="#005B5B" bgcolor="#005B5B">
      <tr>
        <td width="1562" height="40" bgcolor="#4F5359"><div align="center" class="style2">
          <table width="60%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><div align="center" class="style33"> CFIG VACATION SCHEDULE MODIFY </div></td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr>
        <td height="191" align="center" valign="top" bgcolor="#4F5359"><form action="<?php echo $editFormAction; ?>" method="post" name="new_flight" id="new_flight">
          <table width="618" align="center" cellpadding="5" cellspacing="2" bgcolor="#6666CC">
            <tr valign="baseline">
              <td height="25" align="right" valign="top" nowrap="nowrap" bgcolor="#6666FF"><table width="100%" height="268" align="right" cellpadding="0" cellspacing="0">
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
                  <option value="<?php echo $row_Instuctors['Name']?>"<?php if (!(strcmp($row_Instuctors['Name'], $row_Recordset1['cfig_name']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Instuctors['Name']?></option>
                  <?php
} while ($row_Instuctors =mysqli_fetch_assoc($Instuctors));
  $rows = mysqli_num_rows($Instuctors);
  if($rows > 0) {
      mysqli_data_seek($Instuctors, 0);
	  $row_Instuctors =mysqli_fetch_assoc($Instuctors);
  }
?>
              </select>
                            </div></td>
                            <td valign="middle" bgcolor="#6666FF"><div align="left">
                                <input type="text" name="date1" size="10" value="<?php echo $row_Recordset1['vac_start']; ?>" />
                            <a href="#"
					   name="anchor2" class="style32" id="anchor2" onclick="cal.select(document.forms['new_flight'].date1,'anchor2','yyyy-MM-dd'); return false;"> Calendar</a></div></td>
                            <td valign="middle" bgcolor="#6666FF"><div align="left">
                                <input type="text" name="date2" size="10" value="<?php echo $row_Recordset1['vac_end']; ?>" />
                            <a href="#"
					   name="anchor2" class="style32" id="anchor2" onclick="cal.select(document.forms['new_flight'].date2,'anchor2','yyyy-MM-dd'); return false;"> Calendar</a></div></td>
                          </tr>
                        </table>
                    </div>
                        <p align="center"><span class="style25 style33 style54"><span class="style33">For the period identified above ... are you off-duty on these days?</span></span></p>
                      <table width="68%" align="center" cellpadding="7" cellspacing="2">
                          <tr>
                            <td width="26%" height="1" bgcolor="#5F5FC9" class="style25 style33"><div align="left" class="style51">Saturday</div></td>
                            <td width="25%" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_saturday" id="v_saturday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Recordset1['saturday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
<option value="ON" <?php if (!(strcmp("ON", $row_Recordset1['saturday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td width="25%" bgcolor="#5F5FC9"><div align="left"><span class="style51">Wednesday</span></div></td>
                            <td width="24%" bgcolor="#5F5FC9"><div align="center">
                                <select name="v_wednesday" id="v_wednesday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Recordset1['wednesday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
<option value="ON" <?php if (!(strcmp("ON", $row_Recordset1['wednesday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                          </tr>
                          <tr>
                            <td height="1" bgcolor="#5F5FC9"><div align="center" class="style33">
                                <div align="left"><span class="style43">Sunday</span></div>
                            </div></td>
                            <td bgcolor="#5F5FC9"><div align="center">
                                <select name="v_sunday" id="v_sunday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Recordset1['sunday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
<option value="ON" <?php if (!(strcmp("ON", $row_Recordset1['sunday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td bgcolor="#5F5FC9"><div align="left"><span class="style51">Thursday</span></div></td>
                            <td bgcolor="#5F5FC9"><div align="center">
                                <select name="v_thursday" id="v_thursday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Recordset1['thursday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
<option value="ON" <?php if (!(strcmp("ON", $row_Recordset1['thursday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                          </tr>
                          <tr>
                            <td height="1" bgcolor="#5F5FC9"><div align="center" class="style33">
                                <div align="left"><span class="style43">Monday</span></div>
                            </div></td>
                            <td bgcolor="#5F5FC9"><div align="center">
                                <select name="v_monday" id="v_monday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Recordset1['monday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
<option value="ON" <?php if (!(strcmp("ON", $row_Recordset1['monday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td bgcolor="#5F5FC9"><div align="left"><span class="style51">Friday</span></div></td>
                            <td bgcolor="#5F5FC9"><div align="center">
                                <select name="v_friday" id="v_friday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Recordset1['friday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
                                  <option value="ON" <?php if (!(strcmp("ON", $row_Recordset1['friday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                          </tr>
                          <tr>
                            <td height="1" bgcolor="#5F5FC9"><div align="center" class="style33">
                                <div align="left"><span class="style43">Tuesday</span></div>
                            </div></td>
                            <td bgcolor="#5F5FC9"><div align="center">
                                <select name="v_tuesday" id="v_tuesday">
                                  <option value="OFF" <?php if (!(strcmp("OFF", $row_Recordset1['tuesday']))) {echo "selected=\"selected\"";} ?>>OFF</option>
<option value="ON" <?php if (!(strcmp("ON", $row_Recordset1['tuesday']))) {echo "selected=\"selected\"";} ?>>ON</option>
                                </select>
                            </div></td>
                            <td bgcolor="#5F5FC9"><span class="style51">DELETE ?</span></td>
                            <td bgcolor="#5F5FC9"><div align="center">
                              <select name="r_deleted" id="r_deleted">
                                <option value="NO" <?php if (!(strcmp("NO", $row_Recordset1['rec_deleted']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="YES" <?php if (!(strcmp("YES", $row_Recordset1['rec_deleted']))) {echo "selected=\"selected\"";} ?>>YES</option>
                              </select>
                            </div></td>
                          </tr>
                        </table>
                      <p align="center"><span class="style53">Record Key: <?php echo $row_Recordset1['vac_key']; ?>&nbsp;</span></p></td>
                  </tr>
              </table></td>
            </tr>
            <tr valign="baseline">
              <td width="602" height="0" align="right" valign="top" nowrap="nowrap" bgcolor="#6666FF"><div align="center">
                  <input name="submit" type="submit" value="Update record" />
              </div></td>
            </tr>
          </table>
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="vac_key" value="<?php echo $row_Recordset1['vac_key']; ?>" />
        </form>        <p class="style38 style39">&nbsp;</p></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#4F5359" class="style16"><div align="center"><a href="pgc_request_vacation_view_cfig_by_cfig.php" class="style17">BACK TO CFIG VACATION  PAGE </a></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Instuctors);
?>
