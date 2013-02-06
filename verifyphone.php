<?php require_once('configure.php');
$sql="SELECT * FROM ".$tableprefix."candidates WHERE mobile_verify_token='".dbin($_REQUEST['transaction_token'])."'";
//file_put_contents("./test/testresponse.txt", "==========".print_r($_REQUEST,1).'============'.print_r($_SESSION,1));
$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
if(mysql_num_rows($result)>0){
	$r=mysql_fetch_assoc($result);
	UpdateMobileVerify('Yes',$r);
}
?>