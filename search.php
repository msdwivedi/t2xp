<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Right Oppertunity Grab it!');

$tpl->Assign('bShowSearchForm',true);
$tpl->Assign('nav_search_class','class="active"');

$tpl->Assign('PageContent',$job_list='');
$tpl->ParseTemplate('index');

?>
