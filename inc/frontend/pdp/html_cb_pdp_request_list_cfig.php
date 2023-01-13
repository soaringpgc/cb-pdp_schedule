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
$MM_restrictGoTo = "../welcome-to-pgc-2/members-page/";
if ( !(isset($_SESSION['MM_Username']) ))  {   
    header("Location: ". $MM_restrictGoTo); 
  exit;
 }
$_SESSION['last_cfig_query'] = "http://" .  $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING'];
$_SESSION['last_cfig_r_query'] = "http://" .  $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING'];
$session_pilotname = $_SESSION['MM_PilotName'];
$session_email = $_SESSION['MM_Username']; 
$BaseColor = "#35415B";
$RedColor = "#990000";
$LtBlueColor = "#35415B";
$LtBlueColor = "#1A6866";
$LtGreenColor = "#35415B";
$LtGreenColor = "#575575";
/* ========= Set Vacation Flages ===============================*/
//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_request SET cfig2_vacation = 'N', cfig_vacation = 'N', cfig_vacation2 = 'N', source_key = NULL, rec_processed = 'N', rec_processed2 = 'N', rec_processed3 = 'N'"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

//mysql_select_db($database_PGC, $PGC);
$runSQL = "UPDATE pgc_request SET request_day = Date_format(request_date,'%W')"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* ---  A. Update Short Duration Vacations first ----*/
$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 7) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 7) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 31) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 31) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* --- B. Then Longer Duration Vacations  ----*/

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation = 'Y'
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays > 31 ) AND A.rec_processed  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND ( B.vdays > 31) AND A.rec_processed = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* ========== */


/* ---  A. Update Short Duration Vacations first ----*/
$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 7) AND A.rec_processed2  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed2 = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 7) AND A.rec_processed2 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 31) AND A.rec_processed2  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 31) AND A.rec_processed2 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* --- B. Then Longer Duration Vacations  ----*/

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig_vacation2 = 'Y'
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays > 31) AND A.rec_processed2  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed2 = 'Y', A.source_key = B.vac_key
WHERE A.request_cfig2 = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays > 31) AND A.rec_processed2 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* ======= */

/* ---  A. Update Short Duration Vacations first ----*/
$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 7) AND A.rec_processed3  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed3 = 'Y', A.source_key = B.vac_key
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 7) AND A.rec_processed3 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays <= 31) AND A.rec_processed3  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed3 = 'Y', A.source_key = B.vac_key
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays <= 31) AND A.rec_processed3 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

/* --- B. Then Longer Duration Vacations  ----*/

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.cfig2_vacation = 'Y'
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (LOCATE(Date_format(A.request_date,'%W'),B.alldays) > 0) AND (B.vdays > 31) AND A.rec_processed3  = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));

$runSQL = "UPDATE pgc_request A, pgc_cfig_vacation B SET A.rec_processed3 = 'Y', A.source_key = B.vac_key
WHERE A.accept_cfig = B.cfig_name and (A.request_date >= B.vac_start AND A.request_date <= B.vac_end) AND (B.vdays > 31) AND A.rec_processed3 = 'N'";
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));


/* =========  End Set Vacation Flages ===============================*/


?>

<?php
$maxRows_Requests = 20;
$pageNum_Requests = 0;
if (isset($_GET['pageNum_Requests'])) {
  $pageNum_Requests = $_GET['pageNum_Requests'];
}
$startRow_Requests = $pageNum_Requests * $maxRows_Requests;

