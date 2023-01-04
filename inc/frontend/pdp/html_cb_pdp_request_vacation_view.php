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
/**/
//$session_pilotname = $_SESSION['MM_PilotName'];
//$session_email = $_SESSION['MM_Username']; 
//$currentPage = $_SERVER["PHP_SELF"];
$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
$BaseColor = "#35415B";
$RedColor = "#990000";
$LtBlueColor = "#35415B";
$LtBlueColor = "#1A6866";
$LtGreenColor = "#35415B";
$LtGreenColor = "#00D936";
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$maxRows_Recordset1 = 15;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

//mysql_select_db($database_PGC, $PGC);
if(isset($_GET['sort']) && $_GET['sort'] == "cfig") {
	$query_Recordset1 = "SELECT vac_key, cfig_name, vac_start, vac_end, date_format(vac_start,'%M %D ') as Mstart, date_format(vac_end,'%M %D ') as Mend, datediff(vac_end, vac_start) + 1 as mdays, saturday, sunday, monday, tuesday, wednesday, thursday, friday FROM pgc_cfig_vacation WHERE vac_end >= curdate() ORDER BY cfig_name ASC, vac_start ASC";
} else {   
	$query_Recordset1 = "SELECT vac_key, cfig_name, vac_start, vac_end, date_format(vac_start,'%M %D ') as Mstart, date_format(vac_end,'%M %D ') as Mend, datediff(vac_end, vac_start) + 1 as mdays, saturday, sunday, monday, tuesday, wednesday, thursday, friday FROM pgc_cfig_vacation WHERE vac_end >= curdate() ORDER BY vac_start ASC";
}
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysqli_query($PGCi, $query_limit_Recordset1 )  or die(mysqli_error($PGCi));
$row_Recordset1 =mysqli_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysqli_query($PGCi, $query_Recordset1 ) ;
  $totalRows_Recordset1 = mysqli_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>
