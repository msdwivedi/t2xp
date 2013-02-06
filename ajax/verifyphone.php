<?php require_once('../configure.php');
include('JSON.php');
$customerToken='599e30daa4b0a9da7d2d9d8c557c133729875e83'; 
$z2vToken='b8a383310a1432f5b3676ae882a8a4e75fbf336c';
if(IsCandidateLoggedIn()){
	$c=unserialize($_SESSION['candidate_login']);	
	$content= file_get_contents('http://www.zipdial.com/z2v/startTransaction.action?customerToken='.$customerToken.'&clientTransactionId='.$c['id'].'&callerid='.$_POST['no'].'&duration=180&countryCode=91&z2vToken='.$z2vToken);
	$j=new Services_JSON();
	$o=$j->decode($content);
	//print_r($content);
	if($o->message=='ok'){
		echo 'Dial this toll fre number...<br>';
		echo '<img src="'.$o->img.'" alt=""/>';
		UpdateMobileVerifyToken($o->transaction_token);
	}else{
		print_r($o);
		
	}
}
?>