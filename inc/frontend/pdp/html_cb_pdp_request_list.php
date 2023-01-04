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
$maxRows_Requests = 10;
$pageNum_Requests = 0;
if (isset($_GET['pageNum_Requests'])) {
  $pageNum_Requests = $_GET['pageNum_Requests'];
}
$startRow_Requests = $pageNum_Requests * $maxRows_Requests;

//mysql_select_db($database_PGC, $PGC);
$query_Requests = "SELECT entry_date, member_id, member_name, Date_format(request_date,'%m/%d/%y') as mydate,  request_time, request_type, request_cfig, request_notes, accept_cfig, accept_date, accept_notes FROM pgc_request WHERE request_date >= curdate() ORDER BY request_date ASC, request_cfig ASC   ";
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Member View - List Requests</title>
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
.style27 {font-size: 10}
.style30 {font-size: 12px}
-->
</style></head>

<body>
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#000033">
  <tr>
    <td align="center" bgcolor="#666666"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="440" bgcolor="#666666"><table width="900" height="565" align="center" cellpadding="4" cellspacing="3" bordercolor="#005B5B" bgcolor="#005B5B">
        <tr>
            <td width="1562" height="56" bgcolor="#4F5359"><div align="center" class="style2">
                <table width="60%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><div align="center">MEMBER VIEW - STUDENT  INSTRUCTION REQUESTS - LIST ALL BY DATE REQUESTED </div></td>
                    </tr>
                    <tr>
                        <td><div align="center"></div></td>
                    </tr>
                </table>
          </div></td>
        </tr>
        <tr>
            <td height="465" align="center" valign="top" bgcolor="#4F5359"><table width="98%" border="0" align="center" cellpadding="3" cellspacing="3" bgcolor="#36373A">
              <tr>
                <td width="152" bgcolor="#35415B" class="style25"><div align="center">DATE &amp; TIME ENTERED </div></td>
                <td width="220" bgcolor="#35415B" class="style25"><div align="center">MEMBER</div></td>
                <td width="138" bgcolor="#35415B" class="style25"><div align="center">DATE REQUESTED </div></td>
                <td width="221" bgcolor="#35415B" class="style25"><div align="center">CFIG REQUESTED</div></td>
                <td width="212" bgcolor="#35415B" class="style25"><div align="center">CFIG ASSIGNED</div></td>
              </tr>
              <?php do { ?>
              <tr>
                <td bgcolor="#35415B"><div align="center"><?php echo $row_Requests['entry_date']; ?></div></td>
                <td bgcolor="#35415B"><?php echo $row_Requests['member_name']; ?></td>
                <td bgcolor="#35415B"><div align="center"><?php echo $row_Requests['mydate']; ?></div></td>
                <td bgcolor="#35415B"><?php echo $row_Requests['request_cfig']; ?></td>
                <td bgcolor="#35415B"><?php echo $row_Requests['accept_cfig']; ?></td>
                </tr>
              <?php } while ($row_Requests =mysqli_fetch_assoc($Requests)); ?>
            </table>
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
mysqli_free_result($Requests);

mysqli_free_result($Recordset1);
?>
 