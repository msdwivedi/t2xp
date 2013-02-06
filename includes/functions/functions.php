<?php
function GetTemplateSystem($templateDirectory = null)
{
	$directory = TEMPLATE_DIRECTORY . '/';
	$cache_directory = TEMP_STORAGE_PATH . '/template-cache';
//die($cache_directory);
	if (!is_null($templateDirectory)) {
		if (!is_dir($templateDirectory)) {
			throw new Exception('Template directory does not exists');
		}

		$directory = "{$templateDirectory}/;{$directory}";
	}

	if (!is_dir($cache_directory)) {
		if (!@mkdir($cache_directory)) {
			throw new Exception('Cannot create template cache directory');
		}

		@chmod($cache_directory, 0777);
	}

	$tpl = new EXT_ManiTemplate();
	$tpl->SetLangFunction('GetLang');
	$tpl->SetCachePath($cache_directory);
	$tpl->SetTemplatePath($directory, 'html');
	//$tpl->SetCharacterSet('UTF-8');

	return $tpl;
}
function set_flashMessage($key='msg',$msg='',$error_type=1)
{
	if(!session_id())
		session_start();
	if(!empty($msg))
	{
		$_SESSION[$key]=$msg;
		$_SESSION['error_type']=$error_type;
	}
}
function HidableMessage($msg='',$error_type=1)
{
	if(!session_id())
		session_start();
	$id="flash_message_".session_id();
	$msg='<div id="'.$id.'" class="'.($error_type==1?"success_message":"error_message").'" align="center">'.$msg.'</div><script type="text/javascript">$("#'.$id.'").fadeOut(10000);</script>';
	return $msg;
}
function print_flashMessage($key='msg',$type=1)
{
	$tempmsg='';
	if(!session_id())
		session_start();
	if(isset($_SESSION[$key]))
	{
		$tempmsg=$_SESSION[$key];
		if($type==2)
			$tempmsg=HidableMessage($tempmsg,$_SESSION['error_type']);
		unset($_SESSION[$key]);
		@session_unregister($$key);
		unset($_SESSION['error_type']);
		@session_unregister($error_type);
	}
	return $tempmsg;
}
function ContactUsValidator($a){
	global $_FILES,$filename_to_upload;
	$array_errors=array();	
	$email_check=array();
	$account_exists=array();
	$empty_check=array('name'=>'Name','email'=>'Email','mobile_no'=>'Mobile number','contact_person_is'=>'What best describe you','text_message'=>'Your message');
	$email_check['email']='Email';
	if(stristr($a['contact_person_is'],'other')){
		$empty_check['other_describe']='What Other best describe you';
	}
	if(stristr($a['contact_person_is'],'company')){
		$empty_check['company_name']='Company name';
		$empty_check['office_address']='Office address';
		
	}	
	foreach($empty_check as $k=>$v){
		if(empty($a[$k])){
			$array_errors[$k]="'".$v."' field is required."; 
		}
	}
	require_once 'Zend/Validate/EmailAddress.php';
	foreach($email_check as $k=>$v){
		$validator = new Zend_Validate_EmailAddress();
		if (!$validator->isValid($a[$k])) 
		{    
			$array_errors[$k]="'".$v."' address is not valid."; 
			// email is invalid; print the reasons    
			foreach ($validator->getMessages() as $message) 
			{
					$array_errors[$k] .= "$message\n";    
			}
		}		
	}	
	return $array_errors;	
}
function ContactUsSendMessage($a){
	require_once 'Zend/Mail.php';
    $mail = new Zend_Mail();
	$messageTemplate='<html><head><title>%s</title></head><body><table width="100%">%s</table></body></html>';
	$message="<tr><td><strong>Sender's name:</strong></td><td>$a[name]</td></tr>";
	$message.="<tr><td><strong>Sender's email:</strong></td><td>$a[email]</td></tr>";
	$message.="<tr><td><strong>Sender's Mobile:</strong></td><td>$a[mobile_no]</td></tr>";
	$message.="<tr><td><strong>Sender's Landline:</strong></td><td>$a[ll_phone_no]</td></tr>";
	$message.="<tr><td><strong>What best describe sender:</strong></td><td>".(stristr($a['contact_person_is'],'other')?$a['other_describe']:$a['contact_person_is'])."</td></tr>";
	if(stristr($a['contact_person_is'],'company')){
		$message.="<tr><td><strong>Sender's Company name:</strong></td><td>$a[company_name]</td></tr>";
		$message.="<tr><td><strong>Sender's Office Address:</strong></td><td>$a[office_address]</td></tr>";
	}	
	$message.="<tr><td colspan=\"2\"><strong>Sender's Message:</td></tr>";
	$message.="<tr><td colspan=\"2\">$a[text_message]</td></tr>";

    $mail->setBodyText(strip_tags(preg_replace('%</?[a-z][a-z0-9]*[^<>]*>%', '',str_replace("</tr>","\n",$$message))));
	$subject='Contact us form submitted by '.$a['name'];
	$message = sprintf($messageTemplate,$subject,$message);
    $mail->setBodyHtml($message);
    $mail->setFrom($a['email'], $a['name']);
    $mail->addTo('career@t2xplore.com', 'Prashant Singh');
	$mail->addTo('msdwivedi1@gmail.com', 'Mani Dwivedi');
    $mail->setSubject($subject);
    $mail->send();	
}
function EmployerValidator($a){
	global $_FILES,$filename_to_upload;
	$array_errors=array();	
	$email_check=array();
	$account_exists=array();
	$empty_check=array('job_title'=>'Job title','current_industry'=>'Current industry','job_function'=>'Job function','job_role'=>'Job role','contact_name'=>'Contact name','company_name'=>'Company name','employmenttype'=>'Employment type','experience_range'=>'Experience in years','salary_range'=>'Salary range','job_location'=>'Job location','job_requirements'=>'Job requirements','account_exists'=>'Account exists');
	if(in_array('OEmployerValidatorther',$a['job_location'])){
		$empty_check['other_city']='Other city';
	}
	if(isset($a['account_exists']) && $a['account_exists']=='Yes'){
		$empty_check['login_email']='Login email';
		$empty_check['login_password']='Login password';
		$email_check['login_email']='Login email';
	}
	else{
		$empty_check['contact_email']='Contact email';
		$empty_check['mobile_no']='Mobile number';
		$empty_check['ll_phone_no']='Landline number';
		$empty_check['current_location']='Current location';
		$empty_check['office_address']='Office address';
		$empty_check['email']='Email';
		$empty_check['password']='Password';		
		$empty_check['re-password']='Confirm password';	

		$email_check['contact_email']='Contact email';	
		$email_check['email']='Email';
		$account_exists['email']='Email';
	}
	foreach($empty_check as $k=>$v){
		if(empty($a[$k])){
			$array_errors[$k]="'".$v."' field is required."; 
		}
	}
	require_once 'Zend/Validate/EmailAddress.php';
	foreach($email_check as $k=>$v){
		$validator = new Zend_Validate_EmailAddress();
		if (!$validator->isValid($a[$k])) 
		{    
			$array_errors[$k]="'".$v."' address is not valid."; 
			// email is invalid; print the reasons    
			foreach ($validator->getMessages() as $message) 
			{
					$array_errors[$k] .= "$message\n";    
			}
		}		
	}
	foreach($account_exists as $k=>$v){
		if(!is_unique_insert('employer','login_email',$a[$k])){
			$array_errors[$k]="Account with '$v' already exists.";
		}
	}
	return $array_errors;
}
function CandidateValidator($a){
	//die($_FILES["document_file"]["type"]);
	global $_FILES,$filename_to_upload;
	$array_errors=array();
	if(sizeof($_FILES)){
		$allowedExts = array("doc", "docx", "rtf", "pdf");
		$extension = end(explode(".", $_FILES["document_file"]["name"]));
		if ((($_FILES["document_file"]["type"] == "application/x-download")|| ($_FILES["document_file"]["type"] == "application/msword") || ($_FILES["document_file"]["type"] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') || ($_FILES["document_file"]["type"] == "application/pdf") || ($_FILES["document_file"]["type"] == "text/rtf")) && in_array($extension, $allowedExts)){
		  if ($_FILES["document_file"]["error"] > 0){
			$array_errors['document_file']="Error Uploading 'Document File' for your resume. "."Return Code: " . $_FILES["document_file"]["error"];
		 }
		 else
		 {
			$filename_to_upload=substr(md5(rand(0,999)),rand(0,5),rand(4,15))."_".basename( $_FILES["document_file"]["name"]);
			if (file_exists(UPLOAD_PATH.DS. $filename_to_upload)){
			   $array_errors['document_file'] = $filename_to_upload . " already exists. ";
			}
			else{
			  if(!move_uploaded_file($_FILES["document_file"]["tmp_name"],UPLOAD_PATH.DS. $filename_to_upload)){
				 $array_errors['document_file'] = "Error uploading file: $filename_to_upload ."; 
			  }
			  

			 }
		  }
	    }
		else{
		  $array_errors['document_file'] =  "Invalid file";
		}		
	}
	else{
		$array_errors['document_file']="'Document File' for your resume is required.";	
	}
	if(!is_unique_insert('candidates','email',$a['email'])){
		$array_errors['email']='Given email address is already in use.';
	}
	else{
		$empty_check=array('name'=>'Name','email'=>'Password','password'=>'Password','re-password'=>'Confirm Password','resume_headline'=>'Resume headlines');
		$numeric_check=array('mobile_no'=>'Mobile number','ll_phone_no'=>'Landline number');
		$mob_or_ll=array('ll_phone_no'=>'Landline number');
		foreach($empty_check as $k=>$v){
			if(empty($a[$k])){
				$array_errors[$k]="'".$v."' field is required."; 
			}
		}
		if(!isset($a['mobile_no']))
		{
			$emptyll=true;
			foreach($mob_or_ll as $k=>$v){
				if(empty($a[$k])){
					$array_errors['LL']="'$v' is required."; 
				}
				else
					$emptyll=false;
			}
			if(!isset($array_errors['LL']) || !$emptyll){
				unset($array_errors['mobile_no']);
			}
		}
		if($a['password']!=$a['re-password'])
		{
			$array_errors['password']="'Password' and 'Confirm password' should match."; 
		}
		foreach($numeric_check as $k){
			if(!empty($a[$k]) && !is_numeric(trim($a[$k]))){
				$array_errors[$k]="'".str_replace('_',' ',$k)."' field should be numeric."; 
			}
		}	
	}
	return 	$array_errors;
}
function ShowErrorDiv($v,$i){
	$errorSpan='<span style="-moz-user-select: none;" id="zpFormField'.$i.'ErrorText" class="SSERROR zpFormInternalE'.$i.' zpFormError">'.$v.'</span>';
	return $errorSpan;//'<div class="field_error">&nbsp;&nbsp;'.$v.'</div>';
}
function p_r($var,$is_die=false){
	echo '<pre>'.print_r($var,1).'</pre>';
	if($is_die){
		die();
	}
}
?>