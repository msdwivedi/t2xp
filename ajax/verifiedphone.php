<?php require_once('../configure.php');
include('JSON.php');
$customerToken='599e30daa4b0a9da7d2d9d8c557c133729875e83'; 
$z2vToken='b8a383310a1432f5b3676ae882a8a4e75fbf336c';
if(IsCandidateLoggedIn()){
	echo CheckPhoneVerified();
}
?>