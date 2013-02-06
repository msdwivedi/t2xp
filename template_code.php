<?php
$subject=str_ireplace(array('href="css/',"href='css/",'src="js/','images/'),array('href="templates/css/',"href='templates/css/",'src="templates/js/','templates/images/'),$subject);
preg_match_all('/<!--\s*#include\s+virtual="(.*?)\.html"\s+-->/x', $subject, $result, PREG_PATTERN_ORDER);
for ($i = 0; $i < count($result[0]); $i++) {
	$subcontent = file_get_contents($result[1][$i].".html");
	$subject = str_replace($result[0][$i],$subcontent,$subject);
}

?>