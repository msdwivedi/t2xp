<?php
if($_SERVER['HTTP_HOST']=='localhost')
{
	$link=mysql_connect($db_settings_local['host'],$db_settings_local['username'],$db_settings_local['password']);
	mysql_select_db($db_settings_local['dbname'],$link);
	$tableprefix=$db_settings_local['tableprefix'];
}
else{
	$link=mysql_connect($db_settings_production['host'],$db_settings_production['username'],$db_settings_production['password']);
	mysql_select_db($db_settings_production['dbname'],$link);	
	$tableprefix=$db_settings_production['tableprefix'];
}
function GetRecentlyPostedJobList($start=0, $end=3){
	global $link,$tableprefix;	
	$sql="SELECT * FROM ".$tableprefix."jobpost WHERE date(posted_date)>=date_sub(Now(),INTERVAL 30 day) AND date(posted_date)<=now() LIMIT $start,$end";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	while($r=mysql_fetch_assoc($result)){
		$cities=GetCities($r['job_location']);
		$arr[$i]['joblink']='jobdetails.php?jobid='.$r['id'];
		$arr[$i]['jobtitle']=$r['job_title'];
		$arr[$i]['location']=implode(", ",$cities);
		$arr[$i]['jobdesc']=substr($r['requirements'],0,150).'...';
		$arr[$i]['postdate']=date("d/m/Y",strtotime($r['posted_date']));
		$arr[$i]['desired_experience']=$r['desired_experience'];
		$arr[$i]['applylink']='jobdetails.php?jobid='.$r['id'];
		$i++;
	}
	return $arr;
}
function GetJobDetails($a){

	global $link,$tableprefix;	
	$sql="SELECT * FROM ".$tableprefix."jobpost WHERE ";
	if(isset($a['jobid']) && $a['jobid']!='')
	{
		$sql .=" id='".dbin($a['jobid'])."'";
	}
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	$arrjobtype=array('1'=>'Permanent','2'=>'Temporary/Contractual','3'=>'Freelancer');
	while($r=mysql_fetch_assoc($result)){
		$cities=GetCities($r['job_location']);
		$bizlocation=GetCities($r['business_location']);

		$fa=GetFunctionalAreaNames($r['functional_area']);
		$inds=GetIndustriesNames($r['industry']);
		$job_role=GetJobRolesNames($r['job_role']);
		$arr[$i]['id']=$r['id'];
		$arr[$i]['jobtitle']=$r['job_title'];
		$arr[$i]['company_name']=$r['company_name'];
		$arr[$i]['contact_name']=$r['contact_name'];
		$arr[$i]['contact_email']=$r['contact_email'];
		$arr[$i]['contact_mobile']=$r['contact_mobile'];
		$arr[$i]['contact_landline']=$r['contact_landline'];
		$arr[$i]['business_location']=implode(", ",$bizlocation);
		$arr[$i]['office_address']=$r['office_address'];
		 	 	 	 	
		$arr[$i]['location']=implode(", ",$cities);
		$arr[$i]['education']=implode(", ",$cities);
		$arr[$i]['job_role']=implode(", ",$job_role);
		$arr[$i]['jobdesc']=$r['requirements'];
		$arr[$i]['postdate']=date("d F, Y",strtotime($r['posted_date']));
		$arr[$i]['desired_experience']=$r['desired_experience'];
		$arr[$i]['functional_area']=implode(", ",$fa);
		$arr[$i]['industry']=implode(", ",$inds);
		$arr[$i]['jobtype']=$arrjobtype[$r['position_type']];
		$arr[$i]['salaryrange']=$r['offering_salary_range'];
		$i++;
	}
	return $arr;
	
}
function GetSearchResults($a){

	global $link,$tableprefix;	
	$sql="SELECT * FROM ".$tableprefix."jobpost WHERE 1";
	//p_r($a,1);
	if(isset($a['as_values_keywords']) && $a['as_values_keywords']!='')
	{
		$keywords=explode(",",trim($a['as_values_keywords'],","));
		$sql .= " AND (";
		foreach($keywords as $eachkw)
		{
			$sql .= "  job_title LIKE '%".dbin($eachkw)."%' OR requirements  LIKE '%".dbin($eachkw)."%' OR company_name LIKE '%".dbin($eachkw)."%' OR";
			if(is_unique_insert('keywords','keyword',$eachkw)){
				mysql_query("INSERT INTO ".$tableprefix."keywords SET keyword='".dbin($eachkw)."'") or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
			}
		}
		$sql=trim($sql,"OR").")";
	}
	if(isset($a['as_values_location']) && $a['as_values_location']!='')
	{
		$locations=GeCitiesIds(trim($a['as_values_location'],","));
		$sql .= " AND (";
		foreach($locations as $eachloc)
		{
			$sql .= "  find_in_set('$eachloc',job_location) OR";
		}
		$sql=trim($sql,"OR").")";
	}	
	
	$sql .= " ORDER by posted_date DESC";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	while($r=mysql_fetch_assoc($result)){
		$cities=GetCities($r['job_location']);
		$arr[$i]['joblink']='jobdetails.php?jobid='.$r['id'];
		$arr[$i]['jobtitle']=$r['job_title'];
		$arr[$i]['company_name']=$r['company_name'];
		$arr[$i]['location']=implode(", ",$cities);
		$arr[$i]['jobdesc']=substr($r['requirements'],0,150).'...';
		$arr[$i]['postdate']=date("d/m/Y",strtotime($r['posted_date']));
		$arr[$i]['desired_experience']=$r['desired_experience'];
		$i++;
	}
	return $arr;
	
}
function GeCitiesIds($locs){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."cities WHERE name IN('".str_replace(",","','",$locs)."')";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	while($r=mysql_fetch_assoc($result)){
		$arr[$i]=$r['id'];
		$i++;
	}
	return $arr;	
}
function GetCities($ids){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."cities WHERE id IN($ids)";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	while($r=mysql_fetch_assoc($result)){
		$arr[$i]=$r['name'];
		$i++;
	}
	return $arr;
}
function GetFunctionalAreaNames($ids){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."functionalarea WHERE id IN($ids)";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	while($r=mysql_fetch_assoc($result)){
		$arr[$i]=$r['areaname'];
		$i++;
	}
	return $arr;
}
function GetIndustriesNames($ids){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."industries WHERE id IN($ids)";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	while($r=mysql_fetch_assoc($result)){
		$arr[$i]=$r['industryname'];
		$i++;
	}
	return $arr;
}
function GetJobRolesNames($ids){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."jobrole WHERE id IN($ids)";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$arr=array();
	$i=0;
	while($r=mysql_fetch_assoc($result)){
		$arr[$i]=$r['rolename'];
		$i++;
	}
	return $arr;
}
function GetExperience($type='year')
{
	$array_return=array();
	if($type=='year'){
		$array_return[]='-select-';
		$array_return[]='Fresher';
		for($i=0;$i<=MAX_EXPERIENCE;$i++)
			$array_return[]=$i;
		$array_return[]=MAX_EXPERIENCE.'+';
	}
	else if($type=='month'){
		$array_return[]='Select';
		for($i=0;$i<=11;$i++)
			$array_return[]=$i;		
	}
	return $array_return;
}
function GetExperienceRange(){
	$array_return=array();
	$array_return[]='-select-';
	$array_return[]='Fresher';
	for($i=1;$i<=MAX_EXPERIENCE;$i+=3)
		$array_return[]=$i."-".($i+2)." years";
	$array_return[]=($i).'+ years.';
	return $array_return;	
}
function GetLocation(){
	global $link,$tableprefix;
	$array_return=array();
	$array_return[0]['id']='';
	$array_return[0]['name']='';
	$array_return[0]['type']='';
	$i=1;
		
	$sql="SELECT * FROM ".$tableprefix."stategroups";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	while($rSG=mysql_fetch_assoc($result)){
		if(isset($array_return[$i]['id']))
			$i++;
		$array_return[$i]['id']='0';
		$array_return[$i]['name']="---- ".$rSG['groupname']." ---";
		$array_return[$i]['type']='optgroup';	
		$sql="SELECT * FROM ".$tableprefix."cities WHERE find_in_set('".$rSG['id']."',stategroup_id)";
		$resultGroupCities=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
		while($rGC=mysql_fetch_assoc($resultGroupCities)){
			if(isset($array_return[$i]['id']))
				$i++;
			$array_return[$i]['id']=$rGC['id'];
			$array_return[$i]['name']=$rGC['name'];
			$array_return[$i]['type']='city';
		}
	}
	$sql="SELECT * FROM ".$tableprefix."states WHERE stategroup_ids is null OR stategroup_ids=''";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	while($rSt=mysql_fetch_assoc($result)){	
		if(isset($array_return[$i]['id']))
			$i++;		
		$array_return[$i]['id']='0';
		$array_return[$i]['name']="---- ".$rSt['name']." ---";
		$array_return[$i]['type']='optgroup';			
		$sql="SELECT * FROM ".$tableprefix."cities WHERE state_id='".$rSt['id']."'";
		$resultCities=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
		while($rC=mysql_fetch_assoc($resultCities)){
			if(isset($array_return[$i]['id']))
				$i++;;			
			$array_return[$i]['id']=$rC['id'];
			$array_return[$i]['name']=$rC['name'];
			$array_return[$i]['type']='city';
						
		}		
	}
	return $array_return;
}
function GetFunctionalArea(){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."functionalarea";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$array_return=array();
	$array_return[0]['id']='';
	$array_return[0]['areaname']='';
	while($r=mysql_fetch_assoc($result)){
		$array_return[]=$r;		
	}
	return $array_return;
}
function GetJobRole($func_id=null){
	global $link,$tableprefix;

	$array_return=array();
	$array_return[0]['id']='';
	$array_return[0]['name']='';
	$array_return[0]['type']='';	
	$i=1;

	$sql="SELECT * FROM ".$tableprefix."jobrolegroup WHERE 1 ";
	if(!is_null($func_id))
	{
		$sql .= " AND func_id='".dbin($func_id)."'";	
	}	
	//echo $sql.'<br>';
	$result_grp=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	if(mysql_num_rows($result_grp)){
		while($rJG=mysql_fetch_assoc($result_grp)){
			if(isset($array_return[$i]['id']))
				$i++;				
			$array_return[$i]['id']=$rJG['id'];
			$array_return[$i]['name']="---- ".$rJG['groupname']." ---";
			$array_return[$i]['type']='optgroup';	
			
			$sql="SELECT * FROM ".$tableprefix."jobrole WHERE groupid ".(empty($rJG['id'])?' is_null':" ='".dbin($rJG['id']))."'";
			if(!is_null($func_id))
			{
				$sql .= " AND func_id='".dbin($func_id)."'";	
			}
			//echo $sql.'<br>';
			$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
			
			while($r=mysql_fetch_assoc($result)){
				if(isset($array_return[$i]['id']))
					$i++;
				$array_return[$i]['id']=$r['id'];
				$array_return[$i]['name']=$r['rolename'];
				$array_return[$i]['type']='role';			
			}			
		}	
	}
	else
	{
		if(isset($array_return[$i]['id']))
			$i++;
		$array_return[$i]['id']='1';
		$array_return[$i]['name']='Freshers/Trainee';
		$array_return[$i]['type']='role';	
			$i++;
		$array_return[$i]['id']='-1';
		$array_return[$i]['name']='Other';
		$array_return[$i]['type']='role';						
	}
	return $array_return;	
}
function GetIndustries(){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."industries";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$array_return=array();
	$array_return[0]['id']='';
	$array_return[0]['industryname']='';
	
	while($r=mysql_fetch_assoc($result)){
		$array_return[]=$r;		
	}
	return $array_return;
}
function GetEducation($type='UG'){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."education WHERE `type`='$type'";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$array_return=array();
	$array_return[0]['id']='';
	$array_return[0]['coursename']='';
	
	while($r=mysql_fetch_assoc($result)){
		$array_return[]=$r;		
	}
	return $array_return;
}

