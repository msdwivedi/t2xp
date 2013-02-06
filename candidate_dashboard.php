<?php
require_once('configure.php');
if(!IsCandidateLoggedIn()){
	header("location:login.php?redirecturl=".$_SERVER['PHP_SELF']);
	die;
}else{
	$tpl->Assign('title','Talent 2 Explore -- Your Dashboard');
	$job_list=$tpl->ParseTemplate('candidate_dashboard',1);
	
	$tpl->Assign('Sucess_message',print_flashMessage($key='msg',$type=2));
	
	
	
	$tpl->Assign('PageContent',$job_list,1);
	$tpl->ParseTemplate('index');
}
?>
