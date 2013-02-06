<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Right Oppertunity Grab it!');

$tpl->Assign('more_options_kw','');
$tpl->Assign('current_social_url','');
$tpl->Assign('current_social_title','');


$tpl->Assign('Has_Recentlypostedjobs','true');
$tpl->Assign('Has_Recentlypostedjobs2','true');
if(IsCandidateLoggedIn()){
	$tpl->Assign('show_employers_bar',false);
}
else{
	$tpl->Assign('show_employers_bar',true);
}
$tpl->Assign('IsLoggedIn',IsCandidateLoggedIn());
if(IsCandidateLoggedIn()){
	$tpl->Assign('LogoutLink','logout_candidate.php');
	
}
$arrRecJob=GetRecentlyPostedJobList();
$arrRecJob2=GetRecentlyPostedJobList(3,6);
$tpl->Assign('Recentlypostedjobs_list',$arrRecJob);
if(empty($arrRecJob2)){
	shuffle($arrRecJob);
	$tpl->Assign('Recentlypostedjobs_list2',$arrRecJob);
}
else{
	$tpl->Assign('Recentlypostedjobs_list2',$arrRecJob2);
}
$tpl->Assign('bShowSearchForm',true);
$tpl->Assign('nav_home_class','class="active"');
//p_r(GetCategories(),1);
$tpl->Assign('CatColumns',array(0,1,2,3,4));
$tpl->Assign('results', GetCategories() );

$job_list=$tpl->ParseTemplate('homepage',1);

$tpl->Assign('Sucess_message',print_flashMessage($key='msg',$type=2));



$tpl->Assign('PageContent',$job_list,1);
$tpl->ParseTemplate('index');

?>
