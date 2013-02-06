<?php
if(!session_id()){
	session_start();
}


if($_SERVER['HTTP_HOST']=='localhost')
	define('SITE_URL','http://'.$_SERVER['HTTP_HOST'].'/t2xplore');
else
	define('SITE_URL','http://'.$_SERVER['HTTP_HOST'].'');
define('DS',DIRECTORY_SEPARATOR);
define('SITE_ROOT',dirname(__FILE__));

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . SITE_ROOT . '/library'); //Add Zend Library

define('INCLUDES_DIR',SITE_ROOT.DS.'includes');
define('FUNCTIONS_DIR',INCLUDES_DIR.DS.'functions');
define('CLASSES_DIR',INCLUDES_DIR.DS.'classes');

define('TEMPLATE_DIRECTORY',SITE_ROOT.DS.'templates');
define('TEMP_STORAGE_PATH',SITE_ROOT.DS.'temp');
define('UPLOAD_PATH',TEMP_STORAGE_PATH.DS.'upload');
define('MAX_EXPERIENCE','30');//maximum experience we will manage this from DB/admin later.
define('MODEL_DIRECTORY',SITE_ROOT.DS.'model');

$db_settings_local=array(
							'host'=>'localhost',
							'username'=>'root',
							'password'=>'root',
							'dbname'=>'t2xplore',
							'tableprefix'=>'t2x_'
						);

$db_settings_production=array(
							'host'=>'dhootinfradb.db.8581085.hostedresource.com',
							'username'=>'dhootinfradb',
							'password'=>'Dhoot123mani',
							'dbname'=>'dhootinfradb',
							'tableprefix'=>'t2x_'
						);
require_once(CLASSES_DIR.DS.'class.template.php');
require_once(CLASSES_DIR.DS.'mani_template.class.php');
require_once(FUNCTIONS_DIR.DS.'functions.php');
require_once(MODEL_DIRECTORY.DS.'model_functions.php');

$tpl = GetTemplateSystem();
if(IsCandidateLoggedIn()){
	$tpl->Assign('IsLoggedIn',true);
	$tpl->Assign('show_employers_bar',false);
	$sess=unserialize($_SESSION['candidate_login']);
	//p_r($sess);

	foreach($sess as $k => $v){
		if($k=='lastlogin')
			$v=date("jS, M Y h:iA",strtotime($v));			
		$tpl->Assign($k,$v);	

	}
}
$tpl->Assign('HomeLink',SITE_URL);
$tpl->Assign('SearchLink',SITE_URL.'/search.php');
$tpl->Assign('RegCandidateLink',SITE_URL.'/registration.php');
$tpl->Assign('ProfileLink',(IsCandidateLoggedIn()?SITE_URL.'/candidate_dashboard.php':'login.php'));
$tpl->Assign('AdviceLink',SITE_URL.'/advice.php');
$tpl->Assign('HelpContactLink',SITE_URL.'/contact.php');
$tpl->Assign('PostJOBLink',SITE_URL.'/jobpost.php');
$tpl->Assign('SearchCandidateLink',SITE_URL.'/search_condidate.php');
$tpl->Assign('SlogalText','Experience..... "The Right Opportunity" Grab it!!!');

$tpl->Assign('logo_link',SITE_URL);
$tpl->Assign('SlogalText','Experience..... "The Right Opportunity" Grab it!!!');
$tpl->Assign('topLink1_Link',(IsCandidateLoggedIn()?'logout_candidate.php':'login.php'));
$tpl->Assign('topLink1_Text',(IsCandidateLoggedIn()?'Logout':'Candidate Login'));
$tpl->Assign('topLink2_Link','#');
$tpl->Assign('topLink2_Text','About');
$tpl->Assign('topLink3_Link','#');
$tpl->Assign('topLink3_Text','Sitemap');
$tpl->Assign('extra_top_nav','<div class="topnav-03_"><span>|</span>011-45629750, +919711897500</div>');
$tpl->Assign('LoginLink','login.php');
$tpl->Assign('FbLink','http://www.facebook.com/prashant.singh.507464');
$tpl->Assign('current_social_url','http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['PHP_SELF']);
$tpl->Assign('current_social_text','Experience..... "The Right Opportunity" Grab it!!!');
//$navigation=$tpl->ParseTemplate('navigation',1);
global $tpl;
?>
