<?php
require_once('pgc_connect.php');
?>
<?php
error_reporting(E_ALL);
if (!isset($_SESSION)) {
  session_start();
}
//require_once('pgc_check_login.php'); 
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT IGNORE INTO pgc_field_duty (`date`, `session`) VALUES (%s, %s)",
                       GetSQLValueString($_POST['date'], "date"),
                       GetSQLValueString($_POST['session'], "int"));

  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $insertSQL )  or die(mysqli_error($PGCi));

  $insertGoTo = "pgc_field_duty_insert_dates.php";
  $updateGoTo = 'http://' . $_SERVER['HTTP_HOST']  .$_SESSION[last_query];
  if (isset($_SERVER['xQUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

//mysql_select_db($database_PGC, $PGC);
$query_Recordset1 = "SELECT * FROM pgc_field_duty ORDER BY `date` DESC";
$Recordset1 = mysqli_query($PGCi, $query_Recordset1 )  or die(mysqli_error($PGCi));
$row_Recordset1 =mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
.style11 {font-size: 16px; font-weight: bold; }
a:link {
	color: #CCCCCC;
}
a:visited {
	color: #CCCCCC;
}
.style16 {color: #CCCCCC; }
.style20 {color: #8CA6D8; font-style: italic; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style></head>

<body>
<table width="800" align="center" cellpadding="3" cellspacing="2" bordercolor="#000033" bgcolor="#2B5555">
  <tr>
    <td><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="514" bgcolor="#666666"><table width="92%" height="417" align="center" cellpadding="2" cellspacing="2" bordercolor="#005B5B" bgcolor="#404368">
      <tr>
        <td height="36"><div align="center"><span class="style11">FIELD DUTY DATES - CREATE</span></div></td>
      </tr>
      <tr>
        <td height="373" bgcolor="#4F5359"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          <table width="207" align="center" cellpadding="3" cellspacing="1" bordercolor="#333333" bgcolor="#333333">
            <tr valign="baseline">
              <td height="32" align="right" valign="middle" nowrap="nowrap" bgcolor="#2B5555"><div align="left"><em><strong>DATE:</strong></em></div></td>
              <td valign="middle" bgcolor="#2B5555"><input name="date" type="text" value="<?php echo $row_Recordset1['date']; ?>" size="10" maxlength="10" /></td>
            </tr>
            <tr valign="baseline">
              <td height="40" align="right" valign="middle" nowrap="nowrap" bgcolor="#2B5555"><div align="left"><em><strong>SESSION:</strong></em></div></td>
              <td valign="middle" bgcolor="#2B5555"><select name="session" id="session">
                <option value="1" <?php if (!(strcmp(1, $row_rsFieldDuty['session']))) {echo "selected=\"selected\"";} ?>>1</option>
                <option value="2" <?php if (!(strcmp(2, $row_rsFieldDuty['session']))) {echo "selected=\"selected\"";} ?>>2</option>
                <option value="3" <?php if (!(strcmp(3, $row_rsFieldDuty['session']))) {echo "selected=\"selected\"";} ?>>3</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td height="32" colspan="2" align="right" valign="middle" nowrap="nowrap" bgcolor="#2B5555"><label></label>                  <div align="center">
                      <input name="submit" type="submit" value="Insert record" />
                      </div></td>
              </tr>
          </table>
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
          <p>&nbsp;</p>
          <p align="center">&nbsp;</p>
          
          <p align="center"><strong class="style11"><a href="pgc_portal_menu.php" class="style20">BACK TO PDP MAINTENANCE MENU</a><a href="pgc_portal_menu.php" class="style16"></a><a href="../PGC_OPS/pgc_fd_menu.php" class="style16"></a></strong></p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Recordset1);
?>
