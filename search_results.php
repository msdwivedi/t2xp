<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Right Oppertunity Grab it!');
$tpl->Assign('Has_Recentlypostedjobs','true');
$tpl->Assign('Has_Recentlypostedjobs2','true');
//p_r($_POST,1);
$tpl->Assign('more_options_kw',',preFill:\''.trim($_POST['as_values_keywords'],',').'\'');
$tpl->Assign('more_options_loc',',preFill:\''.trim($_POST['as_values_location'],',').'\'');

$arrRecJob=GetSearchResults($_POST);
$tpl->Assign('Search_Results_list',$arrRecJob);
if(empty($arrRecJob2)){
	shuffle($arrRecJob);
	$tpl->Assign('Recentlypostedjobs_list2',$arrRecJob);
}
else{
	$tpl->Assign('Recentlypostedjobs_list2',$arrRecJob2);
}
$tpl->Assign('bShowSearchForm',true);
$tpl->Assign('nav_home_class','class="active"');
$job_list=$tpl->ParseTemplate('searchresults',1);

$tpl->Assign('Sucess_message',print_flashMessage($key='msg',$type=2));



$tpl->Assign('PageContent',$job_list,1);
$tpl->ParseTemplate('index');

?>
