<?php
require_once('configure.php');
$tpl->Assign('title','Talent 2 Explore -- Right Oppertunity Grab it!');

if(sizeof($_POST)){
	foreach($_POST as $k=>$v){
		$tpl->Assign($k,$v,1);
	}
	$filename_to_upload='';
	$arr_err=ContactUsValidator($_POST);
	if(sizeof($arr_err)){
		$i=1;
		foreach($arr_err as $k=>$v){
			$tpl->Assign($k.'_error', ShowErrorDiv($v,$i));
			$i++;
		}
	}
	else{
		ContactUsSendMessage($_POST);
		set_flashMessage($key='msg',$msg='Your message has been sent successfully.',$error_type=1);
		header("location:index.php");exit;
				
	}
}
$tpl->Assign('listBestDescribe',array('','A company','A recruter','An employee','A candidate','A general site visitor','Other'));


$custom_header='<style>header{height: 160px;}body{background-image:url(logoBigTrans.png); background-repeat:no-repeat;width:100%;height:800px;margin:0;padding:0;z-index:100px;overflow:visible;background-position:top;background-attachment:fixed}
section#content {
    background:none;
}
</style>';
$custom_header.='<!-- Javascript Zapatec utilities file -->  
<script type="text/javascript" src="templates/js/utils/zapatec.js"></script>
<!-- Javascript file to support form -->
<script type="text/javascript" src="templates/js/zpform/src/form.js"></script>
<link href="templates/js/zpform/themes/alternate.css" rel="stylesheet" type="text/css">
';
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

$page_content='<h2 style="margin:0px; padding:0px;">SORRY, OUR WEBSITE IS UNDER DEVELOPMENT. YOU MAY ENCOUNTER ERROR PAGE SEVERAL TIMES</h2><h1>Our apologise, We will get back soon with fully functional website. For any queries please fill up our contactus form.</h1><br class="clear" />Thanks for your patience';
$job_list=$tpl->ParseTemplate('contact',1);
$page_content.=$job_list;//.'<div style="background-image:url(logoBigTrans.png); background-repeat:no-repeat;width:100%;height:800px;margin:0;padding:0;z-index:-9overflow:visible;background-position:top">&nbsp;</div>';

$tpl->Assign('PageContent',$page_content,1);

$tpl->ParseTemplate('index');

?>
