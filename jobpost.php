<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Post your free classified job');
$tpl->Assign('show_employers_bar','true');
if(sizeof($_POST)){
	foreach($_POST as $k=>$v){
		$tpl->Assign($k,$v,1);
	}
	$filename_to_upload='';
	$arr_err=EmployerValidator($_POST);
	if(sizeof($arr_err)){
		$i=1;
		foreach($arr_err as $k=>$v){
			$tpl->Assign($k.'_error', ShowErrorDiv($v,$i));
			$i++;
		}
	}
	else{
		if(isset($_POST['account_exists']) && $_POST['account_exists']=='Yes'){
			if(!EmployerCheckLogin($_POST['login_email'],$_POST['login_password'])){
				$tpl->Assign('login_email_error', ShowErrorDiv('Login email or password is incorrect.',0));
			}
			else
			{
				if(PostJobForEmployer($_POST)){
					set_flashMessage($key='msg',$msg='Job has been posted successfully.',$error_type=1);
					header("location:index.php");exit;
				}
				else
				{
					p_r($_POST,1);
				}
			}
		}
		else{
			$employer_id=RegisterEmployer($_POST);
			PostJob($_POST,$employer_id);
			set_flashMessage($key='msg',$msg='Job has been posted successfully.',$error_type=1);
			header("location:index.php");exit;
		}
	}
}
$tpl->Assign('bShowSearchForm',false);
$tpl->Assign('nav_registration_class','');
#print_r(GetExperience('year'));
$tpl->Assign('listSalary',GetSalaryRange());
$tpl->Assign('listCurrentLocation', GetLocation());
$tpl->Assign('listExpYY', GetExperienceRange());
$tpl->Assign('listExpMM', GetExperience('month'));
$tpl->Assign('listJobFunctions', GetFunctionalArea());
$tpl->Assign('listCurrentIndustries', GetIndustries());
$tpl->Assign('listGraduation', GetEducation('UG'));
$tpl->Assign('listGraduationPG', GetEducation('PG'));
$tpl->Assign('listGraduationPHD', GetEducation('PHD'));
$custom_header='<style>header{height: 160px;}</style>';
$custom_header.='<!-- Javascript Zapatec utilities file -->  
<script type="text/javascript" src="templates/js/utils/zapatec.js"></script>
<!-- Javascript file to support form -->
<script type="text/javascript" src="templates/js/zpform/src/form.js"></script>
<link href="templates/js/zpform/themes/alternate.css" rel="stylesheet" type="text/css"> ';
$custom_footer='<script>
Zapatec.Form.setupAll({showErrorsOnSubmit:true,statusImgPos:"beforeField",showErrors:"afterField",submitErrorFunc:submit_err});
function submit_err(errors){
    var strMsg=errors.generalError
    for (var i = 0; i < errors.fieldErrors.length; i++){
		strMsg += "<br />" + errors.fieldErrors[i].errorMessage;
    }
    var outputdiv = document.getElementById("errOutput");
    if(outputdiv != null){
       outputdiv.innerHTML = strMsg;
       outputdiv.style.display = "block";
		$("#errOutput").click(function(){$("#errOutput").fadeOut();});
    }
}
function hideErr()
{
	 var outputdiv = getElementById("errOutput");
	 outputdiv.style.display ="none";
}
</script>';
$tpl->Assign('custom_header_content',$custom_header);
$tpl->Assign('custom_footer_content',$custom_footer);

$job_list=$tpl->ParseTemplate('jobpost',1);

$tpl->Assign('PageContent',$job_list,1);
$tpl->ParseTemplate('index');

?>
