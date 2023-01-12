<?php
require_once('pgc_connect.php');
?>
<?php
error_reporting(E_ALL);
//ini_set('display_errors', 'On');
if (!isset($_SESSION)) {
  session_start();
}
require_once('pgc_check_login.php'); 
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
	global $PGCi;
  $theValue = mysqli_real_escape_string($PGCi, $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE pgc_field_duty_control SET session_start_date=%s, session_end_date=%s, session_active=%s WHERE fd_session=%s",
                       GetSQLValueString($_POST['session_start_date'], "date"),
                       GetSQLValueString($_POST['session_end_date'], "date"),
                       GetSQLValueString($_POST['session_active'], "text"),
                       GetSQLValueString($_POST['fd_session'], "text"));

  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
  

/* 
// Set other sessions to inactive (removed as a feature to allow independent session control.)
if ($_POST['session_active'] == 'Y') {
    $updateSQL = sprintf("UPDATE pgc_field_duty_control SET session_active='N' WHERE fd_session<>%s",
                         GetSQLValueString($_POST['fd_session'], "text"));
  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $updateSQL )  or die(mysqli_error($PGCi));
}
*/
  
  //

  $updateGoTo = "pgc_field_duty_control_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

//mysql_select_db($database_PGC, $PGC);
$query_session_control = "SELECT fd_session, session_start_date, session_end_date, session_active FROM pgc_field_duty_control ORDER BY fd_session ASC";
$session_control = mysqli_query($PGCi, $query_session_control )  or die(mysqli_error($PGCi));
$row_session_control =mysqli_fetch_assoc($session_control);
$totalRows_session_control = mysqli_num_rows($session_control);$colname_session_control = "-1";
if (isset($_GET['fd_session'])) {
  $colname_session_control = $_GET['fd_session'];
}
//mysql_select_db($database_PGC, $PGC);
$query_session_control = sprintf("SELECT fd_session, session_start_date, session_end_date, session_active FROM pgc_field_duty_control WHERE fd_session = %s ORDER BY fd_session ASC", GetSQLValueString($colname_session_control, "text"));
$session_control = mysqli_query($PGCi, $query_session_control )  or die(mysqli_error($PGCi));
$row_session_control =mysqli_fetch_assoc($session_control);
$totalRows_session_control = mysqli_num_rows($session_control);
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
<title>PGC Data Portal</title>
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
.style16 {
	color: #CCCCCC;
	font-size: 14px;
}
a:link {
	color: #E6E3DF;
}
a:visited {
	color: #E6E3DF;
}
a:active {
	color: #FFFFCC;
}
.style19 {color: #CCCCCC; font-style: italic; font-weight: bold; }
.style20 {	font-size: 16px;
	font-weight: bold;
	color: #FFCCCC;
}
.style24 {font-size: 16px; font-weight: bold; color: #CCCCCC; }
.style28 {font-size: 12px}
.style23 {font-size: 16px; font-weight: bold; color: #FFCCCC; font-style: italic; }
.style44 {color: #999999;
	font-weight: bold;
}
-->
</style></head>

<body>
<table width="800" border="0" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#666666">
  <tr>
    <td width="1002"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="190" bgcolor="#3E3E5E"><table width="99%" height="186" border="0" align="center" cellpadding="5" cellspacing="2" bordercolor="#005B5B" bgcolor="#4F5359">
      <tr>
        <td height="25"><div align="center">
            <table width="99%" border="0" cellspacing="2" cellpadding="2">
                <tr>
                      <td bgcolor="#333366"><div align="center"><span class="style24">FIELD DUTY CONTROL</span></div></td>
                </tr>
                </table>
            </div></td>
      </tr>
      
      <tr>
        <td height="108" align="center" valign="top">&nbsp;
              <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                    <table align="center">
                          <tr valign="baseline">
                                <td width="111" align="right" nowrap="nowrap">Fd_session:</td>
                                <td width="323"><?php echo $row_session_control['fd_session']; ?></td>
                          </tr>
                          <tr valign="baseline">
                                <td nowrap="nowrap" align="right">Session_start_date:</td>
                                <td><input type="text" name="session_start_date" value="<?php echo htmlentities($row_session_control['session_start_date'], ENT_COMPAT, 'iso-8859-1'); ?>" size="12" />
                                      <a href="#"
					   name="anchor2" class="style44" id="anchor2" onclick="cal.select(document.forms['form1'].session_start_date,'anchor2','yyyy-MM-dd'); return false">Select From Calendar</a></td>
                          </tr>
                          <tr valign="baseline">
                                <td nowrap="nowrap" align="right">Session_end_date:</td>
                                <td><input type="text" name="session_end_date" value="<?php echo htmlentities($row_session_control['session_end_date'], ENT_COMPAT, 'iso-8859-1'); ?>" size="12" />
                                      <a href="#"
					   name="anchor1" class="style44" id="anchor1" onclick="cal.select(document.forms['form1'].session_end_date,'anchor1','yyyy-MM-dd'); return false">Select From Calendar</a></td>
                          </tr>
                          <tr valign="baseline">
                                <td nowrap="nowrap" align="right">Session_active:</td>
                                <td><select name="session_active" id="session_active">
                                      <option value="Y" <?php if (!(strcmp("Y", $row_session_control['session_active']))) {echo "selected=\"selected\"";} ?>>Y</option>
                                      <option value="N" <?php if (!(strcmp("N", $row_session_control['session_active']))) {echo "selected=\"selected\"";} ?>>N</option>
                                </select></td>
                          </tr>
                          <tr valign="baseline">
                                <td nowrap="nowrap" align="right">&nbsp;</td>
                                <td><input type="submit" value="Update record" /></td>
                          </tr>
                    </table>
                    <input type="hidden" name="MM_update" value="form1" />
                    <input type="hidden" name="fd_session" value="<?php echo $row_session_control['fd_session']; ?>" />
              </form>
              <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td height="33"><div align="center" class="style20">
            <p><a href="pgc_fd_menu.php" class="style16">BACK TO FD MENU</a></p>
        </div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($session_control);
?>
