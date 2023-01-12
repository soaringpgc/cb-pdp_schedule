<?php
require_once('pgc_connect.php');
?>
<?php 
$date = date("Y-m-d") + 1; 
//mysql_select_db($database_PGC, $PGC);

$runSQL = "INSERT INTO `pgcsoaringdb`.`pgc_field_duty` (`date`) VALUES ('$date')"; 
$Result1 = mysqli_query($PGCi, $runSQL )  or die(mysqli_error($PGCi));
 ?>

