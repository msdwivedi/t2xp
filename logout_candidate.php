<?php
require_once('configure.php');
if(CandidateLogOut()){
	set_flashMessage($key='msg',$msg='You have been logged out successfully.',$error_type=1);
	header("location:index.php");exit;
}
else{
	set_flashMessage($key='msg',$msg='Probably you have been already logged out.',$error_type=1);
	header("location:index.php");exit;	
}
?>
