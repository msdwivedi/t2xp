<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Register with us');
if(sizeof($_POST)){
	foreach($_POST as $k=>$v){
		$tpl->Assign($k,$v,1);
	}
	$filename_to_upload='';
	$arr_err=CandidateValidator($_POST);
	if(sizeof($arr_err)){
		$i=1;
		foreach($arr_err as $k=>$v){
			$tpl->Assign($k.'_error', ShowErrorDiv($v,$i));
			$i++;
		}
	}
	else{
		$reg_id=RegisterCandidate($_POST);
		//SendEmailToAdmin($_POST);
		//SendEmailToCandidate($reg_id);
		set_flashMessage($key='msg',$msg='Your resume has been submitted successfully.',$error_type=1);
		header("location:index.php");exit;
	}
}
$tpl->Assign('bShowSearchForm',false);
$tpl->Assign('nav_registration_class','class="active"');
#print_r(GetExperience('year'));
$tpl->Assign('listCurrentLocation', GetLocation());
$tpl->Assign('listExpYY', GetExperience('year'));
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

$job_list=$tpl->ParseTemplate('registration',1);

$tpl->Assign('PageContent',$job_list,1);
$tpl->ParseTemplate('index');

?>