function RegisterCandidate($a){
	global $link,$tableprefix,$filename_to_upload;
	$sql  = "INSERT INTO ".$tableprefix."candidates SET ";
	$sql .= " name='".dbin($a['name'])."',"; 
	$sql .= " mobile_no='".dbin($a['mobile_no'])."',"; 
	$sql .= " landline_no='".dbin($a['ll_phone_no'])."',"; 
	$sql .= " email='".dbin($a['email'])."',"; 
	$sql .= " password='".dbin($a['password'])."',"; 
	$sql .= " experience_Y='".dbin($a['experience_Y'])."',"; 
	$sql .= " experience_M='".dbin($a['experience_M'])."',"; 
	$sql .= " keyskills='".dbin($a['keyskills'])."',"; 
	$sql .= " resume_headline='".dbin($a['resume_headline'])."',"; 
	$sql .= " job_function='".dbin($a['job_function'])."',"; 
	$sql .= " current_industry='".dbin($a['current_industry'])."',"; 
	$sql .= " education_basic_graduation='".dbin($a['education_basic_graduation'])."',"; 
	$sql .= " education_pg='".dbin($a['education_pg'])."',"; 
	$sql .= " education_phd='".dbin($a['education_phd'])."',"; 
	$sql .= " education_cirtificate1='".dbin($a['education_cirtificate1'])."',"; 
	$sql .= " education_cirtificate2='".dbin($a['education_cirtificate2'])."',"; 
	$sql .= " education_cirtificate3='".dbin($a['education_cirtificate3'])."',";	
	$sql .= " resume_file='$filename_to_upload'";
	mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	return mysql_insert_id($link);	
}
function EmployerCheckLogin($login_email,$login_password){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."employer WHERE ";
	$sql.="login_email='".dbin($login_email)."' AND ";
	$sql.="login_password='".dbin($login_password)."'";
	//echo $sql;die;
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	return (mysql_num_rows($result)>0);
}
function RegisterEmployer($a){
	global $link,$tableprefix;
	$sql=" INSERT INTO ".$tableprefix."employer SET ";
	$sql.="employer_name='".dbin($a['contact_name'])."',";
	$sql.="employer_company_name='".dbin($a['company_name'])."',";
	$sql.="employer_mobile='".dbin($a['mobile_no'])."',";
	$sql.="employer_landline='".dbin($a['ll_phone_no'])."',";
	$sql.="employer_location='".dbin($a['current_location'])."',";
	$sql.="employer_office_address='".dbin($a['office_address'])."',";
	$sql.="login_email='".dbin($a['email'])."',";
	$sql.="login_password='".dbin($a['password'])."',";
	$sql.="employer_status='Inactive';";
	mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	return mysql_insert_id($link);
}
function PostJob($a,$employer_id){
	global $link,$tableprefix;
	$sql=" INSERT INTO ".$tableprefix."jobpost SET ";
	$sql.="job_title='".dbin($a['job_title'])."',";
	$sql.="industry='".dbin($a['current_industry'])."',";
	$sql.="functional_area='".dbin($a['job_function'])."',";
	$sql.="job_role='".dbin($a['job_role'])."',";
	$sql.="contact_name='".dbin($a['contact_name'])."',";
	$sql.="company_name='".dbin($a['company_name'])."',";
	$sql.="position_type='".dbin($a['employmenttype'])."',";
	$sql.="desired_experience='".dbin($a['experience_range'])."',";
	$sql.="offering_salary_range='".dbin($a['salary_range'])."',";
	$sql.="job_location='".dbin(implode(",",$a['job_location']))."',";
	$sql.="requirements='".dbin($a['job_requirements'])."',";
	$sql.="contact_mobile='".dbin($a['mobile_no'])."',";
	$sql.="contact_landline='".dbin($a['ll_phone_no'])."',";
	$sql.="business_location='".dbin($a['current_location'])."',";
	$sql.="office_address='".dbin($a['office_address'])."',";
	$sql.="employer_id='".dbin($employer_id)."'";
	mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	return mysql_insert_id($link);	
}
function PostJobForEmployer($a){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."employer WHERE ";
	$sql.="login_email='".dbin($a['login_email'])."' AND ";
	$sql.="login_password='".dbin($a['login_password'])."'";
	//echo $sql;die;
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	if(mysql_num_rows($result)>0){
		$r=mysql_fetch_assoc($result);
		//$_SESSION['emp_login']=serialize($r);
		$a['mobile_no']			=	$r['employer_mobile'];
		$a['ll_phone_no']		=	$r['employer_landline'];
		$a['current_location']	=	$r['employer_location'];
		$a['office_address']	=	$r['employer_office_address'];
		$employer_id			=	$r['id'];
		//p_r($a,1);
		$id=PostJob($a,$employer_id);
		return $id;
	}
	return false;
}
function is_unique_insert($tblname,$fieldname,$fieldvalue){
	global $link,$tableprefix;
	$table_name=$tableprefix.str_replace($tableprefix,'',$tblname);
	$sql="SELECT * FROM ".$table_name." WHERE `$fieldname`='".dbin($fieldvalue)."'";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	if(mysql_num_rows($result)>0){
		return false;
	}else{
		return true;
	}
}
function is_unique_update($tblname,$fieldname,$fieldvalue,$idfield_name,$id_field_value){
	global $link,$tableprefix;
	$table_name=$tableprefix.str_replace($tableprefix,'',$tblname);
	$sql="SELECT * FROM ".$table_name." WHERE `$fieldname`='".dbin($fieldvalue)."' and `$idfield_name`!='".dbin($id_field_value)."'";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	if(mysql_num_rows($result)>0){
		return false;
	}else{
		return true;
	}
}
function dbin($v){
	return mysql_real_escape_string($v);
}
function GetSalaryRange(){
	$array_sal_range=array();
	$array_sal_range[]='-select-';
	for($i=1000;$i<30000;$i+=5000){
		$array_sal_range[]="INR ".$i."-".($i+4000)." thousand per month";
	}
	for($i=1;$i<20;$i+=2){
		$array_sal_range[]="INR ".$i."-".($i+1)." lacs per annum";
	}
	$array_sal_range[]="INR 20+ lacs per annum";
	return $array_sal_range;
}
function loginCandidate($a,$b=false){
	global $link,$tableprefix;
	$sql="SELECT * FROM ".$tableprefix."candidates WHERE email='".dbin($a['email'])."' AND password='".dbin($a['password'])."'";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	if(mysql_num_rows($result)>0){
		$r=mysql_fetch_assoc($result);
		$_SESSION['candidate_login']=serialize($r);
		$sql="UPDATE ".$tableprefix."candidates SET lastlogin=now() WHERE id='".dbin($r['id'])."'";
		mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
		if($b!==false){
			if(isset($b['redirecturl'])){
				header('location:'.$b['redirecturl']);
				die;
			}
			else{
				header('location:candidate_dashboard.php');
				die;			
			}
		}
		else
		{
			header('location:candidate_dashboard.php');
			die;			
		}
		return true;
	}else{
		return false;
	}
}
function IsCandidateLoggedIn(){
	return (isset($_SESSION['candidate_login']) && $_SESSION['candidate_login']!='');
}

