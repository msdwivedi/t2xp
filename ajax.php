<?php
require_once('configure.php');

$array_data=GetJobRole($_GET['job_func']);
//echo '<pre>'.print_r($array_data,1).'</pre>';
//$content='<select name="job_role" class="zpFormRequired zpFormRequiredError=\'Please\\ select\\ preferred\\ job\\ role.\'">';
$content='';
foreach( $array_data as $roledata){

	if ($roledata['type']=='optgroup')
		$content.='<optgroup label="'.$roledata['name'].'">';
	else
		$content.='<option value="'.$roledata['id'].'">'.$roledata['name'].'</option>';
}
//$content.='</select>';
echo $content;
?>