<?php if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
   $insertGoTo = $_SESSION[last_r_query];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

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
<title>CFIG Vacations</title>
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
.style33 {font-size: 14px}
.style34 {color: #CCCCCC; font-weight: bold; }
.style35 {font-size: 14px; font-weight: bold; color: #000000; }
.style51 {
	font-size: 14px;
	color: #E5E5E5;
	font-weight: normal;
}
.style54 {color: #CCCCCC; font-weight: bold; font-size: 14px; }
.style57 {
	font-size: 14px;
	color: #A7B5CE;
	font-weight: normal;
}
.style58 {color: #FF3300}
-->
</style>
</head>
<body>
<table width="900" align="center" cellpadding="2" cellspacing="2" bordercolor="#000033" bgcolor="#595E80">
  <tr>
    <td align="center" bgcolor="#666666"><div align="center"><span class="style1">PGC PILOT DATA PORTAL</span></div></td>
  </tr>
  <tr>
    <td height="268" bgcolor="#666666"><table width="95%" height="277" align="center" cellpadding="4" cellspacing="2" bordercolor="#005B5B" bgcolor="#005B5B">
        <tr>
          <td width="1562" height="40" bgcolor="#4F5359"><div align="center" class="style2">
              <table width="88%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                        <td width="12%">&nbsp;</td>

<?php if(isset($_GET['sort']) && $_GET['sort'] == "cfig") { ?> 
                  <td width="71%"><div align="center" class="style33">CURRENT &amp; UPCOMING CFIG  OFF DUTY DAYS - CFIG ORDER </div></td>
                  <td width="17%" bgcolor="#6666CC"><div align="center">
                  
<!-- 
                  <a href="../PGC_OPS/pgc_request_vacation_view.php" class="style58">SORT BY START DATE </a>
 -->

                               <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
                         	    	<input type="hidden" name="action" value="cb_pdp_training_request">
                         	    	<input type="hidden" name="page" value="vacation_view">
                         	    	<input type="hidden" name="sort" value="date">
                         	    	<input type="submit" value="SORT BY Date" >	 
                      		    </form>   

<?php } else {  ?>
                  <td width="73%"><div align="center" class="style33">CURRENT &amp; UPCOMING CFIG  OFF DUTY DAYS - DUTY START DATE ORDER </div></td>
                  <td width="15%" bgcolor="#6666CC"><div align="center">
                
                                <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
                         	    	<input type="hidden" name="action" value="cb_pdp_training_request">
                         	    	<input type="hidden" name="page" value="vacation_view">
                         	    	<input type="hidden" name="sort" value="cfig">
                         	    	<input type="submit" value="SORT BY CFIG" >	 
                      		    </form>    
<?php }  ?>                                    
<!-- 
                  <a href="../PGC_OPS/pgc_request_vacation_view_by_cfig.php" class="style58">SORT BY CFIG </a>
 -->    
                  </div></td>
                </tr>
                <tr>
                        <td>&nbsp;</td>
                  <td><div align="center"></div></td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div></td>
        </tr>
        <tr>
              <td height="160" align="center" valign="top" bgcolor="#424A66">&nbsp;
                    <form id="form1" name="form1" method="post" action="">
                          <table width="95%" border="0" cellpadding="1" cellspacing="3" bgcolor="#36373A">
                                <tr>
                                      <td bgcolor="#35415B" class="style34"><div align="center" class="style57">CFIG</div></td>
                                      <td width="160" bgcolor="#35415B" class="style34"><div align="center" class="style57">OFF DUTY START</div></td>
                                      <td width="155" bgcolor="#35415B" class="style34"><div align="center" class="style57">OFF DUTY END</div></td>
                                      <td width="10" bgcolor="#35415B" class="style34"><div align="center"><span class="style57">DAYS</span></div></td>
                                      <td width="3" align="center" bgcolor="#35415B" class="style57"><span class="style57"> S</span></td>
                                      <td width="3" align="center" bgcolor="#35415B" class="style57"><span class="style57">S</span></td>
                                      <td width="3" align="center" bgcolor="#35415B" class="style57"><span class="style57">M</span></td>
                                      <td width="3" align="center" bgcolor="#35415B" class="style57"><span class="style57">T</span></td>
                                      <td width="3" align="center" bgcolor="#35415B" class="style57"><span class="style57">W</span></td>
                                      <td width="3" align="center" bgcolor="#35415B" class="style57"><span class="style57">T</span></td>
                                      <td width="3" align="center" bgcolor="#35415B" class="style57"><span class="style57">F</span></td>
                                      </tr>
                                <?php  while ($row_Recordset1 =mysqli_fetch_assoc($Recordset1)) { ?>
                                      <tr>
                                            <td bgcolor="#35415B" class="style35"><div align="left" class="style51"><?php echo $row_Recordset1['cfig_name']; ?></div></td>
                                            <td bgcolor="#35415B" class="style35"><div align="left" class="style51">
                                                  <div align="center"><?php echo $row_Recordset1['Mstart']; ?></div>
                                                  </div></td>
                                            <td bgcolor="#35415B" class="style35"><div align="left" class="style51">
                                                  <div align="center"><?php echo $row_Recordset1['Mend']; ?></div>
                                                  </div></td>
                                            <td bgcolor="#35415B" class="style51"><div align="center"><?php echo $row_Recordset1['mdays']; ?></div></td>
                                            <?php
			 $color = $RedColor; 
 			  if ($row_Recordset1['saturday'] == "ON") {
			    $color = $LtGreenColor; }
 		  	?>
                                            <td bgcolor="<?php echo $color; ?>" class="style51"><?php echo $row_Recordset1['saturday']; ?></td>
                                            <?php
			 $color = $RedColor; 
 			  if ($row_Recordset1['sunday'] == "ON") {
			    $color = $LtGreenColor; }
 		  	?>
                                            <td bgcolor="<?php echo $color; ?>"  class="style51"><?php echo $row_Recordset1['sunday']; ?></td>
                                            <?php
			 $color = $RedColor; 
 			  if ($row_Recordset1['monday'] == "ON") {
			    $color = $LtGreenColor; }
 		  	?>
                                            <td bgcolor="<?php echo $color; ?>"  class="style51"><?php echo $row_Recordset1['monday']; ?></td>
                                            <?php
			 $color = $RedColor; 
 			  if ($row_Recordset1['tuesday'] == "ON") {
			    $color = $LtGreenColor; }
 		  	?>
                                            <td bgcolor="<?php echo $color; ?>"  class="style51"><?php echo $row_Recordset1['tuesday']; ?></td>
                                            <?php
			 $color = $RedColor; 
 			  if ($row_Recordset1['wednesday'] == "ON") {
			    $color = $LtGreenColor; }
 		  	?>
                                            <td bgcolor="<?php echo $color; ?>"  class="style51"><?php echo $row_Recordset1['wednesday']; ?></td>
                                            <?php
			 $color = $RedColor; 
 			  if ($row_Recordset1['thursday'] == "ON") {
			    $color = $LtGreenColor; }
 		  	?>
                                            <td bgcolor="<?php echo $color; ?>"  class="style51"><?php echo $row_Recordset1['thursday']; ?></td>
                                            <?php
			 $color = $RedColor; 
 			  if ($row_Recordset1['friday'] == "ON") {
			    $color = $LtGreenColor; }
 		  	?>
                                            <td bgcolor="<?php echo $color; ?>"  class="style51"><?php echo $row_Recordset1['friday']; ?></td>
                                            </tr>
                                      <?php }; ?>
            </table>
                          <table width="50%" border="0" align="center" bgcolor="#CCCCCC">
                                <tr>
                                      <td width="23%" align="center"><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                                            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><img src="First.gif" border=0></a>
                                            <?php } ?>                  </td>
                                      <td width="31%" align="center"><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                                            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><img src="Previous.gif" border=0></a>
                                            <?php }  ?>                  </td>
                                      <td width="23%" align="center"><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                                            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><img src="Next.gif" border=0></a>
                                            <?php }  ?>                  </td>
                                      <td width="23%" align="center"><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                                            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><img src="Last.gif" border=0></a>
                                            <?php } ?>                  </td>
                                      </tr>
                                </table>
                          <p>
                                <label></label>
                                <input type="hidden" name="MM_insert" value="form2" />
                                </p>
                          </form></td>
        </tr>
        <tr>
              <td height="30" bgcolor="#4F5359" class="style16"><div align="center">
                                <form action="<?php echo admin_url('admin-post.php'); ?>" method="get">
                         	    	<input type="hidden" name="action" value="cb_pdp_training_request">
                         	    	<input type="hidden" name="page" value="list_member">
                         	    	<input type="submit" value="Return Training Request Page">	 
                      		    </form>    
<!-- 
              <a href="pgc_request_list_member.php" class="style54">Return to Member  Request List </a>
 -->             
              </div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Recordset1);
?>
