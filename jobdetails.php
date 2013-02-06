<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Right Oppertunity Grab it!');

$tpl->Assign('bShowSearchForm',false);
$tpl->Assign('nav_search_class','class="active"');
$job_details=GetJobDetails($_GET);
$tpl->Assign('IsLoggedIn',IsCandidateLoggedIn());
$tpl->Assign('job_details',$job_details);
$tpl->Assign('redirectURL',urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));
$job_list=$tpl->ParseTemplate('jobdetails',1);


$tpl->Assign('PageContent',$job_list,1);
$tpl->ParseTemplate('index');

?>
