<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Right Oppertunity Grab it!');

if(sizeof($_POST)){
	if(!loginCandidate($_POST,$_GET)){
		$tpl->Assign('loginError','Email or password is incorrect.');
	}
}
$subcontent=$tpl->ParseTemplate('login',1);

$tpl->Assign('style_show','false');
$tpl->Assign('PageContent',$subcontent,1);
$tpl->ParseTemplate('index');

?>