function CandidateLogOut(){
	if(isset($_SESSION['candidate_login']) && $_SESSION['candidate_login']!=''){
		@session_unregister($_SESSION['candidate_login']);
		@session_unregister($candidate_login);
		unset($candidate_login);
		unset($_SESSION['candidate_login']);
		return true;
	}
	return false;
}
function CheckPhoneVerified(){
	global $link,$tableprefix;
	$c=unserialize($_SESSION['candidate_login']);	
	$sql="SELECT mobile_verified FROM ".$tableprefix."candidates WHERE id='".dbin($c['id'])."'";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$r=mysql_fetch_assoc($result);	
	return $r['mobile_verified'];
}
function UpdateMobileNo($no){
	global $link,$tableprefix;
	$c=unserialize($_SESSION['candidate_login']);	
	$sql="UPDATE ".$tableprefix."candidates SET mobile_no='".dbin($no)."' WHERE id='".dbin($c['id'])."'";
	mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	return true;	
}
function UpdateMobileVerify($status,$c=null){
	global $link,$tableprefix;
	if(is_null($c))
		$c=unserialize($_SESSION['candidate_login']);
	$sql="UPDATE ".$tableprefix."candidates SET mobile_verified='".dbin($status)."',mobile_verify_token='' WHERE id='".dbin($c['id'])."'";
	mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));	
}
function CandidateSessionRefresh($c=null){
	global $link,$tableprefix;
	if(is_null($c))
		$c=unserialize($_SESSION['candidate_login']);	
	$sql="SELECT * FROM ".$tableprefix."candidates WHERE id='".dbin($c['id'])."'";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	if(mysql_num_rows($result)>0){
		$r=mysql_fetch_assoc($result);
		$_SESSION['candidate_login']=serialize($r);
	}	
}
function UpdateMobileVerifyToken($token,$c=null){
	global $link,$tableprefix;
	if(is_null($c))
		$c=unserialize($_SESSION['candidate_login']);	
	$sql="UPDATE ".$tableprefix."candidates SET mobile_verify_token='".dbin($token)."' WHERE id='".dbin($c['id'])."'";
	mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));	
}
function GetCategories(){
	global $link,$tableprefix;
	if(is_null($c))
		$c=unserialize($_SESSION['candidate_login']);	
	$sql="SELECT * FROM ".$tableprefix."industries  ORDER BY industryname ASC, rand() LIMIT 20";
	$result=mysql_query($sql) or die('MySQL ERROR at line#:'.__LINE__.', file:'.__FILE__.' error description:'.mysql_error($link));
	$return=array();
	if(mysql_num_rows($result)>0){
		while($r=mysql_fetch_assoc($result)){
			$return[]= $r;
		}
	}		
	return $return;
}
?>