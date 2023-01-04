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
  $insertSQL = sprintf("INSERT INTO pgc_request (request_date, request_time, request_type, request_cfig, request_notes) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['date1'], "date"),
                       GetSQLValueString($_POST['request_time'], "date"),
                       GetSQLValueString($_POST['request_type'], "text"),
                       GetSQLValueString($_POST['request_cfig'], "text"),
                       GetSQLValueString($_POST['request_notes'], "text"));

  //mysql_select_db($database_PGC, $PGC);
  $Result1 = mysqli_query($PGCi, $insertSQL )  or die(mysqli_error($PGCi));
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
$query_Instructors = "SELECT Name FROM pgc_instructors ORDER BY Name ASC";
$Instructors = mysqli_query($PGCi, $query_Instructors )  or die(mysqli_error($PGCi));
$row_Instructors =mysqli_fetch_assoc($Instructors);
$totalRows_Instructors = mysqli_num_rows($Instructors);
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
.style25 {font-weight: bold; color: #A7B5CE;}
.style31 {color: #000000}
.style32 {font-weight: bold; color: #000000; }
.style33 {font-size: 14px}
-->
</style></head>

<body>
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#000033">
  <tr>
    <td align="center" bgcolor="#666666"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="398" bgcolor="#666666"><table width="900" height="377" align="center" cellpadding="4" cellspacing="3" bordercolor="#005B5B" bgcolor="#005B5B">
        <tr>
            <td width="1562" height="56" bgcolor="#4F5359"><div align="center" class="style2">
                <table width="60%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><div align="center" class="style33">PGC STUDENT INSTRUCTION REQUEST</div></td>
                    </tr>
                    <tr>
                        <td><div align="center"></div></td>
                    </tr>
                </table>
          </div></td>
        </tr>
        <tr>
            <td height="277" align="center" valign="top" bgcolor="#4F5359"><form action="<?php echo $editFormAction; ?>" method="post" name="new_flight" id="new_flight">
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <table width="367" align="center" cellpadding="5" cellspacing="2" bgcolor="#666666">
                <tr valign="baseline">
                  <td width="140" height="41" align="right" valign="middle" nowrap bgcolor="#6666FF" class="style25"><div align="left" class="style31">INSTRUCTION DATE: </div></td>
                  <td width="215" valign="middle" bgcolor="#6666FF"><input type="text" name="date1" size="10" value="<?php echo "$date1" ?>" />
                    <a href="#"
					   name="anchor2" class="style32" id="anchor2" onclick="cal.select(document.forms['new_flight'].date1,'anchor2','yyyy-MM-dd'); return false;"> Calendar</a></td>
                </tr>
                
                <tr valign="baseline">
                  <td height="42" align="right" valign="middle" nowrap bgcolor="#6666FF" class="style25"><div align="left" class="style31">REQUESTED CFIG: </div></td>
                               
			                    <td valign="middle" bgcolor="#6666FF">
				  
				  <select name="request_cfig" id="request_cfig">
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
                    </select>				    </td>
			    </tr>
                
                <tr valign="baseline">
                  <td colspan="2" align="right" valign="middle" nowrap bgcolor="#6666FF">
                    
                    <div align="center">
                      <input type="submit" value="SAVE">
                    </div></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="form1">
            </form>
              <p>&nbsp;</p>
          </p></td>
        </tr>
        <tr>
            <td height="30" bgcolor="#4F5359" class="style16"><div align="center"><a href=<?PHP echo $_SESSION['PDP_HOME']; ?> class="style17">BACK TO MEMBERS PAGE </a></div></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Instructors);

mysqli_free_result($Requests);
?>
 