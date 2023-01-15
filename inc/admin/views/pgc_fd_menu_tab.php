<?php
error_reporting(E_ALL);
if (!isset($_SESSION)) {
  session_start();
}
// require_once('pgc_check_login.php'); 
// //require_once('pgc_check_login_admin.php'); 
// require_once('pgc_access_save_appname.php'); 
// /* START - PAGE ACCESS CHECKING LOGIC - ADD TO ALL APPS - START */
// require_once('pgc_access_app_check.php');
// /* END - PAGE ACCESS CHECKING LOGIC - END */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PGC Data Portal - FD MENU</title>
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
.style11 {
	font-size: 16px;
	font-weight: bold;
	color: #FFFFFF;
}
a:link {
	color: #FFFFFF;
}
a:visited {
	color: #FFFFFF;
}
.style17 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
.style18 {
	color: #FFFF99;
	font-style: italic;
	font-weight: bold;
}
.style18
{
	font-style: normal;
}
.style18
{
	font-size: 14px;
}
.style18
{
	color: #FFF;
}
.style18 u
{
	color: #FFF;
}
.style3 {font-size: 16px; font-weight: bold; }
.style46 {color: #CCCCCC; font-size: 14px; }
-->
</style></head>

<body>
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#595E80">
  <tr>
    <td><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="473"><table width="92%" height="467" align="center" ellpadding="4" cellspacing="3" bordercolor="#005B5B" bgcolor="#414967">
      <tr>
        <td height="36"><div align="center"><span class="style11">ADMIN FIELD DUTY  MENU</span></div></td>
      </tr>
      <tr>
        <td height="423" valign="top"><div align="center">
              <table width="98%" border="0" cellspacing="2" cellpadding="2">
                    <tr bgcolor="#004242">
                            <td height="28" align="center" valign="middle" bgcolor="#103A5C"><div align="center" class="style18"><span class="style17"> FIELD DUTY SETUP / CONTROL FUNCTIONS </span></div></td>
                            <td height="30" align="center" valign="middle" bgcolor="#103A5C"><div align="center"><span class="style18"> <span class="style17"> FIELD DUTY MONITORING </span></span></div></td>
                      </tr>
                      <tr bgcolor="#004242">
                            <td width="51%" align="center" valign="middle" bgcolor="#502727"><div align="center">
                                  <div align="center"><a href="pgc_field_duty_role.php" class="style17"> ADMIN - FIELD DUTY ROLE SETUP</a></div>
                            </div></td>
                            <td width="49%" height="30" align="center" valign="middle" bgcolor="#005B5B"><a href="pgc_field_duty_member_select_dates_sessions.php" class="style17"><u class="style18"><a href="pgc_field_duty_member_select_audit.php" class="style17">MEMBER  - SELECTION AUDIT REPORT</a></td>
                      </tr>
                      <tr bgcolor="#004242">
                            <td align="center" valign="middle" bgcolor="#502727"><a href="pgc_field_duty_control_list.php" class="style17"> ADMIN - FIELD DUTY SESSION CONTROL</a></td>
                            <td height="30" align="center" valign="middle" bgcolor="#005B5B"><u class="style18"><a href="pgc_fd_member_selected_view.php" class="style17">ADMIN -  MEMBER SELECTIONS VIEW</a><a href="pgc_field_duty_member_select_audit.php" class="style17"></a></u></td>
                      </tr>
                      <tr bgcolor="#004242">
                            <td align="center" valign="middle" bgcolor="#502727">&nbsp;</td>
                            <td height="30" align="center" valign="middle" bgcolor="#005B5B"><div align="center"><a href="pgc_field_duty_list_member.php" class="style17"> </a><a href="../PGC_OPS/pgc_add_pilot_rating.php" class="style17"></a><a href="pgc_fd_xls_session_selected.php" class="style17"><u class="style18"><a href="pgc_fd_member_assigned_check.php" class="style17">ADMIN -  CHECK MEMBER ASSIGNMENTS</a></div></td>
                      </tr>
                      <tr bgcolor="#004242">
                            <td align="center" valign="middle" bgcolor="#502727"><div align="center"><a href="pgc_fd_insert_date_range.php" class="style17"> ADMIN - GENERATE WEEKEND FD DATES IN MASTER TABLE</a></div></td>
                            <td height="30" align="center" valign="middle" bgcolor="#005B5B">&nbsp;</td>
                      </tr>
                      <tr bgcolor="#004242">
                            <td height="36" align="center" valign="middle" bgcolor="#502727"><a href="pgc_fd_refresh_duty_selections_table.php" class="style17">ADMIN -  REFRESH FD SELECTIONS TABLE</a></td>
                            <td align="center" valign="middle" bgcolor="#005B5B"><a href="pgc_fd_xls_session_selected.php" class="style17">EXCEL REPORT - MEMBER FD SELECTIONS</a></td>
                            </tr>
                      <tr bgcolor="#004242">
                            <td height="41" align="center" valign="middle" bgcolor="#502727"><a href="pgc_fd_xls_session_selected.php" class="style17"></a></td>
                            <td align="center" valign="middle" bgcolor="#005B5B"><a href="pgc_field_duty_list_only.php" class="style17">BASIC FIELD DUTY SCHEDULE (Member View)</a></td>
                            </tr>
                      <tr bgcolor="#004242">
                            <td height="20" align="center" valign="middle" bgcolor="#502727"><p class="style18"> <u> ADMIN -  TRANSFER FINAL SELECTIONS TO  MASTER FIELD DUTY TABLE (TBD)</u></p></td>
                            <td align="center" valign="middle" bgcolor="#005B5B"><a href="pgc_field_duty_list_basic.php" class="style17">ADMIN - BASIC FIELD DUTY MODIFY </a></td>
                            </tr>
                      <tr bgcolor="#004242">
                            <td height="20" align="center" valign="middle" bgcolor="#502727">&nbsp;</td>
                            <td height="30" align="center" valign="middle" bgcolor="#005B5B">&nbsp;</td>
                      </tr>
                      <tr bgcolor="#004242">
                            <td height="20" align="center" valign="middle" bgcolor="#502727"><a href="pgc_field_duty_list_basic.php" class="style17"><u class="style18"><a href="pgc_field_duty_directions.php">GENERAL INSTRUCTIONS</a></td>
                            <td height="30" align="center" valign="middle" bgcolor="#005B5B"><a href="pgc_field_duty_list_only.php" class="style17"><u class="style18">MEMBER - SWAP DUTY DAYS (TBD)</u></a></td>
                      </tr>
          </table>
                <p class="style18">*TBD = To Be Designed/Developed</p>
                <div align="center"><strong class="style3"><a href=<?PHP echo $_SESSION['PDP_HOME']; ?> class="style46">Back to Members Page</a></strong>        </div>
        </div></td>
      </tr>
    </table></td>
  </tr>
</table> 
</body>
</html>