//mysql_select_db($database_PGC, $PGC);
$query_Requests = "SELECT request_key, entry_date, member_id, member_name,member_weight, Date_format(request_date,'%W, %M %e') as mydate,  request_time, request_type, request_cfig,  request_cfig2, cfig_vacation, cfig_vacation2, cfig_weight, request_notes, accept_cfig, cfig2_vacation, accept_date, accept_notes, record_deleted, Date_format(request_date,'%W') as mdayofweek, sched_assist FROM pgc_request WHERE request_date >= curdate() AND record_deleted <> 'Y' ORDER BY request_date ASC, request_key ASC  ";
$query_limit_Requests = sprintf("%s LIMIT %d, %d", $query_Requests, $startRow_Requests, $maxRows_Requests);
$Requests = mysqli_query($PGCi, $query_limit_Requests )  or die(mysqli_error($PGCi));
//$row_Requests =mysqli_fetch_assoc($Requests);

if (isset($_GET['totalRows_Requests'])) {
  $totalRows_Requests = $_GET['totalRows_Requests'];
} else {
  $all_Requests = mysqli_query($PGCi, $query_Requests ) ;
  $totalRows_Requests = mysqli_num_rows($all_Requests);
}
$totalPages_Requests = ceil($totalRows_Requests/$maxRows_Requests)-1;

