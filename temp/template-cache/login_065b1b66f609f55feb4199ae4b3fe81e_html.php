<?php if($tpl->Get('style_show')): ?>
<style>
.t2xplore_login {
    background: none repeat scroll 0 0 #F3DF89;
    float: right;
    margin: 4px 10px 0 0;
    width: 645px;
}
.t2xplore_login_left {
    background: url("templates/images/login_bar.png") no-repeat scroll 0 0 transparent;
    float: left;
    font: bold 14px Arial,Helvetica,sans-serif;
    height: 138px;
    padding: 100px 0 0;
    width: 370px;
}
.t2xplore_login_left1 {
    float: left;
    font: bold 12px Arial,Helvetica,sans-serif;
    margin: 0;
    padding: 5px 0 0 20px;
    text-align: center;
    width: 220px;
}
.t2xplore_login_left1 h1 {
    display: inline;
    font: bold 14px Arial,Helvetica,sans-serif;
    margin: 0;
    padding: 0;
}
.t2xplore_login_right {
    background: url("templates/images/login_bar.png") no-repeat scroll -371px 0 transparent;
    float: left;
    height: 208px;
    padding: 30px 0 0;
    width: 275px;
}
.t2xplore_login_home {
    width: 250px;
}
.t2xplore_login_home strong {
    color: #FFFFFF;
    float: left;
    font: bold 15px Arial,Helvetica,sans-serif;
    padding: 2px 0 5px 26px;
    text-align: center;
}
.t2xplore_login_home ul {
    border-bottom: 1px solid #B3B3B3;
    float: left;
    margin: 0 18px;
    padding: 0 0 4px;
    width: 210px;
}
.t2xplore_login_left1 a {
    color: #333333;
    display: block;
    float: left;
    padding: 22px 0 0;
    width: 234px;
}
.t2xplore_login_home li {
    color: #333333;
    float: left;
    font: 11px Arial,Helvetica,sans-serif;
}
.new_user {
    border-top: 1px solid #E8E8E8;
    clear: both;
    float: left;
    font: bold 13px Arial,Helvetica,sans-serif;
    margin: 0 20px;
    padding: 3px 0;
    text-align: center;
    width: 210px;
}
.t2xplore_login_home li.error_text {
    color: #FF0000;
    font-weight: bold;
}
.t2xplore_login_home1 {
    background: none repeat scroll 0 0 #FFFFFF;
    border-left: 5px solid #FFE27A;
    border-right: 5px solid #FFE27A;
    float: left;
    padding: 8px 0 0;
    width: 238px;
}
.t2xplore_login_home1_bottom {
    background: url("templates/images/login_bottom.png") repeat scroll 0 0 transparent;
    float: left;
    height: 5px;
    width: 248px;
}
.t2xplore_login_home1 form {
    margin: 0;
    padding: 0;
}
.home_log {
    color: #333333;
    float: left;
    font: 11px Arial,Helvetica,sans-serif;
    padding: 2px 0;
    text-align: right;
    width: 55px;
}
.home_log1 {
    float: left;
    font: 11px Arial,Helvetica,sans-serif;
    margin: 0 0 0 5px;
    padding: 2px 0;
    width: 147px;
}
.home_log1 a {
    text-decoration: underline;
}
</style>
<?php endif; ?>
<div class="indent1">
				<div class="wrapper">
					<article class="fullwidth">


<div class="t2xplore_login">
  <div class="t2xplore_login_left">
    <div class="t2xplore_login_left1">You will get more <b>jobs in India</b>
      , now best match and <br>
      lots of great things </div>
  </div>
  <div id="cndidate_login_widget" class="t2xplore_login_right">
    <div class="t2xplore_login_home"><strong>Existing Users, Login Here</strong>
      <div class="t2xplore_login_home1">
        <form class="cls_cnd_login floatleft" method="post" action="">
          <input type="hidden" id="id_error_count" value="0" name="error_count">
          <ul>
            <li style="width:196px;;color:#ff0000;font:bold 11px arial" class="cls_loginnotmatch disable">* All fields are mendatory. </li>
             <li style="width:196px;;color:#ff0000;font:bold 11px arial" class="cls_loginnotmatch disable"><?php echo $tpl->Get('loginError'); ?></li>
            <li class="home_log">Email ID</li>
            <li class="home_log1">
              <input type="text"  value="" id="id_email" class="" name="email" maxlength="100" />
            </li>
            <li class="home_log">Password</li>
            <li class="home_log1">
              <input type="password" id="id_password" class="" name="password" maxlength="15">
            </li>
            <li class="home_log">&nbsp;</li>
            <li class="home_log1">
              <input type="checkbox" id="checkbox" name="keep_me_signed_in">
              Keep me logged in </li>
            <li class="home_log">&nbsp;</li>
            <li class="home_log1">
              <input type="submit" name="btn_homepage_signin" value="Login" id="btn_login" class="cls_candidate_login submit">
              &nbsp;<a name="link_forgetpsw" class="cls_frgt_pwd" href="forgotpassword.php">Forgot Password?</a></li>
          </ul>
        </form>
        <div class="new_user">New User? <a href="registration.php<?php echo $tpl->Get('QueryString'); ?>">Register Now!</a></div>
      </div>
    </div>
    <div class="t2xplore_login_home1_bottom"></div>
  </div>
</div>
<div style="float:left; border:1px solid #990000; width:270px; height:226px;margin:3px 20px 0px; padding:6px; border-radius:5px">

<div>
<h2 style="margin:1px;padding:1px;">Not member yet?</h2>
<p style="margin:0px 10px;text-align:justify; font-family:Calibri">Whether you are actively looking, or just curious about opportunities, Talent2Xplore will advance your career.</p> 
<a href="registration.php<?php echo $tpl->Get('QueryString'); ?>" style="background:url(templates/images/blue-btn-bg.gif) repeat-x; height:25px; width:150px; text-align:center; border-radius:5px;display:block;font-family:Calibri">Register Now!!!</a></div>

</div>
</article>
</div>
</div>