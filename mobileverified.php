<?php require_once('configure.php');
$r=unserialize($_SESSION['candidate_login']);
	$sql="SELECT * FROM ".$tableprefix."candidates WHERE id='".dbin($r['id'])."'";
	$result1=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$r1=mysql_fetch_assoc($result1);
	@session_unregister($_SESSION['candidate_login']);
	@session_unregister($candidate_login);
	unset($_SESSION['candidate_login']);
	unset($candidate_login);
	$_SESSION['candidate_login']=serialize($r1);
	set_flashMessage($key='msg',$msg='Thanks your verifying your mobile number.',$error_type=1);
	header("location:candidate_dashboard.php");
	die;
?>