$queryString_Requests = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Requests") == false && 
        stristr($param, "totalRows_Requests") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Requests = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Requests = sprintf("&totalRows_Requests=%d%s", $totalRows_Requests, $queryString_Requests);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CFIG View - List Requests</title>
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
	color: #000000;
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
	color: #C9D1E0;
}
.style33 {color: #DD0000}
.style34 {
	color: #333333;
	font-weight: bold;
}
.style35 {color: #993300}
.style36 {
	font-size: 16px;
	color: #FFFFCA;
}
-->
</style>
</head>
<body>
<table width="1100" align="center" cellpadding="1" cellspacing="1" bordercolor="#000033" bgcolor="#595E80">
  <tr>
    <td width="1129" align="center" bgcolor="#318867"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="374" bgcolor="#666666"><table width="99%" height="370" align="center" cellpadding="4" cellspacing="3" >
      <tr>
        <td width="1126" height="51" bgcolor="#002B55"><div align="center" class="style2">
            <table width="98%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="15%" height="16" bgcolor="#0042CA"><div align="center">

                     <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
                     	<input type="hidden" name="action" value="cb_pdp_training_request">
                     	<input type="hidden" name="page" value="enter_request_cfig">
                     	<input type='hidden' name='source_page' value='<?php  the_permalink() ?>' >	 
                     	<input type="submit" value="ENTER A REQUEST FOR MEMBER" >	 
                     </form>                     
         <!-- 
       <a href="pgc_request_enter_member_by_cfig.php">ENTER A REQUEST FOR MEMBER</a>
         
 -->                      
                </div></td>
                <td width="66%"><div align="center" class="style36">CFIG    VIEW / MODIFY  REQUESTS - LIST ALL BY DATE REQUESTED</div></td>
                <td width="15%"><div align="center"><span class="style33">RED = CFIG OFF DUTY</span></div></td>
              </tr>
              <tr>
                <td height="16">&nbsp;</td>
                <td>&nbsp;</td>
                <td><div align="right" class="style34">
                    <div align="center">
                      <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
                     	<input type="hidden" name="action" value="cb_pdp_training_request">
                     	<input type="hidden" name="page" value="vacation_view_cfig_by_cfig">
                     	<input type='hidden' name='source_page' value='<?php  the_permalink() ?>' >	 
                     	<input type="submit" value="DISPLAY CFIG SCHEDULE" >	 
                     </form>                                             
<!-- 
                    <a href="pgc_request_vacation_view_cfig_by_cfig.php" class="style35">DISPLAY CFIG SCHEDULE</a>
 -->                   
                    </div>
                </div></td>
              </tr>
            </table>
        </div></td>
      </tr>
      <tr>
        <td height="276" align="center" valign="top" bgcolor="#002B55"><table border="0" align="center" cellpadding="1" cellspacing="3" bgcolor="#36373A">
            <tr>
              <td bgcolor="#35415B" class="style25"><div align="center">EDIT</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">MEMBER</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">FLTS</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">WT</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">DATE REQUESTED </div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">TYPE</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">SAR</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">REQUEST NOTES</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">CFIG 1</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center"></div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">CFIG 2</div></td>
              <td bgcolor="#35415B" class="style25">&nbsp;</td>
              <td bgcolor="#35415B" class="style25"><div align="center">CFIG ASSIGNED</div></td>
              <td bgcolor="#35415B" class="style25"><div align="center">CFIG COMMENTS </div></td>
            </tr>
            <?php  while ($row_Requests =mysqli_fetch_assoc($Requests)) { ?>
            <tr>
              <?php
							  
			  $color1 = $BaseColor;

 			  if (substr($row_Requests['mydate'],0,3) == "Sun") {
			  $color1 = $LtGreenColor; 
			  }
 			  if (substr($row_Requests['mydate'],0,3) == "Sat") {
			  $color1 = $LtBlueColor;
			  }
			  ?>
              <td bgcolor="<?php echo $color1; ?>"><div align="center">
                                             
                     <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
       	    	     <input type="hidden" name="action" value="cb_pdp_training_request">
       	    	     <input type="hidden" name="page" value="modify_cfig">
       	    	     <input type="hidden" name="request_id" value="<?php echo $row_Requests['request_key']; ?>">     
       	    	     <input type="submit" value="<?php echo $row_Requests['request_key']; ?>">	 
    		         </form>                      
<!-- 
              <a href="pgc_request_modify_cfig.php?request_id=<?php echo $row_Requests['request_key']; ?>"><?php echo $row_Requests['request_key']; ?></a>
 -->
              
              </div></td>
              <td bgcolor="<?php echo $color1; ?>"><div align="left"><a href="mailto:<?php echo $row_Requests['member_id']; ?>"><?php echo $row_Requests['member_name']; ?></a></div></td>
              <td bgcolor="<?php echo $color1; ?>"><div align="center"><strong><a href="pgc_flightlog_lookup_request.php?recordID=<?php echo $row_Requests['member_name']; ?>"></a></strong><a href="pgc_flightlog_lookup_request.php?recordID=<?php echo $row_Requests['member_name']; ?>"><img src="Flights.gif" alt="Flts" width="14" height="13" border="0" /></a></div></td>
              <td bgcolor="<?php echo $color1; ?>"><?php echo $row_Requests['member_weight']; ?></td>
              <td bgcolor="<?php echo $color1; ?>"><div align="center"><?php echo $row_Requests['mydate']; ?></div></td>
              <td bgcolor="<?php echo $color1; ?>"><div align="left"><?php echo $row_Requests['request_type']; ?></div></td>
              <td bgcolor="<?php echo $color1; ?>"><div align="center"><?php echo $row_Requests['sched_assist']; ?></div></td>
              <td bgcolor="<?php echo $color1; ?>"><div align="left"><?php echo $row_Requests['request_notes']; ?></div></td>
              <?php
			  
			  $color = $BaseColor;
 			  if (substr($row_Requests['mydate'],0,3) == "Sun") {
			   $color = $LtGreenColor;
			  }
			  
 			  if (substr($row_Requests['mydate'],0,3) == "Sat") {
			   $color = $LtBlueColor;
			  }
			  
			  if ($row_Requests['cfig_vacation'] == "Y") {
			  $color = $RedColor; 
			  }

			  ?>
              <td bgcolor="<?php echo $color; ?>"><div align="left"><?php echo $row_Requests['request_cfig']; ?></div></td>
              <?php
			  
			  $color = $BaseColor;
 			  if (substr($row_Requests['mydate'],0,3) == "Sun") {
			   $color = $LtGreenColor;
			  }
			  
 			  if (substr($row_Requests['mydate'],0,3) == "Sat") {
			   $color = $LtBlueColor;
			  }
			  
			  /*   */
						  $color = $BaseColor;
 			  if (substr($row_Requests['mydate'],0,3) == "Sun") {
			   $color = $LtGreenColor;
			  }
			  
 			  if (substr($row_Requests['mydate'],0,3) == "Sat") {
			   $color = $LtBlueColor;
			  }
			  
		

			  ?>
			   
			          <td bgcolor="<?php echo $color; ?>"><a href="pgc_request_modify_cfig_auto.php?request_id=<?php echo $row_Requests['request_key']; ?>"><?php echo '>>';$_SESSION['MM_request_key'] = $row_Requests['request_key']; ?></a></td>
			          <?php
			  
			  $color = $BaseColor;
 			  if (substr($row_Requests['mydate'],0,3) == "Sun") {
			   $color = $LtGreenColor;
			  }
			  
 			  if (substr($row_Requests['mydate'],0,3) == "Sat") {
			   $color = $LtBlueColor;
			  }
			  
			  if ($row_Requests['cfig_vacation2'] == "Y") {
			  $color = $RedColor; 
			  }

			  ?>  
			  
			  <td bgcolor="<?php echo $color; ?>"><?php echo $row_Requests['request_cfig2']; ?></td>
              <td bgcolor="<?php echo $color1; ?>"><div align="center"><a href="pgc_request_modify_cfig2_auto.php?request_id=<?php echo $row_Requests['request_key']; ?>"><?php echo '>>';$_SESSION['MM_request_key'] = $row_Requests['request_key']; ?></a></div></td>
              <?php
							  
			  $color = $BaseColor;
 			  if (substr($row_Requests['mydate'],0,3) == "Sun") {
			    $color = $LtGreenColor;
			  
			  }
 			  if (substr($row_Requests['mydate'],0,3) == "Sat") {
			  $color = $LtBlueColor;
			  }
			 
 			  if ($row_Requests['cfig2_vacation'] == "Y")   {
			  $color = $RedColor; 
			  }

			  ?>
              <td bgcolor="<?php echo $color; ?>"><div align="left"><?php echo $row_Requests['accept_cfig']; ?></div></td>
              <td bgcolor="<?php echo $color1; ?>"><?php echo $row_Requests['accept_notes']; ?></td>
            </tr>
            <?php }; ?>
          </table>
            <table width="50%" border="0" align="center" bgcolor="#CCCCCC">
              <tr>
                <td width="23%" align="center"><?php if ($pageNum_Requests > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, 0, $queryString_Requests); ?>"><img src="First.gif" border="0" /></a>
                    <?php } // Show if not first page ?>                </td>
                <td width="31%" align="center"><?php if ($pageNum_Requests > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, max(0, $pageNum_Requests - 1), $queryString_Requests); ?>"><img src="Previous.gif" border="0" /></a>
                    <?php } // Show if not first page ?>                </td>
                <td width="23%" align="center"><?php if ($pageNum_Requests < $totalPages_Requests) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, min($totalPages_Requests, $pageNum_Requests + 1), $queryString_Requests); ?>"><img src="Next.gif" border="0" /></a>
                    <?php } // Show if not last page ?>                </td>
                <td width="23%" align="center"><?php if ($pageNum_Requests < $totalPages_Requests) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Requests=%d%s", $currentPage, $totalPages_Requests, $queryString_Requests); ?>"><img src="Last.gif" border="0" /></a>
                    <?php } // Show if not last page ?>                </td>
              </tr>
          </table></td>
      </tr>
      <tr>
        <td height="29" bgcolor="#4F5359" class="style16"><div align="center"><a href=<?PHP echo $_SESSION['PDP_HOME']; ?> class="style17"></a>
                <table width="84%" height="21" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="36%">&nbsp;</td>
                    <td width="32%"><a href=<?PHP echo $_SESSION['PDP_HOME']; ?> class="style17">BACK TO MEMBERS PAGE</a></td>
                    <td width="32%"><div align="right" class="style34"><a href="pgc_request_vacation_view.php" class="style35"></a></div></td>
                  </tr>
                </table>
        </div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Requests);

?>
