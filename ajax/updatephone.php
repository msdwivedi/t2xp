<?php require_once('../configure.php');
if(IsCandidateLoggedIn()){
	if(UpdateMobileNo($_POST['no'])){
		UpdateMobileVerify('No');
		CandidateSessionRefresh();
		echo "Success";
	}
}
?>