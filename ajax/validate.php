<?php require_once('../configure.php'); ?>
{
<?
sleep(1);

if(isset($_GET['email'])){
	$email = $_GET['email'];

	if(!is_unique_insert('employer','login_email',$email)) {
?>
	"success": false,
	"generalError": "This email address is already in use."
<?
	} else {
?>
	"success": true
<?
	}

} else {
?>
	"success": false,
	"generalError": "No name were given"
<?
}
?>
}
